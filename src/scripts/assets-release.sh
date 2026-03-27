#!/bin/bash
# Build production assets and unlock them for staging in your git client.
# After committing: run npm run assets:lock

SCRIPT_DIR="$(cd "$(dirname "$0")" && pwd)"
AC_ROOT="$(cd "$SCRIPT_DIR/../.." && pwd)"

echo "Running production build..."
cd "$SCRIPT_DIR/.." && npm run build

echo ""
echo "Unlocking assets for staging..."
git -C "$AC_ROOT" ls-files 'assets/' | grep -E '\.(js|css)$' | \
    xargs git -C "$AC_ROOT" update-index --no-assume-unchanged

echo ""
echo "Done. Assets are built and visible in your git client."
echo "Stage and commit them, then run: npm run assets:lock"
