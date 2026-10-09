# Operations Runbook

## Purpose
Baseline operating procedures for Onegodian.org.

## Daily Checks
- Run `./scripts/live_site_audit.sh`
- Confirm homepage, store, membership, contact pages return 200
- Review broken links and security header output
- Confirm checkout and lead capture flows manually

## Weekly Checks
- Review plugin/theme updates in WordPress admin
- Verify backups exist and can be restored
- Review analytics trends: traffic, leads, purchases
- Test mobile homepage experience

## Incident Priorities
1. Site down / checkout broken
2. Login or membership failures
3. Security exposure
4. Broken core navigation
5. Cosmetic issues

## Change Management
- Use GitHub PRs for docs/scripts changes
- Test changes on staging when possible
- Keep rollback notes for production edits

## Growth Metrics
- Homepage CTA clicks
- Email signups
- Membership starts
- Store purchases
- Repeat visitors
