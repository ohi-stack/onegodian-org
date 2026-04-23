# OneGodian Production-Readiness Audit (2026-04-23)

## Scope and assumptions

- Audit date: **2026-04-23 (UTC)**.
- Repository audited: `onegodian-org` (this repo).
- Live site audited: `https://onegodian.org`.
- This is an external black-box site audit + internal repo readiness audit.
- No production code changes were made to the live WordPress instance from this repository because this repo does not currently contain deployable site/theme/plugin code.

---

## A) Executive summary

OneGodian is currently running a **WordPress + WooCommerce** stack (not Next.js/React), hosted behind Hostinger infrastructure with Envoy and H CDN headers. The live site is online and mostly reachable, but production readiness is constrained by security posture, conversion UX, and operational reproducibility.

Top concerns:
1. **Security exposure:** public REST user endpoint returns admin profile details (`is_super_admin`) and several common hardening headers are missing.
2. **Conversion leakage:** homepage contains broken internal links (`?page_id=506`, `?page_id=568`) and no strong above-the-fold conversion CTA hierarchy.
3. **Operational gap:** repository is largely documentation-only, with no infrastructure-as-code, deployment workflow, or versioned WordPress theme/plugin artifacts.

Top safe improvements implemented in this repo:
- Added a **repeatable live-site audit script** (`scripts/live_site_audit.sh`).
- Added a **full audit report** with ranked issues, roadmap, architecture recommendations, and monetization opportunities.
- Updated docs to make the audit process discoverable.

---

## B) Ranked issue list

### Critical

1. **Public user enumeration reveals privileged account metadata**
   - `GET /wp-json/wp/v2/users?per_page=5` returns user object with `is_super_admin` and identifiable author slug.
   - Risk: account targeting, reconnaissance, brute-force preconditioning.

2. **Missing security headers on main pages**
   - Missing on homepage: `Strict-Transport-Security`, `X-Content-Type-Options`, `X-Frame-Options`, `Referrer-Policy` (present on login, absent on front page), `COOP`, `CORP`.
   - Existing CSP is only `upgrade-insecure-requests`, which is minimal.

3. **Broken homepage navigation links**
   - Internal links found returning 404:
     - `https://onegodian.org/?page_id=506`
     - `https://onegodian.org/?page_id=568`
   - These create user drop-off and crawl waste.

### Medium

4. **High plugin/theme footprint and frontend weight**
   - Theme: `airi` (v1.7.0) and WPBakery/WooCommerce/Jetpack/RankMath/Site Kit present.
   - Homepage HTML payload observed around ~158KB before subresources and scripts, with many styles/scripts.

5. **Homepage messaging clarity and CTA hierarchy are weak**
   - Title currently appears as `4.4.26-O - onegodian.org`.
   - Brand narrative and value proposition are not immediately packaged into a clear conversion sequence.

6. **Repo-to-production disconnect**
   - Current repo lacks manifests, code modules, deployment scripts, env templates, CI/CD checks, and reproducible build instructions.
   - Inability to audit dependency freshness directly from repo because package manifests are absent.

### Low

7. **Canonical path consistency issues**
   - Both `/about` and `/about/` and both `/membership` and `/membership/` resolve.
   - This is often fine when canonicalized, but should be verified for duplicate indexing effects.

8. **Brand consistency opportunity**
   - Public metadata and visual naming conventions vary across pages and could be systematized in a style/content governance doc.

---

## C) Revenue opportunity list (highest value first)

1. **Homepage conversion ladder (high impact / low engineering risk)**
   - Add a clear hero with:
     - one-sentence value proposition,
     - primary CTA (`Start Here`),
     - secondary CTA (`Join Membership`),
     - social proof strip.

2. **Email capture architecture**
   - Add lead magnet funnel:
     - free “Foundations” PDF/email series,
     - embedded forms on homepage + blog + exit intent,
     - segmented follow-up flows (new seekers, contributors, enterprise/API).

3. **Productized education funnel**
   - Route users from free articles -> structured learning path -> premium course/cohort -> membership.
   - Add clearer “next best action” modules at end of high-traffic content.

4. **Donation + patron flow optimization**
   - Introduce tiered contribution options (supporter / builder / patron), transparent impact statements, recurring preference defaults.

5. **Founder credibility system**
   - Build trust page: founder bio, mission timeline, community milestones, testimonials, media mentions, FAQ on doctrine/platform.

6. **API/enterprise upsell path**
   - Add top-nav “API / Institutional” page with pricing-entry CTA and qualification form.

---

## D) Exact files changed in this audit pass

1. `scripts/live_site_audit.sh`
2. `docs/audits/2026-04-23-production-readiness-audit.md`
3. `docs/README.md`
4. `README.md`

---

## E) Recommended 30-day roadmap

### Days 1–7 (stabilize + harden)
- Fix broken homepage links.
- Block/limit REST user enumeration for unauthenticated requests.
- Add security headers at edge or origin (`HSTS`, `X-Content-Type-Options`, `Referrer-Policy`, etc.).
- Set up uptime and synthetic checks for top conversion pages.

### Days 8–15 (conversion foundation)
- Redesign homepage CTA structure and trust blocks.
- Add email capture with lead magnet and thank-you flow.
- Instrument key events (hero CTA click, email submit, checkout start, membership start).

### Days 16–23 (monetization expansion)
- Launch a structured product funnel (free -> paid).
- Add donation tiers + recurring defaults.
- Build a dedicated institutional/API landing page.

### Days 24–30 (ops maturity)
- Establish source-of-truth repo for theme/plugin customizations.
- Add CI checks (link checks, lint, basic accessibility assertions).
- Document environment variable matrix and deployment runbook.

---

## F) Recommended production architecture for the OneGodian ecosystem

### Current inferred architecture
- WordPress monolith (content + commerce + plugins) on Hostinger-managed stack.

### Recommended target architecture (progressive)
1. **Presentation layer**
   - Keep WordPress for editorial workflows initially.
   - Introduce headless-ready content APIs for future web/app channels.

2. **Commerce + membership layer**
   - Keep WooCommerce short-term.
   - Standardize payment, subscription, and entitlement events into an event bus/webhook service.

3. **Platform services**
   - Externalize business logic (accounts, entitlements, API keys, analytics pipelines) into dedicated service(s) (e.g., `onegodian-api`).

4. **Data + analytics**
   - Central event model (web, checkout, membership, API usage).
   - Warehouse + dashboard for funnel and retention metrics.

5. **Security + operations**
   - WAF/CDN edge headers + bot/rate-limiting.
   - Staging environment with plugin update validation.
   - Backup/restore drills and incident playbooks.

---

## Immediate safe improvements implemented now

- Added a reusable shell script (`scripts/live_site_audit.sh`) for quick production checks:
  - response/timing,
  - security headers,
  - WordPress stack markers,
  - internal-link status from homepage,
  - REST users exposure check.
- Added this structured audit report and linked it from docs/README.
- Expanded root README with a clear “Current state” and “Audit workflow” section so future contributors can execute the same checks quickly.

