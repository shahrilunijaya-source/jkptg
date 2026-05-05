$c = Get-Content 'c:\Users\User\Desktop\Claude\ClaudeCode\Website\Portal-JKPTG\PLAN.md' -Raw
$idx = $c.IndexOf('LLM driver abstraction')
[Console]::OutputEncoding = [System.Text.Encoding]::UTF8
if ($idx -ge 0) {
  $excerpt = $c.Substring($idx, 500)
  Write-Host $excerpt
  Write-Host '--- BYTES ---'
  $bytes = [System.Text.Encoding]::UTF8.GetBytes($excerpt.Substring(0, 100))
  Write-Host ($bytes -join ' ')
}

$idx2 = $c.IndexOf('Cost cap: set')
if ($idx2 -ge 0) {
  Write-Host '--- COST CAP SET LINE ---'
  Write-Host $c.Substring($idx2, 200)
}
