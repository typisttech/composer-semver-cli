package main_test

import (
	"testing"

	"github.com/rogpeppe/go-internal/testscript"
)

//go:generate go run ./main.go
func Test(t *testing.T) {
	testscript.Run(t, testscript.Params{
		Dir: "testdata/scripts",
	})
}
