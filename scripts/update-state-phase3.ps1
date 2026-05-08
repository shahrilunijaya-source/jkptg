$path = 'c:\Users\User\Desktop\Claude\ClaudeCode\Website\Portal-JKPTG\STATE.md'
$content = Get-Content $path -Raw -Encoding UTF8

$olds = @()
$news = @()

$olds += '**Current stage:** Stage 6.5 COMPLETE. Laravel app scaffolded. /admin/login works. Ready for Stage 7 build phases.'
$news += '**Current stage:** Phase 3 COMPLETE (Stage 7 in progress). 13 content tables migrated + seeded with JKPTG data. Ready for Phase 4 layouts.'

$olds += '**Last updated:** 2026-05-06 (Stage 6.5 Laravel scaffold + auth done)'
$news += '**Last updated:** 2026-05-06 (Phase 3 schema + seeders done)'

$olds += '- [ ] Stage 7 - Build (Phase 3 schema + content seeders -> Phase 14 deploy) <- NEXT'
$news += '- [~] Stage 7 - Build IN PROGRESS' + [char]10 + '  - [x] Phase 3 - DB schema + content seeders (13 migrations, 15 models, 64 seeded rows)' + [char]10 + '  - [ ] Phase 4 - Public layouts (header, megamenu, footer, accessibility, BM/EN) <- NEXT' + [char]10 + '  - [ ] Phase 4.5 - Interaction state matrix' + [char]10 + '  - [ ] Phase 5 - Homepage + persona landings' + [char]10 + '  - [ ] Phase 6 - Service/Korporat/Sumber/Hubungi pages' + [char]10 + '  - [ ] Phase 7 - Filament admin resources' + [char]10 + '  - [ ] Phase 8 - Filament dashboard + audit log' + [char]10 + '  - [ ] Phase 9 - Chatbot' + [char]10 + '  - [ ] Phase 10-12 - i18n, search, edge cases' + [char]10 + '  - [ ] Phase 13 - Verification' + [char]10 + '  - [ ] Phase 14 - Hostinger deploy' + [char]10 + '  - [ ] Phase 14.5 - Walkthrough video'

# Add Phase 3 deliverables block after Stage 6.5 deliverables
$old5 = '## Stage 7 - Resume Plan'
$new5 = @'
## Phase 3 - Deliverables (LOCKED 2026-05-06)

| Component | Detail |
|-----------|--------|
| 13 migrations | pages, services, news, tenders, forms, faqs, cawangan, chatbot_knowledge, chatbot_quick_replies, chat_sessions+messages, chatbot_settings, settings, visit_logs, llm_api_logs |
| 15 models | HasTranslations on 9, Searchable on 4 (Scout DB), LogsActivity on auditable, encrypted cast on ChatMessage |
| ContentSeeder | 12 pages + 6 services + 5 news + 3 tenders + 5 forms + 8 FAQs + 4 cawangan + 6 KB + 4 quick replies + 1 chatbot_settings + 10 settings = 64 rows |
| All BM+EN parallel | verified via verify-content.php locale switch |
| chatbot_settings singleton | driver=canned, cap=RM200, alert=80%, model=claude-sonnet-4-6, cap_reset_at=next month start |
| Sample data | matches Stage 5 mockup (6 services Pengambilan/Pusaka/Pajakan/Penyewaan/Lesen/Strata, 4 cawangan HQ+Selangor+Penang+Johor) |

Commit: see git log

## Phase 4 - Resume Plan

Build public layouts:

1. `resources/views/layouts/public.blade.php` - master layout (header + megamenu + footer + accessibility panel + chatbot bubble)
2. Livewire components: `Header`, `Megamenu`, `Footer`, `AccessibilityPanel`, `LangSwitcher`, `BreadcrumbBar`
3. BM/EN toggle middleware (cookie-driven, default=ms per .env APP_LOCALE)
4. Skip-to-content link, lang attribute, focus rings (WCAG 2.1 AA)
5. Tailwind config matching DESIGN.md tokens (#243D57 primary, Inter+Poppins via Vite)

Use mockup/index.html as visual reference.

## Stage 7 - Resume Plan
'@
$olds += $old5
$news += $new5

# Files state - inject phase 3 row
$oldRow = '| `.gitignore` | done 2026-05-06 | 6.5 |'
$newRow = '| `.gitignore` | done 2026-05-06 | 6.5 |' + [char]10 + '| `portal-jkptg/database/migrations/2026_05_06_18*` | 13 content migrations | Phase 3 |' + [char]10 + '| `portal-jkptg/app/Models/*.php` | 15 Eloquent models | Phase 3 |' + [char]10 + '| `portal-jkptg/database/seeders/ContentSeeder.php` | 64 rows seeded | Phase 3 |'
$olds += $oldRow
$news += $newRow

$replaced = 0
for ($i = 0; $i -lt $olds.Length; $i++) {
  $o = $olds[$i] -replace "`r`n", "`n"
  $n = $news[$i] -replace "`r`n", "`n"
  $cNorm = $content -replace "`r`n", "`n"
  if ($cNorm.Contains($o)) {
    $content = $cNorm.Replace($o, $n)
    $replaced++
    Write-Host ('OK[' + ($i+1) + ']')
  } else {
    Write-Host ('MISS[' + ($i+1) + '] ' + $o.Substring(0, [Math]::Min(70, $o.Length)))
  }
}

Set-Content -Path $path -Value $content -NoNewline -Encoding UTF8
Write-Host ('Done. ' + $replaced + '/' + $olds.Length)
