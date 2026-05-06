$path = 'c:\Users\User\Desktop\Claude\ClaudeCode\Website\Portal-JKPTG\STATE.md'
$content = Get-Content $path -Raw -Encoding UTF8

$olds = @()
$news = @()

$olds += '**Current stage:** Phase 8 COMPLETE (Stage 7 in progress). Dashboard widgets + audit log live. Ready for Phase 9.'
$news += '**Current stage:** Phase 9 COMPLETE (Stage 7 in progress). Chatbot bubble live (canned default + Anthropic fallback chain). Ready for Phase 10.'

$olds += '**Last updated:** 2026-05-06 (Phase 8 dashboard + audit log done)'
$news += '**Last updated:** 2026-05-06 (Phase 9 chatbot done)'

$olds += '  - [ ] Phase 9 - Chatbot (Anthropic Sonnet 4.6 + Canned fallback) <- NEXT'
$news += '  - [x] Phase 9 - Chatbot Livewire bubble + LlmService (Anthropic Sonnet 4.6 + Canned fallback) + sanitizer + rate limiter + cost cap kill-switch' + [char]10 + '  - [ ] Phase 10 - i18n polish (BM/EN parallel) <- NEXT'

$old5 = '## Phase 9 - Resume Plan'
$new5 = @'
## Phase 9 - Deliverables (LOCKED 2026-05-06)

| Component | Detail |
|-----------|--------|
| LlmDriver interface | `chat(string $msg, array $history, string $locale): LlmResponse` + `name()` |
| LlmResponse DTO | content, citation, prompt/completion tokens, cost USD/RM, latency, driver, model, fellBack, fallbackReason |
| AnthropicDriver | Claude Sonnet 4.6 via Http::post /v1/messages, cost calc USD->RM 4.7x, locale-aware system prompt |
| CannedDriver | KB token-match scorer (length-weighted), threshold>=2, BM/EN aware, fallback text |
| Sanitizer | strip control chars + banned tokens (system:, <\|im_*\|>, etc.), 2000 char cap |
| LlmService | orchestrates: kill-switch -> canned else try anthropic -> on RateLimit/Timeout/InvalidResponse fall back to canned. Accrues cost, flips kill-switch at cap, alert log at threshold |
| Livewire Bubble | toggleable panel, quick replies, message log, thinking indicator, citation+fallback badges, RateLimiter (10/IP/hr) |
| Persistence | chat_sessions (UUID cookie 30d), chat_messages (encrypted content cast), llm_api_logs every call |
| config/chatbot.php | driver=canned default, anthropic block, rate_limit, sanitizer, system_prompt MS/EN, history_window=6 |
| Lang keys | chatbot.title/subtitle/thinking/input_label/fallback/rate_limited/error_generic/disclaimer (MS+EN) |

verify-chatbot.php confirms: 6 KB rows, 4 quick replies, sanitizer cleans `system:` + `<|im_start|>` + NUL, CannedDriver matches `pengambilan tanah` -> KB#kb-pengambilan-tempoh, end-to-end LlmService writes llm_api_logs row. Homepage 200 with wire:id present.

## Phase 10 - Resume Plan
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
