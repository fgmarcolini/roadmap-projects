/*
Copyright © 2026 massemiso <massemiso@proton.me>
*/
package cmd

import (
	"fmt"
	"log"
	"net/url"
	"os"

	"caching-proxy/internal/server"

	"github.com/spf13/cobra"
)

var (
	port       int
	origin     string
	clearCache bool
)

var rootCmd = &cobra.Command{
	Use:   "caching-proxy",
	Short: "Cache server that acts as proxy for your origin server",
	Run: func(cmd *cobra.Command, args []string) {
		if port == -1 && origin == "" && !clearCache {
			cmd.Help()
			os.Exit(0)
		}

		if err := RunE(port, origin, clearCache); err != nil {
			log.Fatalln(err.Error())
		}
	},
}

func Execute() {
	err := rootCmd.Execute()
	if err != nil {
		os.Exit(1)
	}
}

func init() {
	rootCmd.PersistentFlags().IntVar(&port, "port", -1, "port of the cache proxy server")
	rootCmd.PersistentFlags().StringVar(&origin, "origin", "", "origin server which responses will be cached")
	rootCmd.PersistentFlags().BoolVar(&clearCache, "clear-cache", false, "if you want to clear the stored cache")
}

func RunE(port int, origin string, clearCache bool) error {
	cache := server.NewCacheService("caching_proxy")

	if clearCache {
		err := cache.ClearCache()
		if err != nil {
			return err
		}

		log.Print("CACHE CLEARED")
		return nil
	}

	if port == -1 || port <= 0 {
		return fmt.Errorf("port has to be greater than 0")
	}

	if origin == "" || !isAURL(origin) {
		return fmt.Errorf("origin has to follow the format: 'http://localhost.com'")
	}

	proxyReverse := server.NewReverseProxy(origin, port, cache)
	err := proxyReverse.RunServer()
	if err != nil {
		return err
	}

	return nil
}

func isAURL(rawUrl string) bool {
	_, err := url.ParseRequestURI(rawUrl)
	return err == nil
}
