#!/usr/bin/env bash
#
# Regenerates the third-party stubs used by PHPStan.
#
# Only needed when one of the target plugins is updated; the generated files are
# committed, so analysis itself never requires the plugins to be present.
#
# Usage:
#   ./generate-stubs.sh
#   AC_PLUGINS_DIR=/path/to/wp-content/plugins ./generate-stubs.sh

set -euo pipefail

DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PLUGINS="${AC_PLUGINS_DIR:-/Users/tobias/Sites/acp/app/public/wp-content/plugins}"
OUT="$DIR/stubs"
GEN="$DIR/vendor/bin/generate-stubs"

if [ ! -x "$GEN" ]; then
    echo "generate-stubs not found. Run: composer phpstan:install" >&2
    exit 1
fi

if [ ! -d "$PLUGINS" ]; then
    echo "Plugins directory not found: $PLUGINS" >&2
    echo "Set AC_PLUGINS_DIR to your wp-content/plugins path." >&2
    exit 1
fi

mkdir -p "$OUT"

generate() {
    local name="$1"
    shift
    local found=()
    for src in "$@"; do
        if [ -e "$src" ]; then
            found+=("$src")
        else
            echo "  warn $name: missing source $src" >&2
        fi
    done
    if [ "${#found[@]}" -eq 0 ]; then
        echo "  skip $name: no sources found" >&2
        return 0
    fi
    echo "  $name (${#found[@]} source(s))"
    "$GEN" --force --functions --classes --interfaces --traits --constants \
        --out="$OUT/$name.stub.php" "${found[@]}" >/dev/null
}

echo "Generating stubs into $OUT"

generate pods \
    "$PLUGINS/pods/src/Pods/Whatsit" \
    "$PLUGINS/pods/classes/fields/pick.php" \
    "$PLUGINS/pods/classes/PodsForm.php"

generate buddypress \
    "$PLUGINS/buddypress/bp-xprofile/classes" \
    "$PLUGINS/buddypress/bp-groups/classes" \
    "$PLUGINS/buddypress/bp-activity/classes"

generate jetengine \
    "$PLUGINS/jet-engine/includes/components/relations" \
    "$PLUGINS/jet-engine/includes/components/glossaries" \
    "$PLUGINS/jet-engine/includes/components/meta-boxes"

generate metabox \
    "$PLUGINS/meta-box-aio/vendor/meta-box/mb-relationships" \
    "$PLUGINS/meta-box-aio/vendor/meta-box/mb-custom-table" \
    "$PLUGINS/meta-box/inc/fields"

generate toolset \
    "$PLUGINS/types/vendor/toolset/toolset-common/inc/m2m" \
    "$PLUGINS/types/vendor/toolset/toolset-common/inc/autoloaded/field/group"

generate mla \
    "$PLUGINS/media-library-assistant/includes/class-mla-data.php" \
    "$PLUGINS/media-library-assistant/includes/class-mla-core.php" \
    "$PLUGINS/media-library-assistant/includes/class-mla-list-table.php"

echo "Done."
