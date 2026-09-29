#!/usr/bin/env bash
set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
AUDIT="$ROOT/scripts/live_site_audit.sh"
PAGE="$ROOT/wordpress/pages/member-dashboard.md"

fail() { echo "FAIL: $1" >&2; exit 1; }
contains() { grep -Fq "$1" "$2" || fail "$3 Missing: $1"; }

test -f "$AUDIT" || fail "live_site_audit.sh must exist"
test -f "$PAGE" || fail "member-dashboard.md must exist"

for marker in "/member-dashboard/" "/my-account/" "/onegodian-101/" "onegodian_members_dashboard" "onegodian_member_dashboard" "Login Details"; do
  contains "$marker" "$AUDIT" "Live-site audit must verify the member access surface."
done

for marker in "Canonical shortcode" "[onegodian_member_dashboard]" "[onegodian_members_dashboard]" "Login Details" "Username or email address" "Forgot password?" "Create Account" "Explore Membership"; do
  contains "$marker" "$PAGE" "Member Dashboard page contract is incomplete."
done

echo "PASS: OneGodian.org member access contract is defined."
