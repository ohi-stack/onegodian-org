# Onegodian Members 0.1.0 → 1.0 Operations Plan

## Current State (April 23, 2026)

The plugin is live and active as **Onegodian Members v0.1.0** with core scope around:
- member dashboard
- WooCommerce entitlements
- protected resources
- tier-based access

This confirms the product has moved from idea phase to operating asset phase.

## Objective

Shift execution from plugin invention to plugin operations:
1. audit and stabilize
2. optimize conversion and retention
3. monetize aggressively
4. scale with measurable business telemetry

---

## Phase 1: Production Audit (Immediate)

### 1) Functional Audit

Validate end-to-end flows in a staging clone and production:
- registration and email verification
- login/logout + session persistence
- password reset and account recovery
- member dashboard data quality and usefulness
- tier access enforcement for all protected pages/assets
- WooCommerce entitlement sync (new orders, refunds, cancellations)
- Stripe recurring billing webhooks (renewal, payment failure, cancellation)
- lifecycle email automation triggers and delivery

**Exit criteria:** every critical member flow passes with test evidence.

### 2) Technical Audit

Check for reliability, performance, and security:
- PHP warnings/notices/fatal errors in logs
- SQL query performance and index opportunities
- nonce/capability checks on all mutating actions
- input sanitization + output escaping in templates/admin pages
- mobile responsiveness for member dashboard and checkout
- theme compatibility with active production theme
- plugin compatibility with cache/CDN/minification layers
- webhook idempotency and retry safety

**Exit criteria:** no critical or high issues remain open.

### 3) Revenue Audit

Assess whether monetization plumbing exists and is trackable:
- pricing page clarity and plan differentiation
- free-to-paid conversion path
- in-app upsell prompts from lower tiers
- checkout abandonment recovery
- churn prevention and win-back campaigns
- coupon strategy and limited-time offers

**Exit criteria:** at least one measurable funnel from visitor → trial → paid.

---

## Phase 2: Version 1.0 Scope

### Revenue Features (Required)
- recurring subscriptions with dunning and retries
- free/low-friction trial offers
- contextual upgrade prompts in dashboard/resources
- referral rewards program
- member-only coupons and periodic offers

### UX Features (Required)
- polished, role-aware dashboard
- profile completion/progress meter
- achievement badges for usage milestones
- personalized resource recommendations

### Business Intelligence Features (Required)
- MRR dashboard (daily/weekly/monthly)
- churn and retention cohorts
- active member counts by tier
- LTV and payback visibility per acquisition channel

**Definition of Done for 1.0:**
- all 1.0 features above shipped
- analytics events validated
- support runbook and rollback plan documented

---

## Monetization Architecture

### Recommended Plan Ladder
- **Free**: lead capture + preview access
- **Supporter ($12/mo)**: premium content + member discounts
- **Builder ($29/mo)**: courses + downloads + implementation tools
- **Founder ($99/mo)**: direct access, premium network, concierge layer

### Critical Funnel Layers
1. conversion funnel (landing → pricing → checkout)
2. onboarding sequence (first 7 days activation)
3. retention system (weekly value + milestone nudges)
4. upsell ladder (Supporter → Builder → Founder)

---

## This Week Execution Checklist

### Day 1–2: Funnel Surface Area
Create or validate key pages:
- `/join`
- `/pricing`
- `/dashboard`
- `/resources`
- `/founder-circle`

### Day 2–3: Billing Hardening
- finalize Stripe subscription lifecycle handling
- verify retries, payment-failure handling, cancellation and reactivation
- ensure webhook audit trail is queryable

### Day 3–4: Protected Value
- publish high-value protected assets
- map each asset to tier intent (acquire, retain, upsell)

### Day 4–5: Email Automation
Launch lifecycle sequence:
- welcome
- quick win/value
- upgrade prompt
- churn-risk intervention

---

## KPI Targets (First 90 Days)

Track weekly and monthly:
- visitor → signup conversion rate
- signup → paid conversion rate
- paid member churn rate
- MRR net growth
- ARPU by tier
- upgrade rate between tiers
- email-driven revenue contribution

Suggested baseline targets (adjust after first 2 weeks of real data):
- churn under 8% monthly for paid tiers
- signup → paid conversion over 5%
- month-over-month MRR growth over 12%

---

## Operating Principle

Treat Onegodian Members as a **cash-flow engine** with product discipline:
- ship value weekly
- instrument everything
- optimize bottlenecks continuously
- prioritize retention as strongly as acquisition

The strategic advantage is ownership of audience, recurring revenue, and community moat.
