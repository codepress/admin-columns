#!/usr/bin/env bash
set -euo pipefail

cd "$(dirname "$0")"

section() {
    printf '\n\033[1;36m==> %s\033[0m\n' "$1"
}

section "Checking prerequisites"
missing=()
for cmd in composer npm php; do
    if ! command -v "$cmd" >/dev/null 2>&1; then
        missing+=("$cmd")
    fi
done
if (( ${#missing[@]} > 0 )); then
    echo "error: missing required command(s): ${missing[*]}" >&2
    exit 1
fi

section "Installing PHP dependencies (triggers prefixer)"
composer install --no-interaction

section "Installing frontend dependencies"
( cd src && npm install )

section "Running one-time frontend dev setup"
( cd src && npm run setup:dev )

section "Done"
cat <<'EOF'
Admin Columns is installed.

Next step: activate the plugin in WordPress -> Plugins, then configure
columns under Settings -> Admin Columns.
EOF
