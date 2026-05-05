$path = 'c:\Users\User\Desktop\Claude\ClaudeCode\Website\Portal-JKPTG\STATE.md'
$content = Get-Content $path -Raw -Encoding UTF8

$olds = @()
$news = @()

$olds += '**Current stage:** Phase 4.5 COMPLETE (Stage 7 in progress). State catalog + 16-feature contract live. Ready for Phase 5.'
$news += '**Current stage:** Phase 5 COMPLETE (Stage 7 in progress). Homepage + 3 persona landings live with Stage 5 variants. Ready for Phase 6.'

$olds += '**Last updated:** 2026-05-06 (Phase 4.5 state matrix done)'
$news += '**Last updated:** 2026-05-06 (Phase 5 homepage + personas done)'

$olds += '  - [ ] Phase 5 - Homepage + persona landings <- NEXT'
$news += '  - [x] Phase 5 - Homepage + persona landings (hero overlay variant A, classic persona variant A, /untuk/{persona} route)' + [char]10 + '  - [ ] Phase 6 - Service/Korporat/Sumber/Hubungi pages <- NEXT'

$olds += '  - [ ] Phase 6 - Service/Korporat/Sumber/Hubungi pages'
$news += ''

$old5 = '## Phase 5 - Resume Plan'
$new5 = @'
## Phase 5 - Deliverables (LOCKED 2026-05-06)

| Component | Detail |
|-----------|--------|
| `resources/views/home.blade.php` | Hero overlay (variant A) + 3 persona doors + 6 service tiles + 3-tab news + agency strip |
| `resources/views/persona/show.blade.php` | Classic landing (variant A) - breadcrumb + hero with search + 3-col grid + sticky sidebar |
| `App\Http\Controllers\PersonaController` | show() - 3 personas (orang-awam, kementerian-jabatan, warga-jkptg), category-filtered services, 404 on invalid slug |
| `routes/web.php` | /untuk/{persona} regex-constrained route -> persona.show |
| `lang/{ms,en}/messages.php` | added home.* (8 keys) + persona.* (20 keys) |

Smoke test: 4 routes verified (200) + invalid persona returns 404. Hero scrim CSS gradient applied. News tabs with Alpine x-data. State components (x-state.empty) fallback.

## Phase 6 - Resume Plan
'@
$olds += $old5
$news += $new5

$oldRow = '| `STATE-MATRIX.md` | full 16-feature contract | Phase 4.5 |'
$newRow = '| `STATE-MATRIX.md` | full 16-feature contract | Phase 4.5 |' + [char]10 + '| `portal-jkptg/resources/views/home.blade.php` | Stage 5 hero variant A | Phase 5 |' + [char]10 + '| `portal-jkptg/resources/views/persona/show.blade.php` | Stage 5 persona variant A | Phase 5 |' + [char]10 + '| `portal-jkptg/app/Http/Controllers/PersonaController.php` | 3 personas, category-filtered | Phase 5 |'
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
