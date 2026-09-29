# OneGodian Member Dashboard — Page Contract

## Canonical route

`https://onegodian.org/member-dashboard/`

## Runtime authority

The page is rendered by the **OneGodian Members** WordPress plugin. The site page is a host surface; membership state, login handling, profile data, community access, OneGodian 101 progress, WooCommerce account integration, and protected-member behavior remain plugin/runtime responsibilities.

## Canonical shortcode

`[onegodian_member_dashboard]`

The historical shortcode `[onegodian_members_dashboard]` is a legacy alias only. Members v2.3.0 must recognize it for backward compatibility and managed-page repair must migrate it to the canonical shortcode without deleting manually authored content around it.

A public visitor must never see either shortcode as literal page text.

## Logged-out experience

When a visitor is not authenticated, the Member Dashboard must render the branded OneGodian member-access scene rather than an empty panel or raw shortcode.

The login surface must clearly expose:

- **Login Details**
- **Username or email address**
- **Password**
- **Remember me**
- **Forgot password?**
- **Create Account**
- **Explore Membership**

The actual credential form must be provided by WooCommerce My Account when available, with a WordPress-native login form as the fallback. No repository page, JavaScript bundle, API response, or static page specification may contain or collect a user's password.

## Logged-in experience

Authenticated visitors may receive the OneGodian Members dashboard, including membership status, member ID, profile completion, profile access, OneGodian ID, certificates, community, member resources, account/billing, support, and OneGodian 101 progress where available.

## Related routes

- Account/login: `/my-account/`
- Membership: `/membership/` and the active membership-commerce surface
- OneGodian 101: `/onegodian-101/`
- OneGodian 101 signed-in progress: `/onegodian-101-progress/`
- Member profile: `/member-profile/`

## Deployment verification

After installing or upgrading OneGodian Members v2.3.0:

1. Run the plugin's managed-page synchronization/repair.
2. Confirm `/member-dashboard/` uses `[onegodian_member_dashboard]`.
3. Clear page, object, CDN, and server caches that can retain the historical shortcode output.
4. Test the page logged out on desktop and mobile.
5. Confirm the login form renders and the recovery/account-creation actions resolve.
6. Test the page with an authenticated member account.
7. Run `bash scripts/live_site_audit.sh https://onegodian.org` and resolve any `FAIL` result before treating the deployment as verified.

Repository conformance does not by itself prove that the live WordPress property has been upgraded or that its caches have been cleared.
