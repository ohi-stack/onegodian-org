#!/usr/bin/env python3
"""Source-level acceptance checks for premium OneGodian.org WPBakery main pages."""
import json
import re
from pathlib import Path
root = Path(__file__).resolve().parents[1]
manifest = json.loads((root / "config/premium-seven-pages.json").read_text())
slugs = ["about", "identity", "learn", "time", "community", "ecosystem", "support"]
assert [p["slug"] for p in manifest["pages"]] == slugs
assert manifest["navigation"] == [slug.upper() for slug in slugs]
for p in manifest["pages"]:
    file = root / p["vc_source"]
    text = file.read_text()
    assert len(text) > 4500, file
    for name in ("vc_row", "vc_column", "vc_column_text"):
        opens = len(re.findall(r"\\[" + name + r"(?:\\s|\\])", text))
        closes = text.count("[/" + name + "]")
        assert opens == closes, (file, name, opens, closes)
    assert len(re.findall(r"<h1(?:\\s[^>]*)?>", text)) == 1, file
    assert text.count("<h2 ") >= 4, file
    assert text.count('class="og7-card"') == 4, file
    assert text.count('class="og7-step"') == 3, file
    assert text.count('class="og7-btn og7-btn-gold"') == 2, file
    assert text.count('class="og7-links"') == 1, file
    assert p["h1"] in text, file
    assert "og7--" + p["slug"] in text, file
    assert "OneGodian™ • " + p["navigation"] in text, file
    assert "app.onegodian.com" not in text, file
    assert "capital.onegodian.com" not in text, file
    assert "<script" not in text.lower(), file
    for href in re.findall(r'href="([^"]+)"', text):
        assert href.startswith(("/", "#", "https://")), (file, href)
    print("PASS:", p["slug"], len(text), "bytes")
css = (root / "wordpress/plugins/onegodian-premium-vc-pages/assets/premium-seven.css").read_text()
assert "prefers-reduced-motion" in css and "max-width:760px" in css
php = (root / "wordpress/plugins/onegodian-premium-vc-pages/onegodian-premium-vc-pages.php").read_text()
for slug in slugs:
    assert "'" + slug + "'" in php
print("PASS: seven WPBakery source contracts and scoped plugin assets")
