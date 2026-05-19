#!/bin/bash
# Register the custom merge driver for asset files and set up assume-unchanged.
# Run once per developer: npm run setup:dev

SCRIPT_DIR="$(cd "$(dirname "$0")" && pwd)"
AC_ROOT="$(cd "$SCRIPT_DIR/../.." && pwd)"

if [ ! -d "$AC_ROOT/.git" ]; then
    echo "Skipped: .git not found at $AC_ROOT"
    exit 0
fi

echo "Configuring git merge driver for assets..."
git -C "$AC_ROOT" config merge.ours-assets.name "Keep our version of built assets"
git -C "$AC_ROOT" config merge.ours-assets.driver true
echo "  ✓ admin-columns (merge driver)"
echo ""

echo "Setting up assume-unchanged for asset files..."
echo "(Watch-mode builds will no longer appear in your git client)"
files=$(git -C "$AC_ROOT" ls-files 'assets/' 2>/dev/null | grep -E '\.(js|css)$')
count=$(echo "$files" | grep -c .)
if [ "$count" -gt 0 ]; then
    echo "$files" | xargs git -C "$AC_ROOT" update-index --no-skip-worktree
    echo "$files" | xargs git -C "$AC_ROOT" update-index --assume-unchanged
    echo "  ✓ admin-columns ($count asset files — assume-unchanged active)"
fi
echo ""

echo "Installing post-checkout hook..."
echo "(assume-unchanged is automatically restored after branch switches)"
hook="$AC_ROOT/.git/hooks/post-checkout"
cat > "$hook" << EOF
#!/bin/sh
# Re-apply assume-unchanged on assets after branch switch.
cd '$AC_ROOT/src' && npm run assets:lock 2>/dev/null || true
EOF
chmod +x "$hook"
echo "  ✓ admin-columns (post-checkout hook)"
echo ""

echo "To commit assets: npm run assets:release"
echo "To re-lock after commit: npm run assets:lock"
