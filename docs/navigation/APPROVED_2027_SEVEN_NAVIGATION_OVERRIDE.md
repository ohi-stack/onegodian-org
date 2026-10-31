# OneGodian.org 2027 — Approved seven-area navigation override

Status: approved specification; implementation and staging acceptance pending.
Supersedes the order in PR #11. Do not merge until renderer, configuration, tests and WordPress staging match this specification.

## Header order (exact)
1. About
2. Identity
3. Learn
4. Media
5. Community
6. Ecosystem
7. Support

Brand: ONEGODIAN™. Separate header action: Login / My Dashboard.

## Featured links
- About: OneGodian Origin (/onegodian-origins/), Founder History (/founder-history/), History & Legacy (/history-legacy/), Major Milestones (/major-milestones/), Creations & Inventions (/creations-inventions/).
- Identity: What Is OneGodian? (verify canonical slug), Membership (/membership/), OneGodian Ally (/onegodian-ally/), Identity & Belief Mapper (/identity-belief-mapper/), OneGodian Lineage (/lineage/), Family Tree (/family-tree/).
- Learn: OneGodian 101 (/onegodian-101/), OneGodian Time (/onegodian-time/), Date Converter (/onegodian-date-converter/), OneGodian Campus (verify route), Learning Progress (/onegodian-101-progress/), Member Resources (/resources/; needs review).
- Media: Media Center (/media-center/; verify publication), Digital Museum (/digital-museum/), Media Archive (/media-archive/), Documents & Artifacts (/documents-artifacts/), Interactive Timeline (/interactive-timeline/), Publications (verify route).
- Community: Member Dashboard (/dashboard/), Community (/community/), Members (/members/), Groups (/groups/), Messages (/member-messages/), Creator Network (/creator-network/), Member Support (/member-support/).
- Ecosystem: Ecosystem Map (verify route), OneGodian City (verify route), Technology & Platforms (verify route), Development Opportunities (/development-inquiry/ as interim inquiry destination only), Business & Commerce (verify route).
- Support: Become a Contributor (/contributor-intake/), Membership Application (/membership-application/), Development Inquiry (/development-inquiry/), Business Inquiry (/business-inquiry/), Community Inquiry (/community-inquiry/), Contact (/contact/).

## Invariants
- Time and Date Converter occur under Learn only in primary navigation; no Time top-level item.
- Media is fourth; Media Center is its featured destination, not a seventh top-level item.
- Campus under Learn; infrastructure context cross-linked from Ecosystem.
- Preserve exact user-supplied WordPress shortcode registrations and all login gates; never fabricate route publication or bypass authentication.
- Desktop six-column menus and mobile accordion share a single hierarchy.
- Target 7 x 6 x 7 = 294 slots plus 4 x 6 footer; do not fill gaps with fake links.
- Existing /resources/ and /certificates/ require review.
- Preserve menu rollback ledger and legacy data until staging signoff.

## Release gates
1. Reconcile existing PR #11 v3.1 renderer and config with this order and exact labels.
2. Confirm /media-center/, Campus, Ecosystem Map, Publications, City and other proposed destinations actually exist before exposing links.
3. Validate all 46 supplied member routes, shortcode registration, permissions and published state.
4. Test desktop/mobile, keyboard/ARIA, duplicate prevention, login redirects, rollback.
5. Obtain human approval before production deployment.
