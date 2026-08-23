# Gen Alpha / Gen Beta Belief Mapper Web Strategy

Updated: August 23, 2026
Status: Prototype / pre-production

## Purpose

OneGodian.org should introduce the Belief Mapper as a short, voluntary educational experience that continues the public explanation of OneGodian without turning the website into a profiling or conversion system.

The working application surface is maintained in `ohi-stack/onegodian-app` at `/belief-mapper`. OneGodian.org should provide the public explanation, trust context, and CTA into that experience.

## Landing Page Structure

1. Hero — `What do you believe at your core?`
2. Micro-explainer — five questions, about ten seconds, no account required
3. Primary CTA — `Try the Belief Mapper`
4. What the Mapper does — compares answers with OneGodian concepts
5. What it does not do — does not assign a religion, identity, membership, or legal status
6. How results work — Explorer / Aligned / Strong Alignment
7. Privacy block — answers are not used for advertising or behavioral targeting
8. Learn section — plain-language explanation of OneGodian
9. Optional next steps — Learn / Join separately / Explore courses / Return to OneGodian.org

## Youth-Facing Messaging

Preferred hooks:

- `What if your belief had a clearer name?`
- `Five questions. Ten seconds. Your choice.`
- `Explore what you believe — without being labeled.`

Avoid claims such as:

- `You are already OneGodian` based only on a score;
- automatic identity assignment;
- pressure-based conversion language;
- claims that the Mapper replaces a user's existing tradition;
- gamification that rewards a particular belief answer.

## Design Direction

- mobile-first
- high-contrast dark navy / black foundation
- premium gold and restrained purple accents
- large type and large tap targets
- one concept per screen
- short animation transitions
- no cluttered institutional text in the interactive flow
- full legal/privacy explanation available outside the question sequence

## Funnel Boundary

`OneGodian.org explainer → app.onegodian.com/belief-mapper → educational result → optional learning path`

Formal membership, paid products, community participation, and any INO governance process must remain separate affirmative actions.

## Data Rule

The Lite Mapper should collect no name, email, phone number, location, birthday, or account identifier to calculate a result. If analytics are used, they should measure aggregate page/session performance without retaining the user's belief-answer vector.

## Production Definition of Done

This experience is not production-ready until:

- the app route passes build and accessibility checks;
- the API/scoring contract is versioned and tested;
- the website CTA and privacy copy are deployed;
- child/youth privacy obligations have been reviewed before any persistent account or personal-data feature is enabled;
- analytics are configured to avoid sensitive answer capture;
- the experience is documented and repeatably deployable.