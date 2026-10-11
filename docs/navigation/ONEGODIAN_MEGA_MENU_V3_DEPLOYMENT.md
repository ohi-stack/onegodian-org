# OneGodian Mega Menu v3 — Deployment and Rollback

**Version:** 3.0.0  
**Foundation:** OneGodian.org October 11, 2026 seven-area, 43-page managed navigation inventory  
**Code:** wordpress/plugins/onegodian-mega-menu-v3/  
**Status:** Repository staging candidate; live WordPress installation and browser acceptance remain pending.

## Design contract

The seven primary navigation areas are **ABOUT · IDENTITY · LEARN · TIME · COMMUNITY · ECOSYSTEM · SUPPORT**. Each area opens a mega panel with **up to six columns per desktop row** and **up to seven links in each column**. If imported legacy navigation exceeds six columns, columns wrap rather than disappearing. Groups exceeding seven links split into continuation columns.

Visual behavior includes a dark obsidian backdrop, gold and violet gradient lighting, translucent cards, animated gold sweeps, subtle hover elevation, and a reduced-motion preference. Desktop behavior includes pointer hover with a small delayed close, click, keyboard focus, Escape to dismiss, and left/right/Home/End on root triggers. Mobile uses a touch toggle, independent accordions, one-column phone panels and two-column tablet panels.

The plugin uses the existing WordPress menu ID and retains unrelated roots plus nested utility links. It is not a replacement for the whole WordPress site, account permissions, or SEO system.

## Installation

1. **Back up** the WordPress database and export menus. Identify the real menu ID assigned to the October 11 WordPress header, which may be named **OneGodian Mega Menu v2**. Review on hosted staging first.
2. From the repository root, package the WordPress plugin:

    cd wordpress/plugins
    zip -r onegodian-mega-menu-v3.zip onegodian-mega-menu-v3

3. Install and activate the archive on staging. Under **Appearance → OneGodian Mega Menu v3**, select the existing menu and save. The frontend replacement option is **off by default**, so native v2 rendering remains available.
4. Review the read-only dry run, then choose **Populate existing menu (reversible)**. The synchronizer adds only verified published pages whose shortcodes are registered. It preserves other menu links, does not overwrite conflicting URL entries, and records each new menu-item ID for rollback.
5. Reported missing Members (/members/) and Community Groups (/groups/) remain excluded regardless of placeholder page availability. Verify Contributor Intake separately; its supplied status was unspecified.
6. Review the actual WordPress menu in Appearance → Menus. When ready, enable frontend replacement. If LA-Studio Header Builder bypasses the WordPress wp_nav_menu() function, insert shortcode **[onegodian_mega_menu_v3]** into the header instead of using replacement. Do not render both copies in the same header.
7. Confirm the LA-Studio header permits **overflow: visible** and a sufficiently high z-index; verify sticky headers, page cache, mobile and keyboard behavior. Obtain human approval before production deployment.

An existing WordPress menu explicitly named **OneGodian Mega Menu v2** or containing **10.11.26 Mega Menu** is renamed to **OneGodian Mega Menu v3** when the population action completes, unless that new name already belongs to a different menu. The original name is retained in a rollback ledger.

## Rollback

Use **Appearance → OneGodian Mega Menu v3 → Rollback v3 menu additions and disable renderer**. The plugin disables its frontend interception, deletes only the WordPress nav items it created and tagged, restores the old menu title if appropriate, and leaves pre-existing menu items and menu-location assignments intact.

Rollback is intentionally narrower than a full database restore. It cannot undo other plugin or administrator changes made after the upgrade. Deactivating the plugin disables enhanced presentation but does not automatically delete populated links; use its explicit rollback control for menu cleanup. Preserve the database backup.

## Responsive and accessibility acceptance

| Test | Expected |
|---|---|
| Seven primary sections | Exactly seven canonical root headings |
| Desktop grid | No more than six columns per row, no more than seven links per column |
| Existing WordPress links | Unrelated utility roots and their descendants remain reachable |
| Content readiness | Shortcode and published-page gates; two known missing pages withheld |
| Hover behavior | Dropdown remains open as pointer moves into panel; delayed close on exit |
| Keyboard | Tab/Shift-Tab, Enter/Space, ArrowLeft/Right, Home/End, Escape |
| Tablet/mobile | Responsive widths at 320, 375, 600, 768 and 1024px; touch-friendly toggle |
| Wide desktop | Review 1280 and 1440px, sticky header overlays, theme container overflow |
| Reduced motion | All decorative transitions/animations suppressed by user preference |
| Security | Member-only destinations do not disclose data to signed-out visitors |
| Rollback | Prior menu item count and title restored; v3 frontend rendering off |

## Automated checks

Run these commands from the repository root:

    python scripts/test_mega_menu_contract.py
    python scripts/test_site_map_contract.py
    php -l wordpress/plugins/onegodian-mega-menu-v3/onegodian-mega-menu-v3.php
    php -l wordpress/plugins/onegodian-mega-menu-v3/includes/class-ogmm-v3-admin.php
    php scripts/test_mega_menu_v3.php
    node --check wordpress/plugins/onegodian-mega-menu-v3/assets/mega-menu-v3.js

The WordPress stub test checks reversible population, 7×6×7 rendering, shortcodes and published-page gates, pre-existing utility links, idempotency, menu-ID scoping and exact rollback. It does **not** replace live hosted-browser acceptance.
