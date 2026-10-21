# OneGodian.org — Seven Premium WPBakery Root Pages

**Date:** October 21, 2026
**Version:** 1.0.0
**Status:** Source implementation prepared for staging; **not imported, deployed, browser-tested or production-certified**.

## Scope and content

The seven **main-area landing pages** are About, Identity, Learn, Time, Community, Ecosystem and Support. The Home page remains a separate entry. Each page contains:
- A unique H1, four section headings and founder-aligned introduction
- Full-width cinematic gradient hero, gold/violet visual orbit, legible supporting copy, two CTA actions
- Four complete cards linked to an appropriate existing or reported destination
- An editorial evidence/value statement, three journey steps, and three supplementary wayfinding links
- Final action section and route-specific public-safe legal/status notice

The source is real WPBakery shortcode content (`[vc_row]`, `[vc_column]`, `[vc_column_text]`) and does not rely on a second frontend framework or fabricated metrics. All styling is in one scoped companion WordPress plugin; no remote fonts or images required.

## Architecture and routing rules

- Source: `ohi-stack/onegodian-org` (public identity, learning, history, membership explanations).
- The open seven-area navigation upgrade, PR #11, is **not replaced or merged** by this package. It must undergo its own deployment/rollback acceptance.
- OneGodian Members owns membership state and protected member access. Public links to login-gated routes must not bypass authentication.
- OneGodian.com handles commercial products; `u.onegodian.com` handles University courses; `omos.onegodian.com` and `quantumohi.com` own their distinct technology/research roles; `odefi.onegodian.com` is the finance layer.
- Retired `app.onegodian.com` and `capital.onegodian.com` must not be used as navigation destinations.
- INO governance is separate from ONEGODIAN, LLC's commercial work. No visitor-flow CTA or internal OneGodian page confers governmental status.

## Installation in staging — do not overwrite existing live pages

1. Create WordPress database and file backups. Record existing root-page IDs, URL slugs, saved page revisions, theme/page-template settings, and SEO plugin settings.
2. Verify current WPBakery plugin activation and theme full-width support on **2027.onegodian.org**.
3. Package directory `wordpress/plugins/onegodian-premium-vc-pages/` as a plugin ZIP; install and activate **in staging**. It loads one CSS file only on the seven root slugs.
4. Create separate staging copies of existing `/about/`, `/identity/`, `/learn/`, `/time/`, `/community/`, `/ecosystem/`, and `/support/` pages or update the staging versions only after backup. Paste each respective `.vc.txt` into **WPBakery Classic/Text** shortcode content, not as literal text in a Code Block. Keep all five `[vc_row]` blocks intact.
5. Use a full-width page template without duplicate theme H1 or excessive header padding. Verify theme/WPBakery nested column styling doesn't conflict.
6. Configure one unique SEO title and meta description per page using `config/premium-seven-pages.json`; defer canonical tags to the active SEO plugin.
7. Verify that every CTA and card destination is published and operational with correct visitor permission. PR #11's route status fields originate from reported inventories, not verified browser tests. **Do not link to missing `/members/` or `/groups/`**.
8. Stage the corresponding mega-menu update separately and check links target these seven root pages when the menu is activated.
9. Run viewport acceptance at 320, 375, 768, 1024 and 1440 px; verify keyboard navigation, focus outlines, high-contrast text, reduced-motion settings, sticky header behavior, no horizontal overflow, no empty cards, and image-free layout balance.
10. After human approval, deploy exact inspected revisions; purge caches; repeat server/browser link, protected access, SEO and typography validation; retain screenshots and rollback proof.

## Source tests

`python3 scripts/test_premium_vc_pages.py`

`php -l wordpress/plugins/onegodian-premium-vc-pages/onegodian-premium-vc-pages.php`

CI packages the styling plugin into a WordPress-installable ZIP artifact. A passing source test **does not prove WPBakery rendering or live page acceptance**.

## Rollback

Restore each pre-change WordPress page revision and metadata or database snapshot, deactivate this styling plugin, purge caches, and recheck seven route URLs. Do not alter unrelated menus or member data.

## Release gate

- [ ] All seven pages imported on staging and visually inspected
- [ ] All seven routes, section anchors, CTAs and disclosures verified
- [ ] Logged-out, logged-in, and denied access behavior correct
- [ ] Mobile, tablet and desktop responsive screenshots retained
- [ ] Page title, H1, metadata, contrast, keyboard and reduced-motion acceptance
- [ ] No broken destinations, JavaScript errors, false stats or speculative status claims
- [ ] Human approval; exact deployment revision and restoration plan recorded
- [ ] Live WordPress/browser verification after deployment

**Build → Integrate → Test → Deploy → Verify.**
