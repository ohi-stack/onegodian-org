# OneGodian.org — Belief Mapper Landing Page

Status: ready for WordPress publication
Canonical public slug: `/belief-mapper/`
App destination: `https://app.onegodian.com/belief-mapper`

## Purpose

This page is the public discovery and education layer for the Gen Alpha / Gen Beta Belief Mapper funnel. OneGodian.org explains the experience and sends users to the canonical application. It does not duplicate scoring logic or store belief answers in WordPress.

## WordPress Source

Paste the contents of `wordpress/pages/belief-mapper-gen-alpha.vc.txt` into a WPBakery/Visual Composer page using the canonical OneGodian.org page environment.

## Page Settings

- Title: `Belief Mapper`
- Slug: `belief-mapper`
- SEO title: `OneGodian Belief Mapper | Explore What You Believe`
- Meta description: `Answer five quick questions about unity, purpose, and belief identity. The OneGodian Belief Mapper is a voluntary, privacy-first reflection experience.`
- Primary CTA: `Start the Belief Mapper`
- CTA URL: `https://app.onegodian.com/belief-mapper`
- Indexing: index/follow after final privacy and accessibility review

## Architecture Boundary

- `OneGodian.org/belief-mapper/` = discovery, education, public explanation
- `app.onegodian.com/belief-mapper` = interactive frontend
- `api.onegodian.org/api/v1/belief-mapper` = authoritative questions and scoring runtime
- INO membership/governance = separate affirmative process

## Production Check

Before publishing publicly:

1. Confirm `https://app.onegodian.com/belief-mapper` is deployed and reachable.
2. Confirm the API endpoint is deployed and accepts the current v0.3 contract.
3. Confirm WordPress does not attach marketing analytics to answer data.
4. Review mobile layout at 320px, 375px, 768px, and desktop widths.
5. Validate keyboard focus, contrast, headings, and CTA labels.
6. Add the page to the most relevant Identity / Belief / Tools navigation location.

The page should not state that a quiz result makes someone OneGodian. The user chooses their own identity.