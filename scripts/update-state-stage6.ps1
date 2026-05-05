$path = 'c:\Users\User\Desktop\Claude\ClaudeCode\Website\Portal-JKPTG\STATE.md'
$content = Get-Content $path -Raw -Encoding UTF8

$olds = @()
$news = @()

# Header
$olds += '**Current stage:** Stage 5 COMPLETE. Visual variants picked. Ready for Stage 6 HTML mockup.'
$news += '**Current stage:** Stage 6 COMPLETE. HTML mockup built. Awaiting approval before Stage 6.5 Laravel scaffold.'

$olds += '**Last updated:** 2026-05-06 (Stage 5 variants locked)'
$news += '**Last updated:** 2026-05-06 (Stage 6 mockup built)'

# Progress block
$olds += '- [ ] Stage 6 - HTML Mockup <- NEXT'
$news += '- [x] Stage 6 - HTML Mockup  `mockup/` (3 pages + README, Tailwind CDN, Stage 5 variants applied)'

$olds += '- [ ] Stage 6.5 - Auth + Filament admin scaffold'
$news += '- [ ] Stage 6.5 - Auth + Filament admin scaffold <- NEXT'

# Replace Stage 6 resume plan with Stage 6.5 plan
$old5 = @'
## Stage 6 - Resume Plan

Run `/design-html` to build static HTML/CSS mockup.

Mockup order:
1. Homepage (hero + service tiles + news + footer)
2. Persona landing - Orang Awam
3. Service detail - Pengambilan Tanah
4. Megamenu open state (interactive demo)

Approve mockup before Phase 7 Laravel Blade integration.
'@

$new5 = @'
## Stage 6 - Mockup deliverables (LOCKED 2026-05-06)

| File | Variant |
|------|---------|
| `mockup/index.html` | Homepage with hero overlay (A) + 6 tiles + 3-tab news + agency carousel |
| `mockup/persona/orang-awam.html` | Classic persona landing (A) - 3-col grid + sidebar |
| `mockup/perkhidmatan/pengambilan.html` | Sticky-nav service detail (B) - left rail + sticky CTA |
| Megamenu | Embedded in index.html header - hero-style featured cards (C) |
| `mockup/README.md` | How-to-view + Phase 7 conversion notes + approval checklist |

Tech: Tailwind via CDN, Lucide icons, Inter + Poppins from Google Fonts. No build step.

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
$olds += $old5
$news += $new5

# Files state - inject mockup row
$oldRow = '| `VARIANTS.md` | LOCKED 2026-05-06 (Stage 5 picks) | 5 |'
$newRow = '| `VARIANTS.md` | LOCKED 2026-05-06 (Stage 5 picks) | 5 |' + [char]10 + '| `mockup/index.html` | done 2026-05-06 | 6 |' + [char]10 + '| `mockup/persona/orang-awam.html` | done 2026-05-06 | 6 |' + [char]10 + '| `mockup/perkhidmatan/pengambilan.html` | done 2026-05-06 | 6 |' + [char]10 + '| `mockup/README.md` | done 2026-05-06 | 6 |'
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
