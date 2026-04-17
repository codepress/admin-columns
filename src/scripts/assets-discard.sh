#!/bin/bash
# Discard local asset changes so you can switch branches freely.
# Safe to run — watch mode will rebuild assets after switching.

SCRIPT_DIR="$(cd "$(dirname "$0")" && pwd)"
AC_ROOT="$(cd "$SCRIPT_DIR/../.." && pwd)"

echo "Discarding watch-built assets..."
files=$(git -C "$AC_ROOT" ls-files 'assets/' 2>/dev/null | grep -E '\.(js|css)$')
if [ -z "$files" ]; then
    echo "  No asset files found."
    exit 0
fi

echo "$files" | xargs git -C "$AC_ROOT" update-index --no-skip-worktree --no-assume-unchanged
echo "$files" | xargs git -C "$AC_ROOT" restore
echo "$files" | xargs git -C "$AC_ROOT" update-index --assume-unchanged
echo "  ✓ admin-columns"

echo ""
echo "You can now switch branches freely."
