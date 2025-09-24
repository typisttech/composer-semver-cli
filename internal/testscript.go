package internal

import (
	"testing"

	"github.com/rogpeppe/go-internal/testscript"
)

func RunTestscript(t *testing.T) {
	testscript.Run(t, testscript.Params{
		Dir: "testdata",
		Setup: func(env *testscript.Env) error {
			env.Setenv("COLUMNS", "999") // To avoid line breaks in output.
			return nil
		},
	})
}
