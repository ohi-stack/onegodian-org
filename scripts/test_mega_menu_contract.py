#!/usr/bin/env python3
"""Validate the OneGodian.org navigation source without needing WordPress."""
import json
import re
from pathlib import Path

ROOT = Path(__file__).resolve().parents[1]
manifest = json.loads((ROOT / "config/onegodian-org-mega-menu.json").read_text())
areas = ["ABOUT", "IDENTITY", "LEARN", "COMMUNITY", "ECOSYSTEM", "SUPPORT", "MEDIA CENTER"]
assert manifest["top_level"] == areas, "Seven-area OneGodian.org navigation drift"
entries = manifest["entries"]
assert len(entries) == 46, f"Expected 46 supplied pages, found {len(entries)}"
assert len({e["slug"] for e in entries}) == len(entries), "Duplicate slugs"

for entry in entries:
    assert entry["area"] in areas and entry["group"] and entry["label"], entry
    assert re.fullmatch(r"/[a-z0-9-]+/", entry["slug"]), entry
    assert re.fullmatch(r"\[onegodian_[a-z0-9_]+\]", entry["shortcode"]), entry
    assert isinstance(entry["login_required"], bool), entry

by_slug = {e["slug"]: e for e in entries}
assert by_slug["/membership/"]["shortcode"] == "[onegodian_membership]"
assert by_slug["/dashboard/"]["shortcode"] == "[onegodian_dashboard]"
assert by_slug["/member-id/"]["shortcode"] == "[onegodian_member_id]"
assert by_slug["/onegodian-origins/"]["shortcode"] == "[onegodian_origins]"
assert by_slug["/1926-2026-2126/"]["shortcode"] == "[onegodian_legacy_eras]"
assert by_slug["/contributor-intake/"]["shortcode"] == "[onegodian_contributor_intake_form]"

assert not {e["slug"] for e in entries if e["reported_status"] == "Missing"}
assert {e["slug"] for e in entries if e["reported_status"] == "Needs Review"} == {"/resources/", "/certificates/"}
assert by_slug["/members/"]["reported_status"] == "Managed / Working"
assert by_slug["/groups/"]["reported_status"] == "Managed / Working"
assert not by_slug["/contributor-intake/"]["login_required"]
assert by_slug["/onegodian-time/"]["area"] == "LEARN"
assert by_slug["/onegodian-date-converter/"]["area"] == "LEARN"
assert all(by_slug[slug]["area"] == "MEDIA CENTER" for slug in ("/digital-museum/", "/media-archive/", "/creator-network/"))

expected_counts = {"ABOUT": 9, "IDENTITY": 6, "LEARN": 5, "COMMUNITY": 14, "ECOSYSTEM": 2, "SUPPORT": 7, "MEDIA CENTER": 3}
for area, expected in expected_counts.items():
    actual = sum(e["area"] == area for e in entries)
    assert actual == expected, (area, actual, expected)

assert sum(e["login_required"] for e in entries) == 19
print("PASS: 46 canonical routes, seven mega-menu areas, 19 protected, two review gates, Time under Learn and Media Center seventh.")
