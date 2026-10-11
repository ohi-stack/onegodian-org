#!/usr/bin/env python3
"""Validate the human and search-engine OneGodian.org sitemaps.

These are static repository checks, NOT a live-site / SEO indexability audit.
"""
from pathlib import Path
from xml.etree import ElementTree as ET
import json
import re

root = Path(__file__).resolve().parents[1]
manifest = json.loads((root / "config/onegodian-org-mega-menu.json").read_text())
entries = manifest["entries"]
assert len(entries) == 43
assert len({entry["slug"] for entry in entries}) == len(entries)
assert sum(entry["login_required"] for entry in entries) == 19
assert sum(entry["reported_status"] == "Missing" for entry in entries) == 2
areas = ["ABOUT", "IDENTITY", "LEARN", "TIME", "COMMUNITY", "ECOSYSTEM", "SUPPORT"]
assert manifest["top_level"] == areas

human = (root / "docs/navigation/ONEGODIAN_ORG_SITE_MAP_2026-10-11.md").read_text()
html = (root / "wordpress/pages/site-map.vc.txt").read_text()
xml_source = (root / "sitemaps/onegodian-managed-public-candidates.xml").read_text()
sitemap = ET.fromstring(xml_source)
ns = "{http://www.sitemaps.org/schemas/sitemap/0.9}"
assert sitemap.tag == ns + "urlset"
seo_urls = [el.text for el in sitemap.findall(f"{ns}url/{ns}loc")]
expected_seo = [
    "https://onegodian.org" + e["slug"]
    for e in entries
    if not e["login_required"]
    and e["reported_status"] == "Managed / Working"
    and e["slug"] != "/my-account/"
]
assert len(seo_urls) == 22 and seo_urls == expected_seo
assert len(set(seo_urls)) == len(seo_urls)
assert "<lastmod>" not in xml_source
assert "[onegodian_" not in xml_source

assert html.startswith('[vc_row full_width="stretch_row_content"')
assert html.strip().endswith("[/vc_column_text][/vc_column][/vc_row]")
assert html.count('<section class="ogs-area"') == 7
assert html.count('</section>') == 7
html_urls = re.findall(r'<li><a href="(https://onegodian.org/[^"]+)">', html)
expected_html = [
    "https://onegodian.org" + e["slug"]
    for e in entries if e["reported_status"] != "Missing"
]
assert len(html_urls) == 41 and html_urls == expected_html
for e in entries:
    assert f"`{e['slug']}`" in human, f"Missing markdown route: {e['slug']}"
    if e["reported_status"] == "Missing":
        assert "https://onegodian.org" + e["slug"] not in html_urls
        assert "https://onegodian.org" + e["slug"] not in seo_urls
    if e["login_required"]:
        assert "https://onegodian.org" + e["slug"] not in seo_urls, e["slug"]
for area in areas:
    assert f"## {area}" in human
    assert f'<h2 id="ogs-{area.lower()}">{area}</h2>' in html

assert "https://onegodian.org/my-account/" not in seo_urls
assert "https://onegodian.org/contributor-intake/" not in seo_urls

print("PASS: 43-page sitemap; 41 human-facing links; 22 public SEO candidates; 19 protected routes excluded from XML.")
