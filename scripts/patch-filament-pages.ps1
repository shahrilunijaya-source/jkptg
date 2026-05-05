# Apply Translatable trait + LocaleSwitcher to Filament page classes for translatable resources

$base = 'c:\Users\User\Desktop\Claude\ClaudeCode\Website\Portal-JKPTG\portal-jkptg\app\Filament\Resources'

# Map: ResourceFolder => @{ List = filename; Create = filename; Edit = filename }
$resources = @{
  'ServiceResource' = @{ List = 'ListServices.php'; Create = 'CreateService.php'; Edit = 'EditService.php' }
  'NewsResource' = @{ List = 'ListNews.php'; Create = 'CreateNews.php'; Edit = 'EditNews.php' }
  'TenderResource' = @{ List = 'ListTenders.php'; Create = 'CreateTender.php'; Edit = 'EditTender.php' }
  'FormResource' = @{ List = 'ListForms.php'; Create = 'CreateForm.php'; Edit = 'EditForm.php' }
  'FaqResource' = @{ List = 'ListFaqs.php'; Create = 'CreateFaq.php'; Edit = 'EditFaq.php' }
  'CawanganResource' = @{ List = 'ListCawangans.php'; Create = 'CreateCawangan.php'; Edit = 'EditCawangan.php' }
  'ChatbotKnowledgeResource' = @{ List = 'ListChatbotKnowledge.php'; Create = 'CreateChatbotKnowledge.php'; Edit = 'EditChatbotKnowledge.php' }
}

foreach ($resName in $resources.Keys) {
  $set = $resources[$resName]

  # ListPage
  $listPath = Join-Path $base "$resName\Pages\$($set.List)"
  if (Test-Path $listPath) {
    $content = Get-Content $listPath -Raw
    if ($content -notmatch 'use Translatable') {
      $content = $content -replace '(use Filament\\Resources\\Pages\\ListRecords;)', "`$1`nuse Filament\Resources\Pages\ListRecords\Concerns\Translatable;"
      $content = $content -replace '(class \w+ extends ListRecords\s*\{)', "`$1`n    use Translatable;"
      $content = $content -replace '(return \[)\s*Actions\\CreateAction::make\(\),', "`$1`n            Actions\LocaleSwitcher::make(),`n            Actions\CreateAction::make(),"
      Set-Content -Path $listPath -Value $content -NoNewline -Encoding UTF8
      Write-Host "OK List: $listPath"
    }
  }

  # CreatePage
  $createPath = Join-Path $base "$resName\Pages\$($set.Create)"
  if (Test-Path $createPath) {
    $content = Get-Content $createPath -Raw
    if ($content -notmatch 'use Translatable') {
      $content = $content -replace '(use Filament\\Resources\\Pages\\CreateRecord;)', "`$1`nuse Filament\Resources\Pages\CreateRecord\Concerns\Translatable;"
      $content = $content -replace '(class \w+ extends CreateRecord\s*\{)', "`$1`n    use Translatable;`n`n    protected function getHeaderActions(): array`n    {`n        return [`n            \Filament\Actions\LocaleSwitcher::make(),`n        ];`n    }"
      Set-Content -Path $createPath -Value $content -NoNewline -Encoding UTF8
      Write-Host "OK Create: $createPath"
    }
  }

  # EditPage
  $editPath = Join-Path $base "$resName\Pages\$($set.Edit)"
  if (Test-Path $editPath) {
    $content = Get-Content $editPath -Raw
    if ($content -notmatch 'use Translatable') {
      $content = $content -replace '(use Filament\\Resources\\Pages\\EditRecord;)', "`$1`nuse Filament\Resources\Pages\EditRecord\Concerns\Translatable;"
      $content = $content -replace '(class \w+ extends EditRecord\s*\{)', "`$1`n    use Translatable;"
      $content = $content -replace '(return \[)\s*Actions\\DeleteAction::make\(\),', "`$1`n            Actions\LocaleSwitcher::make(),`n            Actions\DeleteAction::make(),"
      Set-Content -Path $editPath -Value $content -NoNewline -Encoding UTF8
      Write-Host "OK Edit: $editPath"
    }
  }
}

Write-Host 'Done.'
