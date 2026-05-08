$path = 'c:\Users\User\Desktop\Claude\ClaudeCode\Website\Portal-JKPTG\STATE.md'
$content = Get-Content $path -Raw -Encoding UTF8

$olds = @()
$news = @()

$olds += '**Current stage:** Phase 3 COMPLETE (Stage 7 in progress). 13 content tables migrated + seeded with JKPTG data. Ready for Phase 4 layouts.'
$news += '**Current stage:** Phase 4 COMPLETE (Stage 7 in progress). Public layouts + BM/EN toggle live. Ready for Phase 4.5 / Phase 5.'

$olds += '**Last updated:** 2026-05-06 (Phase 3 schema + seeders done)'
$news += '**Last updated:** 2026-05-06 (Phase 4 layouts + i18n toggle done)'

$olds += '  - [ ] Phase 4 - Public layouts (header, megamenu, footer, accessibility, BM/EN) <- NEXT'
$news += '  - [x] Phase 4 - Public layouts (Tailwind+Vite, master layout, 6 partials, SetLocale middleware, BM/EN toggle verified)' + [char]10 + '  - [ ] Phase 4.5 - Interaction state matrix <- NEXT'

$olds += '  - [ ] Phase 4.5 - Interaction state matrix'
$news += ''

# Add Phase 4 deliverables block before "Stage 7 - Resume Plan"
$old5 = '## Stage 7 - Resume Plan'
$new5 = @'
## Phase 4 - Deliverables (LOCKED 2026-05-06)

| Component | Detail |
|-----------|--------|
| `tailwind.config.js` | DESIGN.md tokens (primary #243D57, Inter+Poppins, focus-ring shadow, jata accents) |
| `postcss.config.js` | tailwindcss + autoprefixer |
| `resources/css/app.css` | Google Fonts import + base layer + component utilities + print stylesheet |
| `layouts/public.blade.php` | Master layout, lang attr, skip-link, Alpine a11y state, @vite + @livewire* |
| 6 partials | utility-bar, header, megamenu, accessibility-panel, footer, chatbot-bubble |
| `SetLocale` middleware | cookie + Accept-Language fallback, registered web middleware |
| `LocaleController` | /locale/{ms\|en} sets cookie, redirects back |
| `lang/ms` + `lang/en` | 50+ translation keys |
| `home.blade.php` | placeholder hero + 6 service tiles (Phase 5 will expand) |
| Vite build | manifest.json + 114 KB CSS + 42 KB JS shipped to public/build |

Smoke test verified:
- / -> 200 with JATA, megamenu, footer, skip-link, services from DB rendered
- /locale/en -> 302 with jkptg_locale cookie
- subsequent / with cookie -> HOME/SERVICES nav, EN tagline, lang="en"
- BM (default) -> UTAMA/PERKHIDMATAN nav, BM tagline, lang="ms"

## Phase 4.5 / Phase 5 - Resume Plan

Phase 4.5 - Interaction state matrix:
- Loading / empty / error / success states for every Livewire component
- 16 features documented in PLAN.md Phase 4.5

Phase 5 - Homepage + persona landings:
- Replace home.blade.php placeholder with Stage 5 variant A (overlay hero + 3 floating persona doors)
- Build persona-landing template (variant A: classic hero + 3-col grid + sidebar)
- Reuse partials, expand featured news + agency carousel sections

## Stage 7 - Resume Plan
'@
$olds += $old5
$news += $new5

# Files state - inject phase 4 row
$oldRow = '| `portal-jkptg/database/seeders/ContentSeeder.php` | 64 rows seeded | Phase 3 |'
$newRow = '| `portal-jkptg/database/seeders/ContentSeeder.php` | 64 rows seeded | Phase 3 |' + [char]10 + '| `portal-jkptg/tailwind.config.js` + `postcss.config.js` | done | Phase 4 |' + [char]10 + '| `portal-jkptg/resources/views/layouts/public.blade.php` | master layout | Phase 4 |' + [char]10 + '| `portal-jkptg/resources/views/partials/*.blade.php` | 6 partials | Phase 4 |' + [char]10 + '| `portal-jkptg/app/Http/Middleware/SetLocale.php` + `LocaleController.php` | locale wiring | Phase 4 |' + [char]10 + '| `portal-jkptg/lang/{ms,en}/messages.php` | 50+ keys | Phase 4 |'
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
