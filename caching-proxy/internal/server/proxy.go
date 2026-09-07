package server

import (
	"fmt"
	"log"
	"net/http"
	"net/http/httputil"
	"net/url"
)

type ReverseProxyInterface interface {
	RunServer() error
}

type ReverseProxy struct {
	URL  string
	Port int
}

func NewReverseProxy(url string, port int) *ReverseProxy {
	return &ReverseProxy{
		URL:  url,
		Port: port,
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

			log.Printf("--> [REQUEST] %s %s | Forwarding to: %s",
				r.In.Method,
				r.In.URL.Path,
				r.Out.URL.String(),
			)
		},
		ModifyResponse: func(res *http.Response) error {
			res.Header.Set("X-Proxy-Header", "Caching-Proxy")
			res.Header.Set("Server", "Go-Custom-Proxy")
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

	log.Printf("Proxy server for %s running on port %d\n", rp.URL, rp.Port)
	return http.ListenAndServe(fmt.Sprintf(":%d", rp.Port), proxy)
}
