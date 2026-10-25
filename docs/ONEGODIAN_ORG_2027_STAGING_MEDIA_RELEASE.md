# OneGodian.org 2027 — staging media and release verification

**Status:** implementation contract committed to PR #12; not a WordPress installation, content import, or deployment.
**Source manifest:** `config/2027-media-staging-acceptance.json`.
**Companion work:** Seven root page designs PR #12; menu/navigation PR #11. Keep both separate until integrated staging checks pass.

## Work order
1. Back up the 2027 staging database, uploads, plugins, active theme, menu assignments, SEO settings and existing page revisions. Capture restoration steps.
2. Verify staging can render WPBakery shortcode pages, including five row blocks per page and the seven-page CSS plugin. Record installed WordPress, PHP, theme and plugin versions. Never infer compatibility from a source-only plugin.
3. Import **About, Identity, Learn, Time, Community, Ecosystem and Support** from `wordpress/pages/premium-seven/*.vc.txt`; retain exact page IDs/slugs and save staging screenshots before and after each change.
4. Produce/upload seven original or properly licensed 16:9 hero images, optional 1:1 supporting crops; apply alt text and width/height dimensions. Concept renders must be identified as illustrative rather than photographs of actual OneGodian facilities, personnel or events.
5. Produce seven short authentic explanatory videos or leave clearly described, non-broken editorial placeholders until media is approved. Do not embed an invented YouTube URL. Every published video needs captions, transcript, still poster and keyboard-operable controls.
6. Verify the installed Members plugin remains the sole authority for membership and protected user data. Integrate OneGodian Forms Bridge only after field validation, WordPress submission storage, spam protection, alerts and consent/privacy review pass. Don't show operational signup or payment success until an end-to-end transaction succeeds.
7. Reconcile PR #11 primary navigation with seven routes and Home as distinct. Preserve **START HERE**, **JOIN**, **CONTRIBUTE** in desktop/mobile headers. Link all dropdowns to published URLs only. Reject retired `capital.onegodian.com` and `app.onegodian.com` and check domain authority with ODeFi/OneGodian.com.
8. Visit each route signed out, member signed in, and unauthorized user. Verify distinct H1, headings, video poster/caption, image loads, section anchors, CTA responses, footer, SEO canonical, breadcrumbs and no PHP/JS console failures. Test viewports 320, 375, 768, 1024 and 1440 px, keyboard menus, visible focus and reduced motion.
9. Collect HTTP status/redirect logs, plugin version list, staging URLs, screenshots, automated lint/build/test outputs and page-specific media URLs. Record any unresolved item as blocked, not passed.
10. Obtain review before production. Export final WPBakery content and menu definitions, back up production, apply exact reviewed artifacts, rebuild/redeploy the frontend, clear caches, verify production, and retain rollback proof.

## Recording evidence
For every page in the JSON manifest, populate WordPress page ID, verified asset URLs, approved video/captions/transcript URLs, staging results and responsible reviewer. Never change a `false` verification field to `true` without a recorded observation. A passing GitHub workflow proves source quality only, not staging behavior.

## Current access findings (October 25, 2026)
- GitHub repository and PR #12 source are accessible through the connected GitHub integration.
- Public checks of `https://2027.onegodian.org/`, `/about/` and `/identity/` could not establish accessible pages via the available web retrieval channel. This is **inconclusive**, not proof the host is down.
- No authenticated WordPress administrator or server deployment action has been executed in this upgrade.
- Therefore seven-page import, plugin activation, video playback and production launch remain **unverified**.

**Release rule:** source-complete ≠ staging-verified ≠ deployed ≠ production-ready.
