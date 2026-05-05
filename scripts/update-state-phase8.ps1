$path = 'c:\Users\User\Desktop\Claude\ClaudeCode\Website\Portal-JKPTG\STATE.md'
$content = Get-Content $path -Raw -Encoding UTF8

$olds = @()
$news = @()

$olds += '**Current stage:** Phase 7 COMPLETE (Stage 7 in progress). 9 Filament admin resources live with translatable plugin. Ready for Phase 8.'
$news += '**Current stage:** Phase 8 COMPLETE (Stage 7 in progress). Dashboard widgets + audit log live. Ready for Phase 9.'

$olds += '**Last updated:** 2026-05-06 (Phase 7 admin resources done)'
$news += '**Last updated:** 2026-05-06 (Phase 8 dashboard + audit log done)'

$olds += '  - [ ] Phase 8 - Filament dashboard + audit log <- NEXT'
$news += '  - [x] Phase 8 - Filament dashboard (4 widgets) + ActivityResource audit log + 528 visit_logs seeded' + [char]10 + '  - [ ] Phase 9 - Chatbot (Anthropic Sonnet 4.6 + Canned fallback) <- NEXT'

$olds += '  - [ ] Phase 9 - Chatbot'
$news += ''

$old5 = '## Phase 8 - Resume Plan'
$new5 = @'
## Phase 8 - Deliverables (LOCKED 2026-05-06)

| Component | Detail |
|-----------|--------|
| StatsOverview widget | 8 stat cards: Halaman/Perkhidmatan/Berita/Tender Terbuka/Borang Muat Turun/KB/Pengguna/Lawatan 24j |
| LlmCostMeter widget | RM mtd vs cap with color flip at alert threshold (success/warning/danger), driver+model, API success rate |
| VisitorChart widget | 7-day line chart from visit_logs (Chart.js via Filament ChartWidget), navy primary fill |
| RecentActivity widget | TableWidget over Spatie Activity, last 10 entries with event badge |
| ActivityResource | Read-only audit log /admin/log-audit, filters by today/week/event, ViewAction with KeyValue properties |
| SampleActivitySeeder | 528 visit_logs (7 days, peaked today ~126) + 5 explicit + ~54 auto-logged activities |

verify-widgets.php confirms via reflection: all 8 stats, 7-day visitor chart 528 total, LlmCostMeter RM 0.00 / 200.00 (0%) green.

## Phase 9 - Resume Plan
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
