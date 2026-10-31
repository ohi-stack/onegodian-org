# OneGodian.org — Members and Forms Mega Menu (October 11, 2026)

**Site:** https://onegodian.org/  
**Navigation ownership:** `ohi-stack/onegodian-org`  
**Shortcode, managed-page, account and authorization ownership:** OneGodian Members runtime (`ohi-stack/onegodian-members`)

## Scope and safety

Integrate the 43 supplied member-managed pages into the existing OneGodian.org seven-area navigation: **ABOUT · IDENTITY · LEARN · TIME · COMMUNITY · ECOSYSTEM · SUPPORT**. Add the requested links without deleting unrelated navigation or changing the retired OneGodian App architecture. This is **not** the older OneGodian.com commercial mega-menu file (`ONEGODIAN_FINAL_MEGA_MENU_PRODUCTION.md`).

The manifest captures the user's reported statuses, **not independently verified live uptime**. The listed **Members** `/members/` and **Community Groups** `/groups/` routes are reported Missing; keep them as pending until both the WordPress page is published and the corresponding plugin shortcode is registered. Contributor Intake's login/status cells were omitted from the supplied inventory; it is classified public based on the earlier six-public-forms Forms Bridge design, and its runtime must still be verified.

**No external legal, membership, religious, identity or financial status is conferred by a menu item.** The menu may expose links to login-gated pages; the actual access decision remains with the Members/WordPress runtime.

## Seven-area mega-menu map

| Menu | Group | Label | Canonical path | Shortcode | Login | Reported status |
|---|---|---|---|---|---|---|
| ABOUT | History & Legacy | History & Legacy | `/history-legacy/` | `[onegodian_history_legacy]` | No | Managed / Working |
| ABOUT | History & Legacy | Interactive Timeline | `/interactive-timeline/` | `[onegodian_interactive_timeline]` | No | Managed / Working |
| ABOUT | History & Legacy | 1926 → 2026 → 2126 | `/1926-2026-2126/` | `[onegodian_legacy_eras]` | No | Managed / Working |
| ABOUT | History & Legacy | Digital Museum | `/digital-museum/` | `[onegodian_digital_museum]` | No | Managed / Working |
| ABOUT | Founder Records | Founder History | `/founder-history/` | `[onegodian_founder_history]` | No | Managed / Working |
| ABOUT | Founder Records | OneGodian Origin | `/onegodian-origins/` | `[onegodian_origins]` | No | Managed / Working |
| ABOUT | Founder Records | Major Milestones | `/major-milestones/` | `[onegodian_major_milestones]` | No | Managed / Working |
| ABOUT | Archives & Evidence | Creations & Inventions | `/creations-inventions/` | `[onegodian_creations]` | No | Managed / Working |
| ABOUT | Archives & Evidence | Documents & Artifacts | `/documents-artifacts/` | `[onegodian_artifacts]` | No | Managed / Working |
| ABOUT | Archives & Evidence | Media Archive | `/media-archive/` | `[onegodian_media_archive]` | No | Managed / Working |
| ABOUT | Archives & Evidence | Verified Records | `/verified-records/` | `[onegodian_verified_records]` | No | Managed / Working |
| IDENTITY | Membership & Ally | Membership | `/membership/` | `[onegodian_members_pricing]` | No | Managed / Working |
| IDENTITY | Membership & Ally | OneGodian Ally | `/onegodian-ally/` | `[onegodian_ally_status]` | Yes | Managed / Working |
| IDENTITY | Belief Mapping | Identity & Belief Mapper Lite | `/belief-mapper-lite/` | `[onegodian_lite_mapper]` | No | Managed / Working |
| IDENTITY | Belief Mapping | Identity & Belief Mapper | `/belief-mapper/` | `[onegodian_identity_mapper_dashboard]` | Yes | Managed / Working |
| IDENTITY | Belief Mapping | Personal Belief Map | `/belief-map/` | `[onegodian_belief_map]` | Yes | Managed / Working |
| IDENTITY | Personal Identity | OneGodian Journey | `/onegodian-journey/` | `[onegodian_journey_center]` | Yes | Managed / Working |
| IDENTITY | Personal Identity | Member Certificate | `/member-certificate/` | `[onegodian_member_certificate]` | Yes | Managed / Working |
| IDENTITY | Personal Identity | My OneGodian ID | `/member-id/` | `[onegodian_digital_id]` | Yes | Managed / Working |
| LEARN | OneGodian 101 | OneGodian 101 | `/onegodian-101/` | `[onegodian_101]` | No | Managed / Working |
| LEARN | OneGodian 101 | OneGodian 101 Progress | `/onegodian-101-progress/` | `[onegodian_101_progress]` | Yes | Managed / Working |
| LEARN | Learning Resources | Member Resources | `/member-resources/` | `[onegodian_member_resources]` | Yes | Managed / Working |
| TIME | Time Tools | OneGodian Time | `/onegodian-time/` | `[onegodian_time_dashboard]` | No | Managed / Working |
| TIME | Time Tools | OneGodian Date Converter | `/onegodian-date-converter/` | `[onegodian_date_converter]` | No | Managed / Working |
| COMMUNITY | Member Access | Member Dashboard | `/member-dashboard/` | `[onegodian_member_dashboard]` | Yes | Managed / Working |
| COMMUNITY | Member Access | Member Profile | `/member-profile/` | `[onegodian_member_profile]` | Yes | Managed / Working |
| COMMUNITY | Member Access | Login & Account | `/my-account/` | `[onegodian_account]` | No | Managed / Working |
| COMMUNITY | Community & Groups | OneGodian Community | `/community/` | `[onegodian_social_dashboard]` | Yes | Managed / Working |
| COMMUNITY | Community & Groups | Members | `/members/` | `[onegodian_community_directory]` | Yes | Missing |
| COMMUNITY | Community & Groups | Community Groups | `/groups/` | `[onegodian_community_groups]` | Yes | Missing |
| COMMUNITY | Communications & Help | Messages | `/member-messages/` | `[onegodian_messages]` | Yes | Managed / Working |
| COMMUNITY | Communications & Help | Notifications | `/member-notifications/` | `[onegodian_notifications]` | Yes | Managed / Working |
| COMMUNITY | Communications & Help | Member Support | `/member-support/` | `[onegodian_member_support]` | Yes | Managed / Working |
| ECOSYSTEM | Member Ecosystem | Member Ecosystem | `/member-ecosystem/` | `[onegodian_member_ecosystem]` | Yes | Managed / Working |
| ECOSYSTEM | Creators & Affiliates | Affiliate Dashboard | `/affiliate-dashboard/` | `[onegodian_affiliate_dashboard]` | Yes | Managed / Working |
| ECOSYSTEM | Creators & Affiliates | Creator Network | `/creator-network/` | `[onegodian_creator_network]` | Yes | Managed / Working |
| SUPPORT | Contributors | Contributors | `/contributors/` | `[onegodian_contributors_page]` | No | Managed / Working |
| SUPPORT | Contributors | Contributor Intake | `/contributor-intake/` | `[onegodian_contributor_intake_form]` | No | Unspecified in supplied list |
| SUPPORT | Contact & Inquiries | Contact OneGodian | `/contact/` | `[onegodian_contact_form]` | No | Managed / Working |
| SUPPORT | Contact & Inquiries | Development Inquiry | `/development-inquiry/` | `[onegodian_development_inquiry_form]` | No | Managed / Working |
| SUPPORT | Contact & Inquiries | Business Inquiry | `/business-inquiry/` | `[onegodian_business_inquiry_form]` | No | Managed / Working |
| SUPPORT | Contact & Inquiries | Community Inquiry | `/community-inquiry/` | `[onegodian_community_inquiry_form]` | No | Managed / Working |
| SUPPORT | Contact & Inquiries | Membership Inquiry | `/membership-inquiry/` | `[onegodian_membership_inquiry_form]` | No | Managed / Working |

**Total:** 43 entries; 19 login-gated; 24 public/guest-accessible by specification; 2 explicitly Missing; 1 with incomplete supplied status.

## WordPress navigation update procedure

1. Back up the production database and export the existing WordPress menus. Confirm the currently assigned header navigation menu ID/location, and test the process against the hosted staging instance first.
2. Deploy this repository's `config/onegodian-org-mega-menu.json` and `scripts/sync_onegodian_mega_menu.php` together to an accessible working directory on the WordPress host. Install/activate the appropriate OneGodian Members release **before** running the menu sync. Do not copy shortcode implementation into the website repository.
3. Execute a **dry run** using the actual menu term ID or actual registered theme menu location. Do not guess the theme's location name:

```bash
wp menu list --fields=term_id,name,slug
wp menu location list
ONEGODIAN_MENU_ID=123 ONEGODIAN_MENU_DRY_RUN=1 wp eval-file scripts/sync_onegodian_mega_menu.php
```

4. Review every skipped entry. A reported Missing page is **not** proof of a working member-directory/groups integration. Repair provisioned pages from the Members managed-page administration and test shortcode output; then rerun the dry-run.
5. Apply only after review:

```bash
ONEGODIAN_MENU_ID=123 wp eval-file scripts/sync_onegodian_mega_menu.php
```

6. The script is additive and idempotent **within its exact parent/group/title scope**. It reuses current seven-area parent items by title, creates missing group headings and requested links, corrects the canonical URL of a matching link, and never deletes unrelated existing links or content. Legacy links elsewhere may remain until separately reviewed. The script does not alter themes, assign a new menu location, or enable a theme-specific mega-menu toggle.
7. In the LA-Studio Header Builder/theme menu settings, verify the selected menu remains assigned and that **third-level group items** render as expected in a seven-area mega dropdown. Enable mega-menu presentation if the theme requires it. Ensure desktop/mobile navigation, keyboard focus and accessible group headings.

## Canonical destination cleanup

- Keep **`/membership/`**, not `/memberships/`.
- Keep **`/member-id/`**, not `/my-onegodian-id-card/`.
- University destinations use **https://u.onegodian.com/**, not `u.onegodian.org`.
- Remove **App.OneGodian.com** from live navigation because the standalone App is retired.
- Do not redirect or delete the missing `/members/` and `/groups/` routes merely to conceal an unresolved runtime issue. Implement/repair and validate them first.
- Add any legacy 301 redirects only **after** canonical destinations have been checked.

## Acceptance criteria

- Seven top-level areas are visible once, in the intended order; legacy unrelated header, footer and utility actions remain intact.
- All active links use supplied canonical paths; none display literal `[onegodian_*]` tags.
- Public pages render meaningful content to logged-out visitors. Protected pages resolve to branded authentication UI/approved redirect, and actual data requires permission.
- The public Lite Mapper must not expose private belief-map responses. Full Mapper, Belief Map, Member ID, profile, messages and notifications enforce member access.
- The missing directory/group links are withheld until the WordPress page and shortcode are verified together.
- Contributor Intake requires a real form-submission test, including spam protections and submission persistence, before being called production-verified.
- No broken links, mobile overflow, keyboard traps or WPBakery/header builder regressions.
- Human WordPress release approval plus staging/live screenshots and link checks are required before the navigation is called deployed.

## Implementation state

**Repository feature branch:** prepared for review. **Production menu:** not modified by this commit. WordPress menus live in the site's database/theme settings and require authenticated site-side execution. Repository changes do not deploy themselves.
