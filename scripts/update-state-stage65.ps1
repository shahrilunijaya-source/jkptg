$path = 'c:\Users\User\Desktop\Claude\ClaudeCode\Website\Portal-JKPTG\STATE.md'
$content = Get-Content $path -Raw -Encoding UTF8

$olds = @()
$news = @()

$olds += '**Current stage:** Stage 6 COMPLETE. HTML mockup built. Awaiting approval before Stage 6.5 Laravel scaffold.'
$news += '**Current stage:** Stage 6.5 COMPLETE. Laravel app scaffolded. /admin/login works. Ready for Stage 7 build phases.'

$olds += '**Last updated:** 2026-05-06 (Stage 6 mockup built)'
$news += '**Last updated:** 2026-05-06 (Stage 6.5 Laravel scaffold + auth done)'

$olds += '- [ ] Stage 6.5 - Auth + Filament admin scaffold <- NEXT'
$news += '- [x] Stage 6.5 - Auth + Filament admin scaffold  `portal-jkptg/` (4 sample users, /admin works)'

$olds += '- [ ] Stage 7 - Build (Laravel app, prototype deliverable)'
$news += '- [ ] Stage 7 - Build (Phase 3 schema + content seeders -> Phase 14 deploy) <- NEXT'

# Replace Stage 6.5 resume plan
$old5 = @'
## Stage 6.5 - Resume Plan

After user approves mockup:

1. Run Phase 0.5 Hostinger plan tier verification (PHP 8.3 + MySQL 8 + Composer SSH + api.anthropic.com)
2. `composer create-project laravel/laravel:^11 portal-jkptg`
3. git init, .gitignore, GitHub repo
4. Install: livewire 3, filament v3.3, spatie permission, spatie translatable, spatie activitylog, scout, dompdf
5. User model implements `FilamentUser` (LESSONS rule 3)
6. Seed 4 sample users (super-admin/editor/viewer/citizen @jkptg.demo)
7. Verify `/admin/login` works
'@

$new5 = @'
## Stage 6.5 - Scaffold deliverables (LOCKED 2026-05-06)

| Component | Status |
|-----------|--------|
| `portal-jkptg/` Laravel 11.x app | scaffolded |
| Filament v3.3.50 + Livewire 3.8.0 | installed |
| spatie/laravel-permission 6.25 | installed + migrated |
| spatie/laravel-translatable 6.14 + filament plugin | installed |
| spatie/laravel-activitylog 4.12 | installed + migrated |
| laravel/scout 11.1 | installed (driver=database) |
| barryvdh/laravel-dompdf 3.1 | installed |
| MySQL `portal_jkptg` database | created (Laragon mysql 8.4.3) |
| `.env` configured | APP_LOCALE=ms, queue=database, scout=database |
| `User` implements `FilamentUser` + `HasRoles` + `LogsActivity` | done |
| `canAccessPanel(Panel $panel): bool` | implemented (super-admin/editor/viewer allowed) |
| 4 sample users seeded | admin/editor/viewer/citizen @jkptg.demo, pwd `password` |
| `/admin/login` returns 200 | verified |
| Auth + role gates verified via verify-auth.php | all 4 users pass |
| git init at root + .gitignore + initial commit | done (commit dca86e9) |

Local dev:
- `cd portal-jkptg && php artisan serve` -> http://127.0.0.1:8000
- `/admin/login` -> log in with admin@jkptg.demo / password

## Stage 7 - Resume Plan

Run Phase 3+ build phases per `PLAN.md`:

1. Phase 3 - DB schema + content seeders (pages, services, news, forms, KB, settings)
2. Phase 4 - Public layouts (header, megamenu, footer, accessibility, BM/EN toggle)
3. Phase 4.5 - Interaction state matrix
4. Phase 5 - Homepage + persona landings (Stage 5 variants applied)
5. Phase 6 - Service + Korporat + Sumber + Hubungi pages
6. Phase 7 - Filament admin resources
7. Phase 8 - Filament dashboard + audit log
8. Phase 9 - Chatbot (Anthropic Sonnet 4.6 + Canned fallback)
9. Phase 10 - i18n polish (BM/EN parallel)
10. Phase 11 - Search (Scout DB driver)
11. Phase 12 - Edge cases (legacy redirects, SPLaSK, print, accessibility)
12. Phase 13 - Verification + acceptance testing
13. Phase 14 - Hostinger deploy + Phase 0.5 verification first
14. Phase 14.5 - Walkthrough video

Sequence per `PLAN.md` Phase Dependency Graph.
'@

$olds += $old5
$news += $new5

# Files state - inject portal-jkptg row
$oldRow = '| `mockup/README.md` | done 2026-05-06 | 6 |'
$newRow = '| `mockup/README.md` | done 2026-05-06 | 6 |' + [char]10 + '| `portal-jkptg/` | scaffolded 2026-05-06 (commit dca86e9) | 6.5 |' + [char]10 + '| `.gitignore` | done 2026-05-06 | 6.5 |'
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
