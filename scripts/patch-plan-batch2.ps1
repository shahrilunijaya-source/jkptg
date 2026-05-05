$path = 'c:\Users\User\Desktop\Claude\ClaudeCode\Website\Portal-JKPTG\PLAN.md'
$content = Get-Content $path -Raw -Encoding UTF8

$olds = @()
$news = @()

# Patch 1: Phase 0.9 anti-pattern that contradicts CEO+Eng review (real LLM)
$olds += 'External LLM call'
$news += 'ALLOWED: Real Anthropic Sonnet 4.6 LLM (CEO review + Eng Q9) - WAS deprecated External LLM call'

# Patch 2: Phase 1 #17 - 2FA install line
$olds += '17. `composer require pragmarx/google2fa-laravel` (deferred'
$news += '17. SKIPPED - Eng review Q5: 2FA deferred to production phase. Do NOT install `pragmarx/google2fa-laravel` in prototype. (Note kept for reference'

# Patch 3: Phase 12.6 - Queue overflow specifics
$old3 = 'Queue overflow guard: if queue depth ' + [char]0x003E + ' 10k, drop new entries silently + log to errors'
$new3 = 'Queue overflow guard (Eng review Q15): jobs use `tries=3`, `backoff=[60,300,900]`. Failed jobs land in `failed_jobs` table. Filament admin retry UI exposed. Email alert if backlog over 50. If queue depth over 10k, drop new visit_logs entries silently + log to errors channel.'
$olds += $old3
$news += $new3

# Patch 4: Phase 12.6 - clarify grow-and-archive
$olds += 'Retention: 13 months (FY-rolling), then archive to S3 or delete'
$news += 'Retention: 13 months (PDPA / SPA Bil 4/2024). Eng review Q7 = grow-and-archive. Monthly cron `0 2 1 * * php artisan visit-logs:archive` exports rows older than 13mo to CSV in storage/app/archives/ then DROPs them. Single MySQL table - no partitioning.'

# Patch 5: Phase 0.10 mark resolved
$olds += '### 0.10 Open questions to resolve in `/plan-eng-review`'
$news += '### 0.10 Open questions to resolve in `/plan-eng-review` (ALL RESOLVED 2026-05-06 - see Eng Review Decisions section at end of PLAN.md)'

$replaced = 0
for ($i = 0; $i -lt $olds.Length; $i++) {
  $o = $olds[$i]
  $n = $news[$i]
  $oNorm = $o.Replace("`r`n", "`n")
  $cNorm = $content.Replace("`r`n", "`n")
  if ($cNorm.Contains($oNorm)) {
    $content = $cNorm.Replace($oNorm, $n)
    $replaced++
    Write-Host ('OK[' + ($i+1) + '] len=' + $o.Length)
  } else {
    Write-Host ('MISS[' + ($i+1) + '] ' + $o.Substring(0, [Math]::Min(70, $o.Length)))
  }
}

Set-Content -Path $path -Value $content -NoNewline -Encoding UTF8
Write-Host ('Done. ' + $replaced + '/' + $olds.Length)
