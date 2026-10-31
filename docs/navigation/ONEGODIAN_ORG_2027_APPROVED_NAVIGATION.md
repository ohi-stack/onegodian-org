# OneGodian.org 2027 — Approved Navigation Implementation

**Approval basis:** October 31, 2026 user-supplied seven-item navigation, page/shortcode inventory, and protected-route rules.

**Release state:** GitHub source candidate only. Neither the live WordPress menu, the staging menu, nor the new pages have been published by this change.

## Primary header — exactly seven areas

1. About — /about/
2. Identity — /identity/
3. Learn — /learn/
4. Media — /media/ (featured: /media-center/)
5. Community — /community/
6. Ecosystem — /ecosystem/
7. Support — /support/

The brand/logo returns home; Home is not an eighth item. Login / My Dashboard is a distinct account action that points to /account/ for guests and /dashboard/ for signed-in members.

## Required placement and preservation rules

- OneGodian Time is a **Learn** submenu item; /onegodian-time/ and /onegodian-date-converter/ remain the authoritative managed page paths. Existing /time/ authored content is a supplementary/legacy learning page, not a header heading and is NOT deleted.
- Media is top-level number 4. Media Center is its featured link and is backed by an authored WPBakery source for /media-center/.
- OneGodian Campus is listed under Learn at /onegodian-campus/, with a cross-link under Ecosystem. Its public explanatory source links to the separate 3D application. This does not assert that the application is verified live.
- The menu must not rewrite WordPress post content or alter plugin shortcodes, login rules, registrations, forms, member identity, INO classification or finance authorization.
- Preserve /dashboard/, /profile/, /membership/, /member-id/, /resources/, /certificates/, /account/, /member-messages/ and all other existing Members/Community managed routes. No route rename or shortcode migration is included.
- Candidate destinations that are not published are displayed as non-clickable labels in the supplied navigation shortcode rather than working links to 404 responses.

## Source of truth

- `config/onegodian-2027-navigation.json` — canonical hierarchy, link paths and publication status.
- `wordpress/plugins/onegodian-2027-navigation/menu.json` — exact packaged copy for WordPress.
- `wordpress/plugins/onegodian-2027-navigation/` — accessible menu implementation (desktop mega menu / mobile accordions).
- `config/premium-seven-pages.json` — WPBakery seven-primary-page metadata with supplemental Time and Media Center pages.
- `wordpress/pages/premium-seven/media.vc.txt` — new Media landing source.
- `wordpress/pages/media-center.vc.txt` — new featured Media Center source.
- `wordpress/pages/onegodian-campus.vc.txt` — Campus public explainer (separate from interactive 3D application).
- `wordpress/pages/premium-seven/learn.vc.txt` — added links to Time, date conversion and Campus.
- `wordpress/plugins/onegodian-premium-vc-pages/` — shared styles, with Time still supported as supplemental content.

## Staging installation and activation (human-approved)

1. Back up WordPress database and current theme/menu settings, save the existing primary menu export or screenshots, and record the installed versions of the OneGodian Members/Community plugins.
2. Confirm published pages and exact canonical slugs, particularly /media/, /media-center/, /onegodian-campus/. Use the documented VC source to create or update **only** approved page content; do not overwrite existing manually authored pages automatically.
3. Install **OneGodian Premium VC Pages** staging candidate and **OneGodian 2027 Navigation** staging candidate. Place `[onegodian_2027_navigation]` in the **header builder/shortcode-capable theme area**; do not place a duplicate header within a page. If the theme does not support header shortcodes, map the same JSON hierarchy into the existing Appearance → Menus setup and use the theme's own mega-menu rendering.
4. Remove or unassign the old Time top-level menu entry **only after** verifying /onegodian-time/ and the date converter appear under Learn. Keep the /time/ page available as supplementary content if already published.
5. Ensure the Media fourth position, featured Media Center URL, Campus placements, protected member routes, and separate Login/My Dashboard header action are correct. Verify published candidate pages and all links; don't activate unavailable destinations as working links.
6. Run keyboard/screen-reader checks, Tab/Shift+Tab/Enter/Space/Escape behavior, desktop 1440px, tablet 768px and iPhone-like 390px layouts, touch accordion interactions, reduced-motion and content focus order.
7. Verify logged-out vs logged-in navigation, capability enforcement in destination plugin routes, shortcode rendering and no change to member data. Do not expose member IDs, messages or applications to unauthenticated visitors.
8. Execute PHP lint, Python source contract tests, JavaScript syntax check and WordPress staging integration. Capture deployed plugin SHA/ZIP checksum, screenshots and acceptance log.
9. Publish to production **only after** staging acceptance and human approval. Recheck URLs and served plugin revisions. Roll back via prior menu assignment plus previous tested plugin ZIP and preserved WordPress database backup.

## Pending verification

The navigation includes proposed destinations such as /ecosystem-map/, /onegodian-city/, /technology-platforms/, /development-opportunities/, /business-commerce/ and /publications/. Those paths are **not** assertions that such WordPress pages currently exist. They must be created with complete content or bound to the verified existing canonical URLs before links become active.

## Security and compliance

OneGodian spiritual membership and INO tribal membership remain separate. Being able to see a navigation label never grants access to a protected member endpoint. ODeFi and external business platforms retain their own authorities.

**Definition of done:** Build → Integration → PHP/JS/contract tests → WordPress staging → published URL and mobile verification → authorized production deployment → documented rollback. A GitHub merge is not evidence of live publication.
