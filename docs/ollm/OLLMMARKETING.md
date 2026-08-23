# OLLM Marketing Page — onegodian.org/ollm

Status: Q3 2026 launch integration

## Purpose

`onegodian.org/ollm` is the canonical public marketing and conversion page for OLLM — OneGodian LLM.

The actual authenticated product lives at `https://llm.onegodian.org`.

## Required page sections

1. Hero: what OLLM is in plain English.
2. How it works: prompt → multiple models → compare → filter → normalize → governed OHI output.
3. Product screenshots or dashboard preview.
4. Core capabilities.
5. Use cases.
6. Plan comparison: Free / Professional / Business.
7. Security, privacy, and governance explanation.
8. FAQ.
9. Clear CTA to launch/sign in at `llm.onegodian.org`.
10. Legal links: Terms, Privacy, Acceptable Use, billing/refund terms where applicable.

## Public positioning

OLLM should be described as a multi-model intelligence orchestration and governed synthesis product. Raw model responses are inputs to the workflow; the final product experience is the governed synthesis layer.

Avoid representing a roadmap feature as operational. Provider availability, OpenAI-branded login, API access, enterprise controls, and other capabilities must be shown as live only after they are actually deployed and repeatable.

## Conversion hierarchy

Primary CTA: **Launch OLLM**
Secondary CTA: **View Plans**
Tertiary CTA: **See How It Works**

All authenticated-product CTAs should point to `https://llm.onegodian.org`.

## Canonical architecture

- Public discovery and conversion: OneGodian.org
- Authenticated product: LLM.OneGodian.org
- Core orchestration: `ohi-stack/onegodian-llm`
- Shared governance/control integration: OHI control plane

This boundary is intentional and should be preserved to avoid duplicating the application inside WordPress.
