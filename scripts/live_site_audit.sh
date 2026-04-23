#!/usr/bin/env bash
set -euo pipefail

BASE_URL="${1:-https://onegodian.org}"
TIMEOUT="${TIMEOUT:-20}"

echo "== OneGodian live-site quick audit =="
echo "Base URL: ${BASE_URL}"

echo
printf '## Response + timing\n'
for path in / /store/ /membership/ /contact/; do
  url="${BASE_URL%/}${path}"
  curl -sS -L --max-time "${TIMEOUT}" -o /dev/null \
    -w "${url} | code=%{http_code} time=%{time_total}s ttfb=%{time_starttransfer}s bytes=%{size_download}\n" \
    "$url"
done

echo
printf '## Security headers\n'
headers=$(curl -sSI -L --max-time "${TIMEOUT}" "${BASE_URL}")
for h in \
  strict-transport-security \
  content-security-policy \
  x-frame-options \
  x-content-type-options \
  referrer-policy \
  permissions-policy \
  cross-origin-opener-policy \
  cross-origin-resource-policy; do
  if echo "$headers" | tr '[:upper:]' '[:lower:]' | rg -q "^${h}:"; then
    echo "$headers" | rg -i "^${h}:"
  else
    echo "${h}: MISSING"
  fi
done

echo
printf '## WordPress markers\n'
curl -sS -L --max-time "${TIMEOUT}" "${BASE_URL}" \
  | rg -n "wp-content/themes|wp-content/plugins|generator|woocommerce|rank math|jetpack" \
  | head -n 40 || true

echo
printf '## Home internal-link status (first 60 unique links)\n'
python - <<'PY'
from urllib.request import urlopen, Request
from urllib.parse import urljoin, urlparse
from html.parser import HTMLParser
import ssl, time
base='https://onegodian.org/'
ctx=ssl.create_default_context()
html=urlopen(Request(base,headers={'User-Agent':'Mozilla/5.0'}),timeout=20,context=ctx).read().decode('utf-8','ignore')
class P(HTMLParser):
    def __init__(self):
        super().__init__(); self.links=[]
    def handle_starttag(self, tag, attrs):
        if tag=='a':
            d=dict(attrs)
            if 'href' in d: self.links.append(d['href'])
p=P(); p.feed(html)
seen=[]
for href in p.links:
    h=href.strip()
    if h.startswith(('#','mailto:','tel:','javascript:')): continue
    u=urljoin(base,h)
    if u not in seen: seen.append(u)
internal=[u for u in seen if urlparse(u).netloc.endswith('onegodian.org')]
for u in internal[:60]:
    t=time.time()
    try:
        r=urlopen(Request(u,headers={'User-Agent':'Mozilla/5.0'}),timeout=20,context=ctx)
        r.read(100)
        print(f"{r.getcode()}\t{(time.time()-t)*1000:.0f}ms\t{u}")
    except Exception as e:
        print(f"ERR\t{(time.time()-t)*1000:.0f}ms\t{u}\t{e}")
PY

echo
printf '## Public user enumeration check\n'
curl -sS --max-time "${TIMEOUT}" "${BASE_URL%/}/wp-json/wp/v2/users?per_page=5" | head -c 350
printf '\n\nDone.\n'
