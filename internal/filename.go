package internal

import "strings"

func sanitizeFilename(n string) string {
	n = strings.ToLower(n)

	return strings.Map(func(r rune) rune {
		if (r >= 'a' && r <= 'z') ||
			(r >= '0' && r <= '9') ||
			r == '-' ||
			r == '_' ||
			r == '.' {
			return r
		}
		return '_'
	}, n)
}
