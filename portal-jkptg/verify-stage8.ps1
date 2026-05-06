$base = 'http://127.0.0.1:8000'
$pass = 0; $fail = 0; $warn = 0
$failed = @()

function Check($label, [bool]$ok, [string]$detail = '') {
  if ($ok) { Write-Host "  PASS  $label $detail"; $script:pass++ }
  else { Write-Host "  FAIL  $label $detail" -ForegroundColor Red; $script:fail++; $script:failed += "$label $detail" }
}
function Warn($label, [string]$detail = '') {
  Write-Host "  WARN  $label $detail" -ForegroundColor Yellow; $script:warn++
}

function Get-Page($path) {
  try { return Invoke-WebRequest -Uri "$base$path" -UseBasicParsing -TimeoutSec 15 } catch { return $null }
}

Write-Host "=========================================="
Write-Host "Stage 8 / Phase 13 Verification"
Write-Host "=========================================="

Write-Host "`n[A] Public route status"
foreach ($p in '/','/perkhidmatan','/panduan/borang','/hubungi','/korporat','/sumber','/cari?q=tanah','/untuk/orang-awam','/untuk/kementerian-jabatan','/untuk/warga-jkptg','/states','/sitemap.xml','/robots.txt','/.well-known/security.txt','/humans.txt') {
  $r = Get-Page $p
  Check "GET $p" ($r -and $r.StatusCode -eq 200) ("len=" + ($r.Content.Length))
}

Write-Host "`n[B] Admin auth gate"
try {
  $r = Invoke-WebRequest -Uri "$base/admin" -UseBasicParsing -TimeoutSec 8
  $finalPath = $r.BaseResponse.ResponseUri.AbsolutePath
  Check "/admin guards to login (final=$finalPath)" ($finalPath -eq '/admin/login')
} catch { Check "/admin reachable" $false $_.Exception.Message }
$r = Get-Page '/admin/login'
Check "/admin/login renders 200" ($r -and $r.StatusCode -eq 200)

Write-Host "`n[C] Security headers"
$r = Get-Page '/'
foreach ($h in 'X-Frame-Options','X-Content-Type-Options','Referrer-Policy','Permissions-Policy','Content-Security-Policy','Cross-Origin-Opener-Policy') {
  $val = if ($r) { ($r.Headers[$h] -join '') } else { '' }
  $detail = if ($val.Length -gt 0) { "= " + $val.Substring(0, [Math]::Min(50, $val.Length)) } else { '' }
  Check "Header $h" ($r -and $val) $detail
}

Write-Host "`n[D] PPPA compliance (head meta)"
$r = Get-Page '/'
$splaskTags = 'splask-w3c-aa','splask-language','splask-mobile','splask-last-updated','splask-visitor-counter','splask-feedback-url','splask-search','splask-sitemap','splask-security','splask-privacy','agency','agency-full','ministry'
foreach ($t in $splaskTags) {
  Check "meta $t present" ($r -and $r.Content -match [regex]::Escape("name=`"$t`""))
}
Check "lang attribute set" ($r -and $r.Content -match '<html lang="(ms|en)"')
Check "skip-link present" ($r -and $r.Content -match 'class="skip-link"')
Check "viewport meta" ($r -and $r.Content -match 'name="viewport"')
Check "csrf-token meta" ($r -and $r.Content -match 'name="csrf-token"')
Check "canonical link" ($r -and $r.Content -match 'rel="canonical"')
Check "hreflang ms" ($r -and $r.Content -match 'hreflang="ms"')
Check "hreflang en" ($r -and $r.Content -match 'hreflang="en"')

Write-Host "`n[E] PPPA visible elements"
Check "footer has visit counter" ($r -and $r.Content -match 'Pelawat')
Check "footer has last updated" ($r -and $r.Content -match 'Kemaskini')
Check "footer has disclaimer link" ($r -and $r.Content -match 'Penafian')
Check "footer has privacy link" ($r -and $r.Content -match 'Polisi Privasi|Privacy Policy')
Check "footer has security link" ($r -and $r.Content -match 'Polisi Keselamatan|Security Policy')
Check "footer has sitemap link" ($r -and $r.Content -match 'Peta Laman|Sitemap')
Check "search input in header" ($r -and $r.Content -match 'name="q"')
Check "BM/EN switcher present" ($r -and $r.Content -match 'route\(.locale|/locale/(ms|en)')
Check "single h1" (([regex]::Matches($r.Content, '<h1[\s>]')).Count -eq 1)
Check "JATA element present" ($r -and $r.Content -match 'JATA')

Write-Host "`n[F] Bilingual smoke (BM <-> EN)"
$session = New-Object Microsoft.PowerShell.Commands.WebRequestSession
$null = Invoke-WebRequest -Uri "$base/" -UseBasicParsing -WebSession $session -TimeoutSec 8
$bmHome = (Invoke-WebRequest -Uri "$base/" -UseBasicParsing -WebSession $session -TimeoutSec 8).Content
Check "BM homepage has 'Hubungi Kami'" ($bmHome -match 'Hubungi Kami')
$null = Invoke-WebRequest -Uri "$base/locale/en" -UseBasicParsing -WebSession $session -MaximumRedirection 5 -TimeoutSec 8
$enHome = (Invoke-WebRequest -Uri "$base/" -UseBasicParsing -WebSession $session -TimeoutSec 8).Content
Check "EN homepage has 'Contact Us'" ($enHome -match 'Contact Us')
Check "BM/EN content actually differs" ($bmHome.Length -ne $enHome.Length -or ($bmHome -ne $enHome))

Write-Host "`n[G] CSRF token on POST forms"
$null = Invoke-WebRequest -Uri "$base/locale/ms" -UseBasicParsing -WebSession $session -TimeoutSec 8
$searchPage = (Invoke-WebRequest -Uri "$base/cari" -UseBasicParsing -WebSession $session -TimeoutSec 8).Content
Check "Search form is GET (no CSRF needed)" ($searchPage -match 'method="get"')

Write-Host "`n[H] Search end-to-end"
foreach ($q in 'tanah','pengambilan','tender','pajakan') {
  $r2 = Get-Page "/cari?q=$q"
  $hasResults = $r2.Content -match 'class="text-primary">\d+</strong>'
  Check "/cari?q=$q renders results" ($r2 -and $r2.StatusCode -eq 200 -and $hasResults)
}

Write-Host "`n[I] Sitemap content"
$sm = Get-Page '/sitemap.xml'
$urlCount = ([regex]::Matches($sm.Content, '<loc>')).Count
Check "sitemap has >=20 URLs" ($urlCount -ge 20) "count=$urlCount"
Check "sitemap is valid XML" ($sm.Content -match '<\?xml' -and $sm.Content -match '</urlset>')

Write-Host "`n[J] Legacy redirect chain"
foreach ($p in '/services','/forms','/contact','/about','/news','/help','/sitemap') {
  try {
    $rr = Invoke-WebRequest -Uri "$base$p" -UseBasicParsing -TimeoutSec 8
    $finalPath = $rr.BaseResponse.ResponseUri.AbsolutePath
    Check "redirect $p -> $finalPath" ($finalPath -ne $p)
  } catch { Check "redirect $p" $false $_.Exception.Message }
}

Write-Host "`n[K] Performance baseline"
foreach ($p in '/','/perkhidmatan','/cari?q=tanah') {
  $sw = [Diagnostics.Stopwatch]::StartNew()
  $r3 = Get-Page $p
  $sw.Stop()
  $ms = $sw.ElapsedMilliseconds
  $kb = if ($r3) { [int]($r3.Content.Length / 1024) } else { 0 }
  $perfOk = ($ms -lt 1500 -and $kb -lt 200)
  Check "perf $p" $perfOk "${ms}ms ${kb}KB"
}

Write-Host "`n=========================================="
Write-Host "Summary: PASS=$pass  FAIL=$fail  WARN=$warn"
Write-Host "=========================================="
if ($failed.Count) {
  Write-Host "`nFailed:"
  foreach ($f in $failed) { Write-Host "  - $f" -ForegroundColor Red }
}
