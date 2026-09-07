package server

import (
	"encoding/json"
	"fmt"
	"net/http"
	"os"
	"path/filepath"
	"strings"
	"time"
)

type CachedResponse struct {
	Headers http.Header
	Body    []byte
}

type CacheServiceInterface interface {
	CheckCache(string) (*CachedResponse, error)
	SaveCache(string, *CachedResponse) error
	GetCacheKey(string, string, string) string
	ClearCache() error
}

type CacheService struct {
	Dir string
}

func NewCacheService(dirName string) *CacheService {
	directory := os.TempDir() + "/" + dirName
	return &CacheService{
		Dir: directory,
	}
}

func (s *CacheService) GetCacheKey(hostname string, path string, httpMethod string) string {
	sanitizedHostname := strings.ReplaceAll(hostname, ".", "_")
	sanitizedPath := strings.ReplaceAll(strings.TrimPrefix(path, "/"), "/", "_")
	sanitizedHttpMethod := strings.ToLower(httpMethod)
	return filepath.Join(s.Dir,
		fmt.Sprintf("%s_%s_%s.json", sanitizedHostname, sanitizedPath, sanitizedHttpMethod))
}

func (s *CacheService) CheckCache(cacheFile string) (*CachedResponse, error) {
	info, err := os.Stat(cacheFile)
	if err != nil {
		return nil, err
	}

	if time.Since(info.ModTime()) > 5*time.Minute {
		return nil, nil
	}

	data, err := os.ReadFile(cacheFile)
	if err != nil {
		return nil, err
	}

	var cachedResponse CachedResponse
	if err := json.Unmarshal(data, &cachedResponse); err != nil {
		return nil, err
	}

	return &cachedResponse, nil
}

func (s *CacheService) SaveCache(cacheFile string, cachedResponse *CachedResponse) error {
	if _, err := os.Stat(s.Dir); err != nil {
		if os.IsNotExist(err) {
			os.Mkdir(s.Dir, 0o755)
		} else {
			return err
		}
	}

	data, err := json.Marshal(cachedResponse)
	if err != nil {
		return err
	}

	return os.WriteFile(cacheFile, data, 0o644)
}

func (s *CacheService) ClearCache() error {
	return os.RemoveAll(s.Dir)
}
