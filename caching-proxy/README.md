# 📦 Caching Proxy

[← Back to Roadmap Projects](../README.md)

A caching reverse proxy CLI proposed by [roadmap.sh](https://roadmap.sh/projects/caching-server).

## 🌟 Highlights

- **Reverse Proxying:** Forwards requests to any origin server and returns its response untouched.
- **Transparent Caching:** Repeated requests are served from a local disk cache instead of hitting the origin.
- **Cache Diagnostics:** Every response reports `X-Cache: HIT` or `X-Cache: MISS`, so you can see exactly where it came from.
- **Automatic Expiry:** Cached entries older than 5 minutes are treated as misses and re-fetched.
- **Cache Clearing:** A single flag wipes the entire stored cache.
- **Method-Aware Keys:** Cache entries are keyed by host, path, and HTTP method, so `GET /posts` and `POST /posts` never collide.
- **Built with Cobra:** Clean flag parsing and built-in help output.

## ℹ️ Project Context

This project was built to master **HTTP proxying** and **response caching** in Go.

The goal was to understand what happens between a client and an origin server: rewriting requests, preserving headers and bodies, and deciding when a stored response is still fresh enough to reuse. It served as a practical introduction to `net/http/httputil.ReverseProxy`, cache key design, and TTL-based invalidation.

## 🚀 Usage

```bash
# Start the proxy on port 3000, forwarding to dummyjson.com
./caching-proxy --port 3000 --origin http://dummyjson.com

# Request through the proxy (first request: MISS)
curl -i http://localhost:3000/products
# X-Cache: MISS

# Same request again (served from cache: HIT)
curl -i http://localhost:3000/products
# X-Cache: HIT

# Clear all cached responses
./caching-proxy --clear-cache
# CACHE CLEARED
```

### Options

| Flag            | Description                                       | Default |
| :-------------- | :------------------------------------------------ | :------ |
| `--port`        | Port the proxy listens on (required to start)     | N/A     |
| `--origin`      | URL of the server to forward requests to          | N/A     |
| `--clear-cache` | Delete all cached responses and exit              | `false` |

_Note: Running the binary with no flags prints the help message._

## ⬇️ Getting Started

### Prerequisites

- Git
- Go 1.27+

### Installation & Run

1. Clone the repository (Sparse checkout for this project only):

```bash
git clone --filter=blob:none --sparse https://github.com/massemiso/roadmap-projects
cd roadmap-projects
git sparse-checkout set caching-proxy
cd caching-proxy
```

2. Build the binary:

```bash
go build -o caching-proxy
```

3. Run:

```bash
./caching-proxy --port <number> --origin <url>
```

## 🛠️ Technical Architecture

This application follows a modular design focused on **Separation of Concerns**:

- **`cmd/`:** Defines the Cobra command, flags, input validation, and wires the proxy to the cache.
- **`internal/server/proxy.go`:** Wraps `httputil.ReverseProxy`. `ServeHTTP` checks the cache first and delegates misses to the upstream proxy; `ModifyResponse` captures the body and headers before storing them.
- **`internal/server/cache.go`:** A filesystem-backed `CacheService` that generates keys, writes JSON entries, checks expiry, and clears the store.
- **Interface-Driven Design:** `CacheServiceInterface` and `ReverseProxyInterface` decouple the proxy from its storage, keeping both layers testable and swappable.
- **Stream-Safe Body Handling:** Response bodies are read once, cached, then restored via `io.NopCloser` with a corrected `Content-Length`, so clients receive a complete response.

### How Caching Works

- Entries are stored as JSON files in the OS temp directory: `<tmp>/caching_proxy/`.
- A key looks like `<host>_<path>_<method>.json` (e.g. `dummyjson_com_products_get.json`).
- Entries have a **5-minute TTL**; anything older is ignored and overwritten on the next miss.
- `--clear-cache` removes the entire cache directory.

## 📋 TODO & Future Improvements

- [ ] **Test Suite:** Unit tests for key generation and expiry, plus integration tests using `httptest` origin servers.
- [ ] **Configurable TTL:** Expose the 5-minute cache duration as a CLI flag.

## 🧠 Key Learnings

- **Reverse Proxying:** Using `httputil.ReverseProxy` to rewrite and forward requests without losing headers.
- **Cache Design:** Choosing cache keys that respect host, path, and method to avoid stale or incorrect responses.
- **TTL & Invalidation:** Balancing freshness against performance with simple time-based expiry.
- **HTTP Semantics:** Preserving status codes, headers, and body length when intercepting responses.

---

[← Back to Roadmap Projects](../README.md)
