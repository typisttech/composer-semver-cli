package internal

import (
	"os"
	"path/filepath"
)

func ensureDirEmpty(dir string) (string, error) {
	p := filepath.Join(scriptDir, dir)
	a, err := filepath.Abs(p)
	if err != nil {
		return "", err
	}

	if err := os.RemoveAll(a); err != nil {
		return "", err
	}

	if err := os.MkdirAll(a, 0755); err != nil {
		return "", err
	}

	return a, nil
}

const scriptDir = "testdata/script"

type txtarWriter interface {
	Name() string
	Write(f *os.File) error
}

func Generate[T txtarWriter](initiator, dir string, cases ...T) error {
	os.Stdout.Write([]byte("\n==> Running " + initiator + "\n"))

	d, err := ensureDirEmpty(dir)
	if err != nil {
		return err
	}
	os.Stdout.Write([]byte("Generating scripts under " + d + "\n"))

	for _, w := range cases {
		n := sanitizeFilename(w.Name()) + ".txtar"
		os.Stdout.Write([]byte("  - " + n + "\n"))

		p := filepath.Join(d, n)
		f, err := os.Create(p)
		if err != nil {
			return err
		}
		defer f.Close()

		err = w.Write(f)
		if err != nil {
			return err
		}
	}

	return nil
}
