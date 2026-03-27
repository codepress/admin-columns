#!/bin/bash
# Lock asset files after committing (re-enables assume-unchanged).
# Watch-mode builds will no longer appear in your git client.

SCRIPT_DIR="$(cd "$(dirname "$0")" && pwd)"
AC_ROOT="$(cd "$SCRIPT_DIR/../.." && pwd)"

files=$(git -C "$AC_ROOT" ls-files 'assets/' 2>/dev/null | grep -E '\.(js|css)$')
count=$(echo "$files" | grep -c .)
if [ "$count" -gt 0 ]; then
    echo "$files" | xargs git -C "$AC_ROOT" update-index --assume-unchanged
    echo "  ✓ admin-columns ($count asset files locked)"
fi

echo ""
echo "Assets locked. Watch-mode builds are hidden from your git client."
