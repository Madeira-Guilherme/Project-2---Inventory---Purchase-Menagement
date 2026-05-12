#!/usr/bin/env bash
# MCP auth diagnostic - run from inside your app container (or wherever `php artisan` runs)
# Usage:
#   bash diagnose-mcp.sh > diagnose-mcp.out 2>&1
# Then send me diagnose-mcp.out

set +e

OUT() { echo; echo "==== $* ===="; }

cd "$(dirname "$0")" || exit 1

OUT "PHP / Laravel versions"
php -v 2>&1 | head -1
php artisan --version 2>&1

OUT "Clear caches (so we test fresh state, not cached config/routes)"
php artisan optimize:clear 2>&1

OUT "Confirm routes/ai.php is loaded and middleware on POST /mcp/demo"
php artisan route:list --path=mcp 2>&1

OUT "Confirm sanctum guard is registered"
php artisan tinker --execute="dump(config('auth.guards'));" 2>&1

OUT "Confirm personal_access_tokens table exists"
php artisan tinker --execute="dump(\Illuminate\Support\Facades\Schema::hasTable('personal_access_tokens'));" 2>&1

OUT "Confirm at least one user exists, then mint a test bearer token"
php artisan tinker --execute="
\$u = \App\Models\User::first();
if (!\$u) { echo 'NO_USER'; exit; }
echo 'USER_ID='.\$u->id.PHP_EOL;
echo 'USER_HAS_TRAIT='.(method_exists(\$u,'createToken') ? 'yes' : 'no').PHP_EOL;
\$t = \$u->createToken('mcp-diagnose')->plainTextToken;
echo 'TOKEN='.\$t.PHP_EOL;
" 2>&1 | tee /tmp/mcp-diag-token.txt

TOKEN=$(grep '^TOKEN=' /tmp/mcp-diag-token.txt | tail -1 | cut -d= -f2-)

OUT "BASE URL detection"
APP_URL=$(php -r "echo trim(file_get_contents('.env'));" | grep -E '^APP_URL=' | head -1 | cut -d= -f2-)
echo "APP_URL from .env: $APP_URL"
BASE="${APP_URL:-http://localhost}"
echo "Using BASE=$BASE"

OUT "Test POST /mcp/demo WITHOUT token (expect 401 + WWW-Authenticate Bearer)"
curl -sS -i -X POST "$BASE/mcp/demo" \
  -H 'Accept: application/json, text/event-stream' \
  -H 'Content-Type: application/json' \
  --data '{"jsonrpc":"2.0","id":1,"method":"initialize","params":{"protocolVersion":"2025-06-18","capabilities":{},"clientInfo":{"name":"diag","version":"1"}}}' \
  2>&1 | head -40

OUT "Test POST /mcp/demo WITH bearer token (expect 200 JSON-RPC initialize result)"
if [ -n "$TOKEN" ]; then
  curl -sS -i -X POST "$BASE/mcp/demo" \
    -H "Authorization: Bearer $TOKEN" \
    -H 'Accept: application/json, text/event-stream' \
    -H 'Content-Type: application/json' \
    --data '{"jsonrpc":"2.0","id":1,"method":"initialize","params":{"protocolVersion":"2025-06-18","capabilities":{},"clientInfo":{"name":"diag","version":"1"}}}' \
    2>&1 | head -60
else
  echo "Skipped: no token was minted above."
fi

OUT "Tail of laravel.log (last 50 lines) for any auth errors"
tail -50 storage/logs/laravel.log 2>&1

echo
echo "==== DONE — send diagnose-mcp.out back to me ===="
