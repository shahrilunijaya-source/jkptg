$path = 'c:\Users\User\Desktop\Claude\ClaudeCode\Website\Portal-JKPTG\PLAN.md'
$content = Get-Content $path -Raw

$newBlock = @'
## Eng Review Decisions (2026-05-06, FULL - 15 questions resolved)

### Batch 1 - Library / vendor picks

| # | Question | Decision |
|---|----------|----------|
| 1 | Translatable storage | **JSON column via `spatie/laravel-translatable`** (Scout DB driver searches via `JSON_EXTRACT` on MySQL 5.7+/MariaDB 10.2+ - Hostinger supports) |
| 2 | PDF library | **`barryvdh/laravel-dompdf`** (pure PHP, works on shared hosting, 90% fidelity adequate for legal docs) |
| 3 | Map embed | **OpenStreetMap via Leaflet.js** (zero-key, no usage limit, PDPA-clean) |
| 4 | LLM model | **Anthropic Claude Sonnet 4.6** (better BM nuance; ~3x cost vs Haiku; cap revised to RM 200 = ~$40 USD = ~7.5k chats/mo at avg 2k input + 500 output tokens) |

### Batch 2 - Architecture / scope picks

| # | Question | Decision |
|---|----------|----------|
| 5 | 2FA scope | **Defer to production** - no 2FA in prototype. Tender narrative: "production-ready hook"; sample admin login = email+password only |
| 6 | LLM monthly cost cap | **RM 200/mo (~$40 USD, ~7.5k Sonnet 4.6 chats)** - kill switch + email alert at 80%, configurable in admin |
| 7 | visit_logs growth | **Grow-and-archive at 13 mo** - single MySQL table, monthly cron archives older to CSV+drops; PDPA-clean (SPA Bil 4/2024); IP anonymized after 30 days |
| 8 | Queue driver | **`database` driver** on `jobs` table - Hostinger cron `php artisan queue:work --stop-when-empty` every minute |
| 9 | LLM driver abstraction | **Anthropic + Canned only** - `LlmDriver` interface stays open for future `OpenAiDriver`; ship 2 of 3 drivers |
| 10 | Filament version | **v3.3.x** - Laravel 11 compatible, stable plugin ecosystem, FILAMENT-PATTERNS.md tested |
| 11 | Hostinger plan tier verification | **Add Phase 0.5 verification step** - block builds until PHP 8.3 + MySQL 8 + Composer SSH + outbound HTTPS to api.anthropic.com confirmed; document plan in CONSTRAINTS.md |
| 12 | myID/IDN integration | **Disabled button + 'Demo' label** - tooltip "Memerlukan integrasi Production - JKPTG SH05/2026"; `MyIdProvider` interface scaffolded with stubbed SAML/OIDC flow |
| 13 | Audit log package | **`spatie/laravel-activitylog`** + Filament v3 activitylog plugin for timeline UI; maps to PPPA 5.2.1 audit-trail |
| 14 | LLM cost-cap reset cron | **Calendar-month reset on 1st** - Hostinger cron `0 0 1 * * php artisan llm:reset-cap` |
| 15 | Queue overflow handling | **3 retries + DLQ** - `tries=3`, `backoff=[60,300,900]`; `failed_jobs` table; Filament admin retry UI; email alert if backlog >50 |

---
'@

$pattern = '(?s)## Eng Review Decisions \(2026-05-06, partial.+?\n---\n'
$matchInfo = [regex]::Match($content, $pattern)
if ($matchInfo.Success) {
  $newContent = $content.Substring(0, $matchInfo.Index) + $newBlock + "`n" + $content.Substring($matchInfo.Index + $matchInfo.Length)
  Set-Content -Path $path -Value $newContent -NoNewline -Encoding UTF8
  Write-Host ('OK - replaced ' + $matchInfo.Length + ' chars with ' + $newBlock.Length + ' chars')
} else {
  Write-Host 'NO MATCH'
  Write-Host '--- searching for header ---'
  if ($content -match '## Eng Review Decisions') {
    Write-Host 'header found - pattern issue'
    $idx = $content.IndexOf('## Eng Review Decisions')
    Write-Host ('header starts at ' + $idx)
    Write-Host ($content.Substring($idx, 200))
  } else {
    Write-Host 'header NOT in file'
  }
}
