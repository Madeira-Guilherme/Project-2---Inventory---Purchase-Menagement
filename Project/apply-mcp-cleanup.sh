#!/usr/bin/env bash
# Applies the two file edits I couldn't make directly over Samba.
# Idempotent: safe to re-run. Backups written to *.bak-mcp-cleanup
set -euo pipefail
cd "$(dirname "$0")"

php_in='app/Mcp/Servers/WarehouseServer.php'
auth_in='config/auth.php'

[ -f "$php_in" ] || { echo "missing $php_in"; exit 1; }
[ -f "$auth_in" ] || { echo "missing $auth_in"; exit 1; }

cp -p "$php_in" "$php_in.bak-mcp-cleanup"
cp -p "$auth_in" "$auth_in.bak-mcp-cleanup"

# 1) Remove dead $auth property block from WarehouseServer.
#    Matches the exact 4-line block (with optional trailing blank line).
php -r '
$f = $argv[1];
$src = file_get_contents($f);
$orig = $src;
$pattern = "/\n[ \t]*protected array \\\$auth = \[\s*\n[ \t]*'\''type'\'' => '\''bearer'\'',\s*\n[ \t]*\];\s*\n/";
$src = preg_replace($pattern, "\n", $src, 1);
if ($src === $orig) { fwrite(STDERR, \"WarehouseServer: nothing matched (already cleaned?)\n\"); exit(0); }
file_put_contents($f, $src);
echo "WarehouseServer: dead \$auth removed\n";
' "$php_in"

# 2) Remove malformed mcp guard block from config/auth.php.
php -r '
$f = $argv[1];
$src = file_get_contents($f);
$orig = $src;
$pattern = "/[ \t]*'\''mcp'\'' => \[\s*\n[ \t]*'\''driver'\'' => '\''session'\'',\s*\n[ \t]*'\''provider'\'' => '\''users'\'',\]\s*\n/";
$src = preg_replace($pattern, "", $src, 1);
if ($src === $orig) { fwrite(STDERR, \"config/auth.php: nothing matched (already cleaned?)\n\"); exit(0); }
file_put_contents($f, $src);
echo \"config/auth.php: malformed mcp guard removed\n\";
' "$auth_in"

echo
echo "Diffs:"
diff -u "$php_in.bak-mcp-cleanup" "$php_in" || true
echo
diff -u "$auth_in.bak-mcp-cleanup" "$auth_in" || true

echo
echo "Done. Backups left as *.bak-mcp-cleanup. Now from inside the container:"
echo "  docker compose exec app php artisan optimize:clear"
