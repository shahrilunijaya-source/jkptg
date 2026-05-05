$path = 'c:\Users\User\Desktop\Claude\ClaudeCode\Website\Portal-JKPTG\STATE.md'
$content = Get-Content $path -Raw -Encoding UTF8

$olds = @()
$news = @()

$olds += '**Current stage:** Phase 5 COMPLETE (Stage 7 in progress). Homepage + 3 persona landings live with Stage 5 variants. Ready for Phase 6.'
$news += '**Current stage:** Phase 6 COMPLETE (Stage 7 in progress). Service/Borang/Hubungi/Korporat/Sumber pages live. Ready for Phase 7.'

$olds += '**Last updated:** 2026-05-06 (Phase 5 homepage + personas done)'
$news += '**Last updated:** 2026-05-06 (Phase 6 internal pages done)'

$olds += '  - [ ] Phase 6 - Service/Korporat/Sumber/Hubungi pages <- NEXT'
$news += '  - [x] Phase 6 - Service detail (variant B sticky-nav) + Borang library + Hubungi (Leaflet) + Korporat + Sumber' + [char]10 + '  - [ ] Phase 7 - Filament admin resources <- NEXT'

$olds += '  - [ ] Phase 7 - Filament admin resources'
$news += ''

$old5 = '## Phase 6 - Resume Plan'
$new5 = @'
## Phase 6 - Deliverables (LOCKED 2026-05-06)

| Route | Controller | View |
|-------|-----------|------|
| `/perkhidmatan` | ServiceController@index | perkhidmatan/index.blade.php |
| `/perkhidmatan/{slug}` | ServiceController@show | perkhidmatan/show.blade.php (Stage 5 variant B sticky-nav) |
| `/panduan/borang` | BorangController@index | panduan/borang.blade.php (q + cat filter) |
| `/korporat` | PageController@korporat | korporat/index.blade.php |
| `/halaman/{slug}` | PageController@show | pages/show.blade.php (generic renderer) |
| `/sumber` | PageController@sumber | sumber/index.blade.php |
| `/hubungi` | HubungiController@index | hubungi/index.blade.php (Leaflet + OpenStreetMap) |

Reusable Blade component: `x-breadcrumb` (DRY across pages).

Service detail variant B features:
- Sticky 240px left rail with anchor nav (Tentang/Kelayakan/Proses/Dokumen/Borang/FAQ)
- IntersectionObserver active-state on scroll
- Sticky bottom-right Mohon Sekarang CTA
- Related forms + FAQs filtered by slug-root prefix (handles category schema drift)
- Mobile: details disclosure for nav, full-width content

Lang keys added: 60 (service.*, borang.*, korporat.*, sumber.*, hubungi.*).

Smoke test: 10 routes verified 200 + Leaflet CSS/JS + OpenStreetMap tiles loaded + service detail FAQs render.

## Phase 7 - Resume Plan
'@
$olds += $old5
$news += $new5

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
