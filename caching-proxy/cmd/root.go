/*
Copyright © 2026 NAME HERE <EMAIL ADDRESS>
*/
package cmd

import (
	"fmt"
	"log"
	"os"
	"regexp"

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
	// TODO
	if clearCache {
		return nil
	}

	if port == -1 || port <= 0 {
		return fmt.Errorf("port has to be greater than 0")
	}

	if origin == "" || !isAURL(origin) {
		return fmt.Errorf("origin has to follow the format: 'http://localhost.com'")
	}

	return nil
}

func isAURL(url string) bool {
	expr := `^((https?|ftp|smtp):\/\/)?(www.)?[a-z0-9]+\.[a-z]+(\/[a-zA-Z0-9#]+\/?)*$`
	r, _ := regexp.Compile(expr)
	return r.MatchString(url)
}
