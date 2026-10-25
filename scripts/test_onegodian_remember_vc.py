#!/usr/bin/env python3
"""Static acceptance tests for OneGodian: Remember WPBakery 2027 source.

A passing result proves source structure only. It does not establish working
WordPress attachment IDs, third-party video, plugin compatibility, or staging.
"""
import re
from pathlib import Path

ROOT = Path(__file__).resolve().parents[1]
PAGE = ROOT / "wordpress/pages/onegodian-remember.vc.txt"
CSS = ROOT / "wordpress/plugins/onegodian-premium-vc-pages/assets/onegodian-remember.css"
PLUGIN = ROOT / "wordpress/plugins/onegodian-premium-vc-pages/onegodian-premium-vc-pages.php"

vc = PAGE.read_text(encoding="utf-8")
css = CSS.read_text(encoding="utf-8")
php = PLUGIN.read_text(encoding="utf-8")

# Verify WPBakery shortcode nest order, including vc_row_inner / vc_column_inner.
tokens = re.finditer(
    r"\[(/?)(vc_row(?:_inner)?|vc_column(?:_inner)?|vc_column_text)(?=[\s\]])[^\]]*\]",
    vc
)
stack = []
for m in tokens:
    closing, tag = m.group(1, 2)
    if closing:
        assert stack and stack[-1] == tag, ("misnested VC shortcode", tag, stack)
        stack.pop()
    else:
        stack.append(tag)
assert not stack, ("unclosed VC shortcodes", stack)
assert vc.count('[vc_row full_width="stretch_row_content"') == 8
assert vc.count("[vc_row_inner") == 2

# Every HTML wrapper stays inside its own [vc_column_text] block.
blocks = re.findall(r"\[vc_column_text\]([\s\S]*?)\[/vc_column_text\]", vc)
assert len(blocks) >= 11
for i, block in enumerate(blocks):
    html_stack = []
    for tag in re.finditer(r"<(/?)(div|section)\b[^>]*>", block):
        if tag.group(1):
            assert html_stack and html_stack[-1] == tag.group(2), (i, tag.group(2), html_stack)
            html_stack.pop()
        else:
            html_stack.append(tag.group(2))
    assert not html_stack, ("HTML wrapper crosses text blocks", i, html_stack)

assert len(re.findall(r"<h1\b", vc)) == 1
assert len(re.findall(r"<h2\b", vc)) >= 7
assert "One Gregory Onegodian™" in vc
assert "Related to, resembling, and belonging to One God" in vc
assert 'el_id="onegodian-remember"' in vc
assert 'el_id="remember-actions"' in vc
assert 'parallax_image="3906"' in vc
for media_id in ("4784", "4844", "5009"):
    assert f'[vc_single_image image="{media_id}"' in vc, media_id
assert '[vc_video link="https://www.youtube.com/watch?v=NqwcX2yqOKs"' in vc
assert '[la_show_posts ' in vc
for path in ("/contributors/", "/what-is-onegodian/", "/contact/", "https://onegodian.com/remember/", "https://onegodian.org/product/contribution/"):
    assert path in vc, path
for bad in ("app.onegodian.com", "capital.onegodian.com"):
    assert bad not in vc
assert "<script" not in vc.lower()

allowed_hosts = ("https://onegodian.org/", "https://onegodian.com/", "https://www.youtube.com/")
for href in re.findall(r'href="([^"]+)"', vc):
    assert href.startswith("/") or href.startswith(allowed_hosts), ("link not allowed", href)

for required in (".ogrm-hero", ".ogrm-story-grid", ".ogrm-section-video", "prefers-reduced-motion", "@media(max-width:640px)"):
    assert required in css, required
assert "FA11C45F-C9B7-493C-BAAA-B291E8DCF376.png" in css
assert "Version: 1.2.0" in php
assert "assets/onegodian-remember.css" in php
assert "ogrm-hero" in php
assert "get_post_field('post_content'" in php
print("PASS: Remember WPBakery markup, media contract, outbound link policy, scoped CSS and plugin")
