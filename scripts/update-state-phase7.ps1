$path = 'c:\Users\User\Desktop\Claude\ClaudeCode\Website\Portal-JKPTG\STATE.md'
$content = Get-Content $path -Raw -Encoding UTF8

$olds = @()
$news = @()

$olds += '**Current stage:** Phase 6 COMPLETE (Stage 7 in progress). Service/Borang/Hubungi/Korporat/Sumber pages live. Ready for Phase 7.'
$news += '**Current stage:** Phase 7 COMPLETE (Stage 7 in progress). 9 Filament admin resources live with translatable plugin. Ready for Phase 8.'

$olds += '**Last updated:** 2026-05-06 (Phase 6 internal pages done)'
$news += '**Last updated:** 2026-05-06 (Phase 7 admin resources done)'

$olds += '  - [ ] Phase 7 - Filament admin resources <- NEXT'
$news += '  - [x] Phase 7 - Filament admin resources (9 resources, 8 translatable, 5 nav groups, navy theme)' + [char]10 + '  - [ ] Phase 8 - Filament dashboard + audit log <- NEXT'

$olds += '  - [ ] Phase 8 - Filament dashboard + audit log'
$news += ''

$old5 = '## Phase 7 - Resume Plan'
$new5 = @'
## Phase 7 - Deliverables (LOCKED 2026-05-06)

| Resource | Nav group | Translatable | Records |
|----------|-----------|--------------|---------|
| PageResource | Kandungan | Y | 12 |
| ServiceResource | Perkhidmatan | Y | 6 |
| NewsResource | Kandungan | Y | 5 |
| TenderResource | Kandungan | Y | 3 |
| FormResource | Perkhidmatan | Y | 5 |
| FaqResource | Kandungan | Y | 8 |
| CawanganResource | Hubungi | Y | 4 |
| ChatbotKnowledgeResource | Chatbot | Y | 6 |
| UserResource | Pentadbiran | N | 4 |

Panel: navy primary #243D57, brand "Portal JKPTG Admin", path=/admin.
Plugin: SpatieLaravelTranslatablePlugin (locales=ms+en) on 8 resources.
LocaleSwitcher in List/Create/Edit page header actions.
Form features: Section grouping, RichEditor for body, TagsInput for arrays, FileUpload for PDFs (FormResource), money column for RM values, badge columns for enums.
User resource: hashed password (dehydrated when filled), roles multi-select via Spatie relationship.

All 9 admin URLs return 302 to login when unauthenticated. verify-admin.php confirms panel + plugin + record counts.

Helper scripts: scripts/patch-filament-pages.ps1 + scripts/fix-bom.ps1.

## Phase 8 - Resume Plan
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
