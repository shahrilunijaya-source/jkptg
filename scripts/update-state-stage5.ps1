$path = 'c:\Users\User\Desktop\Claude\ClaudeCode\Website\Portal-JKPTG\STATE.md'
$content = Get-Content $path -Raw -Encoding UTF8

$olds = @()
$news = @()

# Update header
$olds += '**Current stage:** Stage 4 COMPLETE. PLAN.md locked. Ready for Stage 5 visual variants.'
$news += '**Current stage:** Stage 5 COMPLETE. Visual variants picked. Ready for Stage 6 HTML mockup.'

$olds += '**Last updated:** 2026-05-06 (Stage 4 final lock)'
$news += '**Last updated:** 2026-05-06 (Stage 5 variants locked)'

# Update progress block
$olds += '- [ ] Stage 5 - Visual Variants (max 3 per page per LESSONS rule 1) <- NEXT'
$news += '- [x] Stage 5 - Visual Variants  `VARIANTS.md` (4 winners locked: Hero=A overlay, Persona=A classic, Service=B sticky, Megamenu=C hero-cards)'

$olds += '- [ ] Stage 6 - HTML Mockup'
$news += '- [ ] Stage 6 - HTML Mockup <- NEXT'

# Replace Stage 5 resume plan with Stage 6 plan
$old5 = @'
## Stage 5 - Resume Plan

Run `/design-shotgun` for visual variants. Max 3 per page per LESSONS rule 1.

Pages needing variants:
1. Homepage hero (hybrid: photo + 3 persona doors)
2. Persona landing template (Phase 5.2.1 component)
3. Service detail page (Pengambilan as exemplar)
4. Megamenu open state (PERKHIDMATAN as exemplar)

Pick winners then Stage 6 HTML mockup.
'@

$new5 = @'
## Stage 5 - Variant picks (LOCKED 2026-05-06)

| Page | Winner | Pattern |
|------|--------|---------|
| Homepage hero | A | Overlay (full-bleed photo + persona doors floating) |
| Persona landing | A | Classic (hero + 3-col service grid + news + sidebar) |
| Service detail | B | Sticky left nav + sticky apply CTA |
| Megamenu open state | C | Hero-style with featured cards |

Full pattern docs: `VARIANTS.md`

## Stage 6 - Resume Plan

Run `/design-html` to build static HTML/CSS mockup.

Mockup order:
1. Homepage (hero + service tiles + news + footer)
2. Persona landing - Orang Awam
3. Service detail - Pengambilan Tanah
4. Megamenu open state (interactive demo)

Approve mockup before Phase 7 Laravel Blade integration.
'@
$olds += $old5
$news += $new5

# Add VARIANTS.md to files state - inject after PLAN.md row
$oldRow = '| `PLAN.md` | LOCKED 2026-05-06 (Stage 4 final) | 4 |'
$newRow = '| `PLAN.md` | LOCKED 2026-05-06 (Stage 4 final) | 4 |' + [char]10 + '| `VARIANTS.md` | LOCKED 2026-05-06 (Stage 5 picks) | 5 |'
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
