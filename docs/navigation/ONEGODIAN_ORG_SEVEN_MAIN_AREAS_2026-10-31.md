# OneGodian.org — Seven Main Areas (October 31, 2026)

**Status:** WordPress mega-menu v3.1 repository candidate. Not verified as installed, activated, or deployed.  
**Authoritative input:** October 31 user-supplied 46-row managed-page table.  
**Canonical navigation source:** `config/onegodian-org-mega-menu.json` mirrored in `wordpress/plugins/onegodian-mega-menu-v3/data/managed-pages.json`.

## Final public header order

1. ABOUT — `/about/`
2. IDENTITY — `/identity-principles/`
3. LEARN — `/learn/` (contains OneGodian Time and Date Converter)
4. COMMUNITY — `/community/`
5. ECOSYSTEM — `/ecosystem/`
6. SUPPORT — `/support/`
7. MEDIA CENTER — `/media-center/`

**Home** remains the logo destination, not an eighth main menu area. Preserve `START HERE`, `JOIN`, and `CONTRIBUTE` as independent header actions. Retain all existing page URLs and WordPress page IDs; this change is navigation classification, not a content migration.

## Six-column mega-menu organization

| Area | Groups | Destinations |
| --- | --- | --- |
| ABOUT | History & Legacy; Founder & Origin; Chronology; Records & Evidence | `/history-legacy/`, `/founder-history/`, `/onegodian-origins/`, `/major-milestones/`, `/interactive-timeline/`, `/1926-2026-2126/`, `/creations-inventions/`, `/documents-artifacts/`, `/verified-records/` |
| IDENTITY | Membership & Ally; Belief Mapping; Personal Journey | `/membership/`, `/onegodian-ally/`, `/belief-mapper-lite/`, `/identity-belief-mapper/`, `/belief-map/`, `/journey/` |
| LEARN | OneGodian 101; OneGodian Time; Learning Resources | `/onegodian-101/`, `/onegodian-101-progress/`, `/onegodian-time/`, `/onegodian-date-converter/`, `/resources/` |
| COMMUNITY | Member Access; Member Credentials; Community Spaces; Communications & Help; Membership Applications; Lineage & Family | `/dashboard/`, `/profile/`, `/account/`, `/certificates/`, `/member-id/`, `/community/`, `/members/`, `/groups/`, `/member-messages/`, `/member-notifications/`, `/member-support/`, `/membership-application/`, `/lineage/`, `/family-tree/` |
| ECOSYSTEM | Member Ecosystem; Creator & Partner Tools | `/member-ecosystem/`, `/affiliate-dashboard/` |
| SUPPORT | Contributions; Contact & Inquiries | `/contributors/`, `/contributor-intake/`, `/contact/`, `/development-inquiry/`, `/business-inquiry/`, `/community-inquiry/`, `/membership-inquiry/` |
| MEDIA CENTER | Museum & Archives; Creators & Collaboration | `/digital-museum/`, `/media-archive/`, `/creator-network/`; public landing `/media-center/` |

The proposed OneGodian Campus cross-property link `https://onegodian.com/campus/` belongs under ECOSYSTEM when its actual production route, page and bridge are independently verified; do not insert an unverified route through the managed-page importer.

## Authority and publication rules

- **46 reported managed pages, 19 login-required.** The user's statuses are inventory claims, not independent WordPress runtime proof.
- **Needs Review:** `/resources/` and `/certificates/`. Their protected access and exact shortcode rendering need acceptance.
- `/members/` and `/groups/` are now supplied as *Managed / Working*, superseding the October 11 inventory's Missing status. They are added only when the published-page and registered-shortcode gates pass.
- Preserve the exact supplied shortcodes. Do not substitute the older PR #11 aliases such as `[onegodian_member_dashboard]` or `[onegodian_time_dashboard]`.
- WordPress login, form privacy, member data, certificate issuance and IDs remain owned by their operating plugins. A locked label is explanatory; it is not authentication.
- The previous TIME WordPress menu root remains stored for audit/rollback, but the v3 renderer suppresses it as an eighth root. `/onegodian-time/` and `/onegodian-date-converter/` are displayed under LEARN.
- Do not publish duplicate menu roots or migrate/delete unrelated theme menu items. The live menu previously contained multiple older Members links and unreviewed host aliases; cleanup requires human comparison, staging acceptance and a rollback snapshot.

## Deployment and acceptance gates

1. Compare source branch against the latest `main`; reconcile any changes from PR #12 and active WordPress customizations.
2. Run `python3 scripts/test_mega_menu_contract.py`, `php -l` on plugin PHP files, and `php scripts/test_mega_menu_v3.php` for the exact candidate SHA. Log results, not assumptions.
3. Back up the live database, header builder and menu assignment. Install v3.1 on `2027.onegodian.org` first with rendering disabled.
4. Read the dry-run summary, confirm page existence/shortcode registration and explicit review of protected pages, then populate the selected existing menu. Resolve conflicts instead of overwriting custom edits.
5. Activate one header renderer (WordPress menu replacement **or** `[onegodian_mega_menu_v3]` in LA-Studio), never both. Verify seven roots, Time nested under Learn and Media Center at position seven.
6. Verify desktop hover, mobile accordion, keyboard, screen reader, focus, CTA buttons, member/nonmember behavior, CSS overflow, links and rollback.
7. Only after owner approval deploy the exact tested plugin/frontend and clear caches; record the served version, screenshot evidence and restoration procedure.

**Release rule:** source changes ≠ WordPress staging verification ≠ production deployment.
