$base = 'http://127.0.0.1:8000'
$paths = @('/', '/perkhidmatan', '/panduan/borang', '/hubungi', '/korporat', '/sumber', '/untuk/orang-awam')
$session = New-Object Microsoft.PowerShell.Commands.WebRequestSession

function Probe($url, $expects) {
  try {
    $r = Invoke-WebRequest -Uri $url -UseBasicParsing -WebSession $session -TimeoutSec 12
    $hits = 0; $miss = @()
    foreach ($p in $expects) {
      if ($r.Content -match [regex]::Escape($p)) { $hits++ } else { $miss += $p }
    }
    "[$($r.StatusCode)] $url   hits=$hits/$($expects.Count)" + ($(if ($miss.Count) { '   miss: ' + ($miss -join ' | ') } else { '' }))
  } catch {
    "[ERR] $url $($_.Exception.Message)"
  }
}

Write-Host '=== BM (default) ==='
foreach ($p in $paths) {
  Probe "$base$p" @('Hubungi Kami', 'Perkhidmatan', 'Status Permohonan', 'Mengenai JKPTG')
}

Write-Host "`n=== Switch to EN ==="
$null = Invoke-WebRequest -Uri "$base/locale/en" -UseBasicParsing -WebSession $session -MaximumRedirection 5 -TimeoutSec 10
Write-Host '=== EN pages ==='
foreach ($p in $paths) {
  Probe "$base$p" @('Contact Us', 'Application Status', 'About JKPTG', 'Client Charter')
}

Write-Host "`n=== Switch back to MS ==="
$null = Invoke-WebRequest -Uri "$base/locale/ms" -UseBasicParsing -WebSession $session -MaximumRedirection 5 -TimeoutSec 10
$r = Invoke-WebRequest -Uri "$base/" -UseBasicParsing -WebSession $session -TimeoutSec 10
if ($r.Content -match 'Hubungi Kami' -and $r.Content -notmatch 'Contact Us') { Write-Host 'MS restore: OK' } else { Write-Host 'MS restore: FAIL' }
