#!/usr/bin/env bash
#
# Smoke-tests a running copy of the site. Pass the base URL as the only
# argument (default http://localhost:8080). Exits non-zero on the first
# failing check. Used by `make test` and the GitHub Actions workflow.
set -euo pipefail

base="${1:-http://localhost:8080}"
site_dir="$(cd "$(dirname "${BASH_SOURCE[0]}")/../site" && pwd)"

fail() { echo "FAIL: $*" >&2; exit 1; }
header() { curl -sSI "$1" | tr -d '\r' | grep -i "^$2:" | cut -d' ' -f2- || true; }
status() { curl -sS -o /dev/null -w '%{http_code}' "$1"; }

[ "$(status "$base/")" = 200 ] || fail "/ is not 200"
curl -sS "$base/" | grep -q "Ceilidh" || fail "/ does not contain Ceilidh"
[ "$(header "$base/" Cache-Control)" = "public, s-maxage=600" ] || fail "/ cache-control"

[ "$(curl -sS "$base/api/healthz")" = '{"success":"ok"}' ] || fail "healthz body"
[ "$(header "$base/api/healthz" Content-Type)" = "application/json" ] || fail "healthz content-type"

[ "$(status "$base/assets/css/theme.css")" = 200 ] || fail "theme.css is not 200"
header "$base/assets/css/theme.css" Cache-Control | grep -q immutable || fail "assets not immutable"

[ "$(status "$base/nope")" = 404 ] || fail "/nope is not 404"
curl -sS "$base/nope" | grep -q "404 - Not Found" || fail "/nope does not serve 404.html"

while read -r path; do
  [ "$(status "$base$path")" = 200 ] || fail "referenced asset $path is not 200"
done < <(grep -o '/assets/[^" ]*' "$site_dir/index.html" | sort -u)

echo "smoke test passed against $base"
