# OneGodian Members v2.2.0 — OneGodian.org Integration Record

Updated: September 29, 2026

## Purpose

This file records the current OneGodian Members WordPress plugin target for OneGodian.org.

## Canonical source

- Plugin: OneGodian Members
- Version: 2.2.0
- Canonical source repository: `ohi-stack/onegodian-platform-plugin`
- Canonical branch: `main`
- Package: `onegodian-members-v2.2.0-production.zip`
- WordPress runtime target: `https://onegodian.org`

The repository source and CI artifact do not by themselves prove that the corresponding plugin package is currently installed or verified on the live WordPress site.

## Managed routes

The v2.2.0 plugin defines these key managed routes:

- `/member-dashboard/`
- `/member-profile/`
- `/membership/`
- `/onegodian-ally/`
- `/belief-mapper/`
- `/onegodian-journey/`
- `/onegodian-time/`
- `/onegodian-date-converter/`
- `/member-resources/`
- `/member-certificate/`
- `/member-id/`
- `/community/`
- `/members/`
- `/groups/`
- `/member-messages/`
- `/member-notifications/`
- `/member-support/`
- `/my-account/`
- `/affiliate-dashboard/`
- `/creator-network/`
- `/contributors/`

Important: `/member-dashboard/` is the member dashboard. `/members/` is the community directory.

## v2.2.0 additions

- Identity & Belief Mapper™ member runtime
- seven administrator-configurable question slots, empty by default
- private consent-gated responses
- self-selected Seeker / Believer / OneGodian / Elder journey stage
- Journey Center with Journey / Reflect / Time / Discover surfaces
- OneGodian Time™ / OTS-V5 conversion and display tools
- current OT date and dual-date presentation
- motion/animation system with reduced-motion accessibility
- managed page generation/repair
- BuddyPress compatibility
- WooCommerce account/membership integration
- REST surfaces for member summary, Mapper, and OTS-V5

## Privacy boundary

Raw Mapper answers are private member data and should not be exposed in ordinary public pages, generic analytics, outbound summary events, or public BuddyPress profile data.

Journey stage is self-selected and must not be automatically assigned from answers, scores, badges, streaks, XP, or activity.

## Production verification

Before calling the live OneGodian.org deployment production-verified, confirm:

- activation/upgrade completes without error
- managed pages are created or repaired correctly
- WooCommerce account and membership flows work
- BuddyPress enabled/disabled paths work
- Mapper permissions and private-answer handling work
- OTS-V5 conversion and display work
- responsive layouts work
- keyboard/accessibility behavior works
- reduced-motion mode works
- optional `api.onegodian.org` synchronization behaves as documented

## Operating rule

If a capability is not operational, documented, repeatable, testable, and deployed, it should not be represented as active production functionality.
