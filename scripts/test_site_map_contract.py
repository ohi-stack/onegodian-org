#!/usr/bin/env python3
"""Verify the 2026-10-31 OneGodian seven-area site map sources.

Source contract only; does not establish live URLs or eligibility for indexing.
"""
from pathlib import Path
from xml.etree import ElementTree as ET
import json
import re

root = Path(__file__).resolve().parents[1]
manifest = json.loads((root / "config/onegodian-org-mega-menu.json").read_text())
entries = manifest["entries"]
areas = ["ABOUT", "IDENTITY", "LEARN", "COMMUNITY", "ECOSYSTEM", "SUPPORT", "MEDIA CENTER"]
assert manifest["top_level"] == areas
assert len(entries) == 46 and len({e["slug"] for e in entries}) == 46
assert sum(e["login_required"] for e in entries) == 19
assert {e["slug"] for e in entries if e["reported_status"] == "Needs Review"} == {"/resources/", "/certificates/"}
assert not any(e["reported_status"] == "Missing" for e in entries)
by_slug = {e["slug"]: e for e in entries}
assert by_slug["/onegodian-time/"]["area"] == "LEARN"
assert by_slug["/onegodian-date-converter/"]["area"] == "LEARN"
assert by_slug["/media-archive/"]["area"] == "MEDIA CENTER"

human = (root / "docs/navigation/ONEGODIAN_ORG_SITE_MAP_2026-10-11.md").read_text()
html = (root / "wordpress/pages/site-map.vc.txt").read_text()
xml_source = (root / "sitemaps/onegodian-managed-public-candidates.xml").read_text()
sitemap = ET.fromstring(xml_source)
ns = "{http://www.sitemaps.org/schemas/sitemap/0.9}"
assert sitemap.tag == ns + "urlset"
seo_urls = [el.text for el in sitemap.findall(f"{ns}url/{ns}loc")]
excluded = {"/account/", "/membership-application/", "/contributor-intake/"}
expected_seo = [
    "https://onegodian.org" + e["slug"]
    for e in entries if not e["login_required"]
    and e["reported_status"] == "Managed / Working"
    and e["slug"] not in excluded
]
assert len(seo_urls) == 24 and seo_urls == expected_seo
assert len(seo_urls) == len(set(seo_urls))
assert "<lastmod>" not in xml_source and "[onegodian_" not in xml_source

assert html.startswith('[vc_row full_width="stretch_row_content"')
assert html.strip().endswith("[/vc_column_text][/vc_column][/vc_row]")
assert html.count('<section class="ogs-area"') == 7
assert html.count('</section>') == 7
html_urls = re.findall(r'<li><a href="(https://onegodian.org/[^"]+)">', html)
expected_html = {"https://onegodian.org" + e["slug"] for e in entries}
assert len(html_urls) == 46 and set(html_urls) == expected_html
for e in entries:
    assert f"`{e['slug']}`" in human, f"Missing human sitemap route: {e['slug']}"
    if e["login_required"] or e["slug"] in excluded:
        assert "https://onegodian.org" + e["slug"] not in seo_urls, e["slug"]
for area in areas:
    assert f"## {area}" in human
    assert f'<h2 id="ogs-{area.lower().replace(" ", "-")}">{area}</h2>' in html
assert '<h2 id="ogs-time">TIME</h2>' not in html
print("PASS: 46 sitemap links, seven sections, 24 public SEO candidates, 19 protected routes excluded.")
