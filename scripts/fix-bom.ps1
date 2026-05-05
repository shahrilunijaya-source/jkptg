$base = 'c:\Users\User\Desktop\Claude\ClaudeCode\Website\Portal-JKPTG\portal-jkptg\app\Filament\Resources'
$count = 0
Get-ChildItem -Path $base -Recurse -Filter '*.php' | ForEach-Object {
  $bytes = [System.IO.File]::ReadAllBytes($_.FullName)
  if ($bytes.Length -ge 3 -and $bytes[0] -eq 0xEF -and $bytes[1] -eq 0xBB -and $bytes[2] -eq 0xBF) {
    $stripped = $bytes[3..($bytes.Length - 1)]
    [System.IO.File]::WriteAllBytes($_.FullName, $stripped)
    Write-Host "Stripped BOM: $($_.Name)"
    $count++
  }
}
Write-Host "Total fixed: $count"
