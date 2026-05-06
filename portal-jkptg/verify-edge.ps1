$base = 'http://127.0.0.1:8000'

function Probe($url, [int]$expect = 200, [string[]]$contains = @()) {
  try {
    $r = Invoke-WebRequest -Uri $url -UseBasicParsing -TimeoutSec 12 -MaximumRedirection 0 -ErrorAction Stop
    $code = [int]$r.StatusCode
  } catch [System.Net.WebException] {
    if ($_.Exception.Response) { $code = [int]$_.Exception.Response.StatusCode } else { $code = -1 }
    $r = $null
  } catch {
    $r = $null; $code = -1
  }
  $hits = 0; $miss = @()
  if ($r) {
    foreach ($c in $contains) { if ($r.Content -match [regex]::Escape($c)) { $hits++ } else { $miss += $c } }
  }
  $ok = ($code -eq $expect)
  $tag = if ($ok) { 'OK ' } else { 'BAD' }
  $out = "[$tag $code] $url"
  if ($contains.Count) { $out += "  hits=$hits/$($contains.Count)" }
  if ($miss.Count) { $out += "  miss: " + ($miss -join ' | ') }
  $out
}

Write-Host '=== SEO endpoints ==='
Probe "$base/sitemap.xml" 200 @('<urlset', '/perkhidmatan', '/halaman/', '<loc>')
Probe "$base/robots.txt" 200 @('User-agent: *', 'Disallow: /admin', 'Sitemap:')
Probe "$base/.well-known/security.txt" 200 @('Contact:', 'Expires:', 'Preferred-Languages:')
Probe "$base/humans.txt" 200 @('Agency:', 'Stack:', 'Compliance:')

Write-Host "`n=== SPLaSK head meta tags ==="
$r = Invoke-WebRequest -Uri "$base/" -UseBasicParsing -TimeoutSec 12
foreach ($tag in 'splask-w3c-aa', 'splask-language', 'splask-mobile', 'splask-last-updated', 'splask-visitor-counter', 'splask-feedback-url', 'splask-search', 'splask-sitemap', 'splask-security', 'agency-full', 'og:title', 'og:locale', 'rel="canonical"', 'hreflang="ms"', 'hreflang="en"', 'hreflang="x-default"') {
  if ($r.Content -match [regex]::Escape($tag)) { "  + $tag" } else { "  - MISSING $tag" }
}

Write-Host "`n=== Legacy redirects (expect 301) ==="
foreach ($p in '/services','/forms','/contact','/about','/news','/tender','/faq','/search','/index.php','/v2','/sitemap') {
  Probe "$base$p" 301
}

Write-Host "`n=== Public pages still 200 ==="
foreach ($p in '/','/perkhidmatan','/panduan/borang','/hubungi','/korporat','/sumber','/cari?q=tanah','/untuk/orang-awam') {
  Probe "$base$p" 200
}
