#!/bin/bash
# Build production assets and unlock them for staging in your git client.
# After committing: run npm run release:lock

SCRIPT_DIR="$(cd "$(dirname "$0")" && pwd)"
AC_ROOT="$(cd "$SCRIPT_DIR/../.." && pwd)"

echo "Running production build..."
if ! ( cd "$SCRIPT_DIR/.." && npm run build ); then
    echo ""
    echo "Build failed. Assets stay locked."
    exit 1
fi

echo ""
echo "Unlocking assets for staging..."
git -C "$AC_ROOT" ls-files 'assets/' | grep -E '\.(js|css)$' | \
    xargs git -C "$AC_ROOT" update-index --no-assume-unchanged

echo ""
echo "Done. Assets are built and visible in your git client."
echo "Stage and commit them, then run: npm run release:lock"
