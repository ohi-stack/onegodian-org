#!/usr/bin/env python3
"""Source-contract tests for OneGodian.org 2027 navigation; no live deployment claims."""
import json
import re
from pathlib import Path
root = Path(__file__).resolve().parents[1]
canonical = json.loads((root / "config/onegodian-2027-navigation.json").read_text())
packaged = json.loads((root / "wordpress/plugins/onegodian-2027-navigation/menu.json").read_text())
assert canonical == packaged, "Packaged navigation must equal canonical manifest."
names = [item["name"] for item in canonical["items"]]
assert names == ["About", "Identity", "Learn", "Media", "Community", "Ecosystem", "Support"], names
assert names[3] == "Media" and "Time" not in names
assert len(set(names)) == 7
for item in canonical["items"]:
    assert item["path"].startswith("/") and item["path"].endswith("/")
    assert item["columns"]
    for col in item["columns"]:
        assert col["links"]
        for link in col["links"]:
            assert link["path"].startswith(("/", "https://")), link
            assert not link["path"].startswith("http://"), link
            if link["path"].startswith("/"):
                assert re.fullmatch(r"/[a-zA-Z0-9/_-]*/", link["path"]), link
links = {item["name"]: [link for col in item["columns"] for link in col["links"]] for item in canonical["items"]}
def destinations(name):
    return {x["path"] for x in links[name]}
assert "/onegodian-time/" in destinations("Learn")
assert "/onegodian-date-converter/" in destinations("Learn")
assert "/onegodian-time/" not in destinations("Media")
assert "/onegodian-campus/" in destinations("Learn")
assert "/onegodian-campus/" in destinations("Ecosystem")
assert "/media-center/" in destinations("Media")
assert any(x.get("featured") and x["path"] == "/media-center/" for x in links["Media"])
assert "/dashboard/" in destinations("Community")
assert "/member-messages/" in destinations("Community")
assert "/membership-application/" in destinations("Support")
assert canonical["header_actions"][0]["guest"] == "/account/"
assert canonical["header_actions"][0]["authenticated"] == "/dashboard/"
for path in ("media", "media-center", "onegodian-campus"):
    source = root / ("wordpress/pages/premium-seven/media.vc.txt" if path == "media" else f"wordpress/pages/{path}.vc.txt")
    assert source.is_file()
    text = source.read_text()
    assert len(text) > 4500
    assert text.count("[vc_row ") == text.count("[/vc_row]")
    assert text.count("[vc_column_text]") == text.count("[/vc_column_text]")
    assert text.count("<h1>") == 1
php = (root / "wordpress/plugins/onegodian-2027-navigation/onegodian-2027-navigation.php").read_text()
assert "onegodian_2027_navigation" in php
assert "get_page_by_path" in php and "is_user_logged_in" in php
assert "get_permalink" in php
assert "edit_post" not in php and "wp_insert_post" not in php
css = (root / "wordpress/plugins/onegodian-2027-navigation/assets/navigation.css").read_text()
js = (root / "wordpress/plugins/onegodian-2027-navigation/assets/navigation.js").read_text()
assert "@media(max-width:900px)" in css
assert "prefers-reduced-motion" in css
assert "Escape" in js
print("PASS: seven navigation items, protected URLs, shared desktop/mobile tree, media and time boundaries")
