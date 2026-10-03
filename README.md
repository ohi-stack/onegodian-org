# OneGodian.org

Canonical **ORGANIZATION** property of the interconnected OneGodian Platform.

## Canonical role

`https://OneGodian.org` is the public identity, mission, history, membership, records, educational-explanation, and institutional/public-information home for OneGodian.

It is **not** the central technical application, commerce authority, University runtime, finance platform, or protocol runtime.

## Current OneGodian Platform

| Layer | Canonical property | Primary role |
|---|---|---|
| ORGANIZATION | `https://OneGodian.org` | Public identity, mission, history, membership, records, educational explanations, institutional/public information |
| STORE | `https://OneGodian.com` | Products, digital downloads, merchandise, certificates, services, campaigns, member purchases |
| EDUCATION | `https://u.OneGodian.com` | University, courses, lessons, learning paths, books, OneGodianese Dictionary, digital e-learning products, student merchandise, onboarding, training certificates |
| GALAXY | `https://galaxy.OneGodian.com` | Galaxy map, planets, world stores, lore, characters, media, planetary navigation |
| CAPITAL | `https://ODeFi.OneGodian.com` | ONEGODIAN, LLC finance materials, capital strategy, disclosure center, contributor information, approved financial-platform functions |
| PROTOCOL | `https://OMOS.OneGodian.com` | OMOS protocol/specification, alignment tools, developer documentation, integrations, API framework |
| SHARED PLATFORM CORE | `https://api.OneGodian.org` | Cross-site API, authentication, connectors, adapters, MCP, synchronization, analytics, valuation, webhooks, registries, shared plugin services |

## Architecture rule

The former standalone **OneGodian App / `app.onegodian.com` is retired**. It must not be treated as an active platform, deployment target, navigation destination, or architectural dependency.

The product is now the **interconnected OneGodian Platform**.

```text
ONEGODIAN, LLC
      ↓
OneGodian Platform
      ↓
api.OneGodian.org
      ↓
Plugins + Connections + Adapters + MCP
      ↓
Specialized OneGodian Properties
```

## Repository responsibility

This repository supports the public OneGodian.org experience and its deployment/integration contracts. Specialized runtimes remain authoritative in their own repositories.

OneGodian Members is a standalone membership runtime. OneGodian University LMS owns formal learning. OneGodian.com owns store/commerce presentation. ODeFi owns the active finance platform. OMOS owns the protocol/developer layer. Cross-property synchronization belongs through `api.OneGodian.org`.

## Audit workflow

```bash
./scripts/live_site_audit.sh
./scripts/live_site_audit.sh https://staging.example.org
```

## Production discipline

Repository documentation or code does not by itself prove live production deployment. Public status must be based on verified runtime evidence.
