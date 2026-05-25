# WordPress Plugin Bridge Standard

Production target: `onegodian.org`

## Purpose

This repository supports the public-facing educational, archival, cultural, and institutional presentation layer for the OneGodian ecosystem.

## Plugin Bridge Objectives

Plugins should synchronize public-safe information between:

- OMOS,
- OneGodian App,
- QRV,
- memberships,
- WooCommerce,
- LMS systems,
- and verification services.

## Required Plugin Capabilities

- manifest ingestion
- ecosystem cards/widgets
- verification embeds
- certificate embeds
- timeline embeds
- protocol documentation embeds
- public API integrations
- shortcode support
- Gutenberg compatibility
- WPBakery compatibility

## Required Public Pages

- About OneGodian
- History & Chronology
- OTS-V5
- OMOS
- Protocol
- Verification
- Contributors
- Documentation
- Contact

## Security Rules

Plugins must never expose:

- deployment secrets,
- privileged controls,
- internal ACC execution,
- private member records,
- financial credentials,
- server internals.

## Production Rule

All plugin features must:

- fail safely,
- degrade gracefully,
- remain documented,
- and remain modular.

Scripts are disposable. Infrastructure compounds.
