# OLLM WordPress Plugin Integration — October 11, 2026

Status: IMPLEMENTATION SPECIFICATION — not evidence of installed or deployed functionality.
Operator: ONEGODIAN, LLC.

## Boundaries
- OneGodian.org: public OLLM explanation at /ollm, membership discovery and approved links.
- llm.onegodian.org: independent authenticated OLLM application, synthesis and history.
- api.onegodian.org: shared identity federation, entitlement/usage contracts, application bridge and observability.
- OneGodian.com: canonical store and commerce authority. OneGodian.org must not become a competing checkout system.
- onegodian-members: membership identity and WordPress access presentation authority.
- onegodian-platform-plugin: shared WordPress API bridge, capability registry, health and integration metadata. Do not duplicate the OLLM runtime.

## P0 integration contracts
1. Register OLLM as a capability with explicit maturity: conceptual, prototype, functional, verified, production. Do not mark production without hosted acceptance.
2. Surface an OLLM launch link and account access entry point on /ollm and approved member dashboard surfaces.
3. Exchange identity via supported OAuth/OIDC or a short-lived server-issued one-time code; validate issuer, audience, state, nonce, expiration and replay. Never pass WordPress passwords or long-lived bearer tokens in URL parameters.
4. Read OLLM plan/entitlement/usage from api.onegodian.org. Fail closed for paid execution if API entitlement cannot be verified.
5. Map canonical user IDs with explicit consent and privacy controls; avoid assuming WordPress user IDs equal OLLM user IDs.
6. Receive verified commerce subscription webhooks from canonical commerce infrastructure; enforce signature, idempotency and event ordering.
7. Present model availability and synthesis status only from actual API responses; never claim configured providers are healthy without runtime checks.
8. Preserve execution boundaries: WordPress never holds provider API keys or executes provider model calls in public PHP endpoints.
9. Add audit-safe request IDs, redacted errors, rate limits, origin allowlists and operator troubleshooting views.
10. Provide a settings screen for API base URL, integration enablement, connectivity test, supported scopes and last verified synchronization; no secret values in diagnostics.

## Commercial plan
Free, Professional and Business are proposed product tiers, not confirmed live billing products. Display only activated price IDs and documented prices from the canonical commerce authority. Do not create duplicate subscription ledgers.

## Operator dashboard
Read-only initial widgets: service health, authentication status, OLLM model availability, daily executions, provider error rate, plan distribution, usage, renewal events, conversion funnel and webhook failures. Use empty/unavailable states rather than synthetic metrics.

## Founder support
Public founder-support messaging may offer products, introductions, partnerships, professional collaboration and approved commercial support pathways. Clearly distinguish purchases, gifts and investment discussions; LLC payments must not be represented as tax-deductible charitable donations without a valid basis.

## Acceptance
- WordPress plugin activates without fatal errors and preserves existing routes.
- Existing /wp-json/onegodian/v1/platform/* and legacy routes remain compatible.
- Public /ollm renders and links correctly.
- Email and Google authentication handoff verified in hosted browsers where enabled.
- Unauthorized users cannot execute paid API calls.
- Purchase/renewal/cancel/refund changes propagate exactly once.
- No credentials leak in logs, browser storage, REST responses or redirects.
- Admin health accurately reports disconnected/degraded states.
- Privacy/cache/CORS/CSRF tests and WordPress integration tests pass.
- Human release approval and rollback plan recorded.

This is a cross-repository implementation contract, not a deployment or verification claim.
