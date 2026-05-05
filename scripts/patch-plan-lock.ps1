$path = 'c:\Users\User\Desktop\Claude\ClaudeCode\Website\Portal-JKPTG\PLAN.md'
$content = Get-Content $path -Raw -Encoding UTF8

# Append final summary + lock stamp at end of file
$append = @'


---

## Stage 4 Final Lock (2026-05-06)

PLAN.md is **LOCKED**. Three reviews complete:

| Review | Date | Outcome |
|--------|------|---------|
| `/plan-ceo-review` | 2026-05-06 | SELECTIVE EXPANSION - real LLM (Sonnet 4.6), real visit tracking, walkthrough video accepted; SITE-AUDIT.pdf and Lighthouse CI deferred |
| `/plan-design-review` | 2026-05-06 | 7/10 -> 9/10 - added Phase 4.5 interaction state matrix, expanded Phase 5.2 persona-landing component, locked megamenu click-only (overrides SPEC Q4B) |
| `/plan-eng-review` | 2026-05-06 | 15 questions resolved across 2 batches - all picks documented in Eng Review Decisions section above |

### Stage 4 deliverables

- [x] PLAN.md drafted (14 phases + Phase 0/0.5/4.5/5.2.1/14.5)
- [x] CEO review applied
- [x] Design review applied
- [x] Eng review applied (Batch 1 + Batch 2)
- [x] Architecture critiques addressed (cost-cap cron Q14, queue overflow Q15)

### What changes vs initial draft

1. Phase 0.5 NEW - Hostinger plan tier verification (Eng Q11) - blocker checks before Phase 1
2. Phase 1 #17 SKIPPED - 2FA package install removed (Eng Q5: defer to production)
3. Phase 9.1 - Sonnet 4.6 model (Eng Q4), RM 200 cost cap (Eng Q6), Anthropic + Canned drivers only (Eng Q9)
4. Phase 9 cost-cap reset cron added (Eng Q14)
5. Phase 12.6 - grow-and-archive at 13mo (Eng Q7), 3-retry DLQ + email at 50 (Eng Q15)
6. Phase 0.9 - "no external LLM" anti-pattern OVERRIDDEN (CEO + Eng Q9 ship real Anthropic)

### Next stage

**Stage 5 - Visual Variants** (`/design-shotgun` capped at 3 variants per page per LESSONS rule 1).

Pages needing variants: homepage hero, persona landing, service detail, megamenu open state. Pick winners then proceed to Stage 6 HTML mockup.
'@

# Only append if lock stamp not present yet
if ($content.Contains('Stage 4 Final Lock')) {
  Write-Host 'Already locked - skipping append'
} else {
  $content = $content.TrimEnd() + $append + [char]10
  Set-Content -Path $path -Value $content -NoNewline -Encoding UTF8
  Write-Host 'OK - lock stamp appended'
}
