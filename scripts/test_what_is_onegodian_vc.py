#!/usr/bin/env python3
"""Source-only checks for the 2027 OneGodian definition WPBakery page.

Passing this script does not prove that WordPress has imported or rendered the page.
"""
import re
from pathlib import Path

ROOT = Path(__file__).resolve().parents[1]
PAGE = ROOT / "wordpress/pages/what-is-onegodian.vc.txt"
CSS = ROOT / "wordpress/plugins/onegodian-premium-vc-pages/assets/what-is-onegodian.css"
PLUGIN = ROOT / "wordpress/plugins/onegodian-premium-vc-pages/onegodian-premium-vc-pages.php"

page = PAGE.read_text(encoding="utf-8")
css = CSS.read_text(encoding="utf-8")
php = PLUGIN.read_text(encoding="utf-8")

for name in ("vc_row", "vc_column", "vc_column_text"):
    opens = len(re.findall(r"\[" + name + r"(?:\s|\])", page))
    closes = page.count("[/" + name + "]")
    assert opens == closes, (name, opens, closes)

assert page.count("[vc_row ") == 8
assert len(re.findall(r"<h1(?:\s[^>]*)?>", page)) == 1
assert len(re.findall(r"<h2(?:\s[^>]*)?>", page)) == 7
assert 'el_id="what-is-onegodian"' in page
assert 'parallax_image="3906"' in page
assert "Related to, resembling, and belonging to One God." in page
assert "why did nobody call us OneGodian?" in page
assert "Ephesians 4:4–6" in page
assert "Mark 10:17–18" in page
assert "ONEGODIAN, LLC" in page
assert "Indigenous Nation of Onegodia" in page

assert "<script" not in page.lower()
assert "<iframe" not in page.lower()
for href in re.findall(r'href="([^"]+)"', page):
    assert href.startswith("/") and ".." not in href, href

for item in (".ogwhy-hero", ".ogwhy-grid-three", "prefers-reduced-motion", "@media(max-width:600px)"):
    assert item in css, item
assert "is_page('what-is-onegodian')" in php
assert "assets/what-is-onegodian.css" in php

print("PASS: What Is OneGodian 2027 WPBakery source and plugin stylesheet")
