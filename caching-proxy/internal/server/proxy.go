package server

import (
	"bytes"
	"context"
	"fmt"
	"io"
	"log"
	"net/http"
	"net/http/httputil"
	"net/url"
)

type ReverseProxyInterface interface {
	RunServer() error
	ServeHTTP(*http.ResponseWriter, *http.Request)
}

type ReverseProxy struct {
	URL           string
	Port          int
	Cache         CacheServiceInterface
	httpUtilProxy httputil.ReverseProxy
}

func NewReverseProxy(url string, port int, cache CacheServiceInterface) *ReverseProxy {
	return &ReverseProxy{
		URL:   url,
		Port:  port,
		Cache: cache,
	}
}

func (rp *ReverseProxy) RunServer() error {
	target, err := url.Parse(rp.URL)
	if err != nil {
		return err
	}

	proxy := &httputil.ReverseProxy{
		Rewrite: func(r *httputil.ProxyRequest) {
			r.SetURL(target)
			r.SetXForwarded()

			log.Printf("--> [CACHE-MISS] %s %s | Forwarding request to: %s",
				r.In.Method,
				r.In.URL.Path,
				r.Out.URL.String(),
			)
		},

		// CACHE MISS
		ModifyResponse: func(res *http.Response) error {
			// 1. Read the body
			bodyBytes, err := io.ReadAll(res.Body)
			if err != nil {
				return err
			}

			// 2. Save to cache
			cacheKey := res.Request.Context().Value("cacheKey").(string)
			err = rp.Cache.SaveCache(cacheKey, &CachedResponse{
				Headers: res.Header,
				Body:    bodyBytes,
			})
			if err != nil {
				log.Printf("Failed to save cache: %v", err)
			}

			// 3. Restore the body for the client
			res.Body = io.NopCloser(bytes.NewBuffer(bodyBytes))

			// 4. Update Content-Length
			res.ContentLength = int64(len(bodyBytes))
			res.Header.Set("Content-Length", fmt.Sprint(len(bodyBytes)))

			// Set headers
			res.Header.Set("X-Cache", "MISS")

			log.Printf("<-- [RESPONSE] %s %s | Status: %d (%s)",
				res.Request.Method,
				res.Request.URL.Path,
				res.StatusCode,
				res.Status,
			)
			return nil
		},
		ErrorHandler: func(w http.ResponseWriter, r *http.Request, err error) {
			http.Error(w, "Bad Gateway", http.StatusBadGateway)
		},
	}

	rp.httpUtilProxy = *proxy
	log.Printf("Proxy server for %s running on port %d\n", rp.URL, rp.Port)
	return http.ListenAndServe(fmt.Sprintf(":%d", rp.Port), rp)
}

func (rp *ReverseProxy) ServeHTTP(w http.ResponseWriter, r *http.Request) {
	parsedUrl, err := url.Parse(rp.URL)
	if err != nil {
	}
	cacheKey := rp.Cache.GetCacheKey(parsedUrl.Hostname(), r.URL.Path, r.Method)

	// save cache key to context
	r = r.WithContext(context.WithValue(r.Context(), "cacheKey", cacheKey))

	cachedResponse, err := rp.Cache.CheckCache(cacheKey)

	// CACHE HIT
	if err == nil && cachedResponse != nil {
		for key, values := range cachedResponse.Headers {
			for _, value := range values {
				w.Header().Add(key, value)
			}
		}
		w.Header().Set("X-Cache", "HIT")
		w.Write(cachedResponse.Body)

		log.Printf("<-- [CACHE-HIT] %s %s | Retrieving %s",
			r.Method,
			r.URL.Path,
			cacheKey,
		)
		return
	}

	// CACHE MISS
	rp.httpUtilProxy.ServeHTTP(w, r)
}
