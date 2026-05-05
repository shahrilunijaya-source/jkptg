$path = 'c:\Users\User\Desktop\Claude\ClaudeCode\Website\Portal-JKPTG\STATE.md'
$content = Get-Content $path -Raw -Encoding UTF8

$olds = @()
$news = @()

$olds += '**Current stage:** Phase 4 COMPLETE (Stage 7 in progress). Public layouts + BM/EN toggle live. Ready for Phase 4.5 / Phase 5.'
$news += '**Current stage:** Phase 4.5 COMPLETE (Stage 7 in progress). State catalog + 16-feature contract live. Ready for Phase 5.'

$olds += '**Last updated:** 2026-05-06 (Phase 4 layouts + i18n toggle done)'
$news += '**Last updated:** 2026-05-06 (Phase 4.5 state matrix done)'

$olds += '  - [ ] Phase 4.5 - Interaction state matrix <- NEXT'
$news += '  - [x] Phase 4.5 - Interaction state matrix (6 Blade components, 16 demos at /states, STATE-MATRIX.md)' + [char]10 + '  - [ ] Phase 5 - Homepage + persona landings <- NEXT'

$olds += '  - [ ] Phase 5 - Homepage + persona landings'
$news += ''

$old5 = '## Phase 4.5 / Phase 5 - Resume Plan'
$new5 = @'
## Phase 4.5 - Deliverables (LOCKED 2026-05-06)

| Component | Detail |
|-----------|--------|
| 6 reusable Blade components | skeleton-row, skeleton-card, empty, error, loading, toast |
| 28 BM+EN translation keys | under `messages.states.*` namespace |
| `/states` demo route | 16 visual examples, all WCAG roles applied |
| `STATE-MATRIX.md` at project root | full 16-feature contract for Phases 5-12 |

States covered: loading skeletons, empty (3 tones), error (with retry + support email), inline spinner (3 sizes), toast (4 tones, auto-close), chatbot greeting + typing dots + simple-mode badge, login inline error, search empty with popular suggestions, /akaun first-visit, dashboard skeleton bars.

All components use `role="status"`, `aria-live`, `aria-pressed`, `aria-invalid` per WCAG 2.1 AA.

## Phase 5 - Resume Plan
'@
$olds += $old5
$news += $new5

$oldRow = '| `portal-jkptg/lang/{ms,en}/messages.php` | 50+ keys | Phase 4 |'
$newRow = '| `portal-jkptg/lang/{ms,en}/messages.php` | 50+ keys | Phase 4 |' + [char]10 + '| `portal-jkptg/resources/views/components/state/*.blade.php` | 6 reusable state components | Phase 4.5 |' + [char]10 + '| `portal-jkptg/resources/views/states.blade.php` | 16-example demo catalog | Phase 4.5 |' + [char]10 + '| `STATE-MATRIX.md` | full 16-feature contract | Phase 4.5 |'
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
