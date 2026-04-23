# Onegodian.org

Core platform for the Onegodian ecosystem.

## Overview

Onegodian.org serves as the primary interface for users, agents, and systems interacting with the Onegodian platform.

It provides:
- Content and publishing
- Commerce (courses, digital products)
- User interaction layer
- Integration with AI agents and OHI Twin systems

## Architecture

This repository represents the platform layer and connects to:

- onegodian-api (backend services)
- onegodian-agent-gateway (agent access)
- u-onegodian-org (education system)
- acc-agent-command-console (control interface)

## Role in Ecosystem

Onegodian.org is responsible for:
- Human-facing experience
- Entry point for agents
- Content distribution
- Monetization layer

## Current state (as of 2026-04-23)

- The repository currently contains primarily platform documentation and placeholders.
- The live production site (`https://onegodian.org`) is a WordPress/WooCommerce deployment.
- Production-readiness findings and recommendations are documented in `docs/audits/2026-04-23-production-readiness-audit.md`.

## Audit workflow

```bash
# run a repeatable live-site audit against production
./scripts/live_site_audit.sh

# optionally target another environment/base URL
./scripts/live_site_audit.sh https://staging.example.org
```

## Future Integrations

- OHI Twin interaction
- Agent-based transactions
- API-driven workflows

## Organization

Part of the Onegodian ecosystem.
