#!/usr/bin/env bash
# Lint every PHP file and smoke-render each site's landing page.
set -euo pipefail
cd "$(dirname "$0")/src"
PHP="php -d short_open_tag=1 -d display_errors=stderr"

for f in $(find . -name '*.php'); do $PHP -l "$f" >/dev/null || exit 1; done

render() { # render <site> <script> <query-param> <value>
  (cd "$1" && $PHP -r '$_SERVER = ["SCRIPT_NAME"=>$argv[1],"HTTP_HOST"=>"localhost","REQUEST_URI"=>"/","SERVER_PORT"=>"80"] + $_SERVER;
                       $_GET = $argv[3] === "" ? [] : [$argv[2] => $argv[3]]; include basename($argv[1]);' -- "$2" "$3" "$4" 2>/dev/null)
}
for site in ping4 ping6 dumpers; do
  param=host; [ $site = dumpers ] && param=bufferData
  landing=$(render $site /index.php $param "")
  grep -q '<title>' <<<"$landing" || { echo "$site: no <title>"; exit 1; }
  tld=network; [ $site = dumpers ] && tld=xyz
  grep -q "rel=\"canonical\" href=\"https://www.$site.$tld/\"" <<<"$landing" || { echo "$site: wrong canonical"; exit 1; }
  grep -q 'noindex' <<<"$landing" && { echo "$site: landing page is noindex"; exit 1; }
  render $site /sitemap.php x "" | python3 -c 'import sys,xml.dom.minidom; xml.dom.minidom.parseString(sys.stdin.read())' || { echo "$site: bad sitemap"; exit 1; }
done
echo ok
