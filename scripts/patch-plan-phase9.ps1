$path = 'c:\Users\User\Desktop\Claude\ClaudeCode\Website\Portal-JKPTG\PLAN.md'
$content = Get-Content $path -Raw -Encoding UTF8

# File has mojibake: Â§ (C3 82 C2 A7 = U+00C2 + U+00A7) and â†’ (C3 A2 E2 80 A0 ... actually U+00E2 U+2020 U+2019)
# Skip the mojibake - just find by stable substrings

$arrow_mojibake = [char]0x00E2 + [char]0x2020 + [char]0x2019  # â†™ wait no
# From inspect output: â†’ rendered. Bytes 195 162 226 128 160 226 128 153 - too complex.
# Actually the file's "->" character set: when displayed, "â†’" appears. That's UTF-8-double-encoded U+2192.
# U+2192 in UTF-8 = E2 86 92. Re-interpret each byte as Latin-1, re-encode as UTF-8: C3 A2 C2 86 C2 92.
# When read as UTF-8 those bytes give: U+00E2 U+0086 U+0092 = "â" - not "â†™"
# But the display shows "â†’". The middle char displayed as "†" is U+2020 dagger.
# So actual chars in string: â (U+00E2), † (U+2020), ™ wait no.
# Let me just search using fragments without the special chars.

$olds = @()
$news = @()

# Find by unique stable substring around the line
# Block 1: replace OpenAiDriver line
$old1 = "App\Services\Llm\OpenAiDriver        // GPT-4o-mini or 4o" + [char]10 + "   App\Services\Llm\AnthropicDriver     // Claude Haiku 4.5 (cheap, fast)"
$new1 = "App\Services\Llm\AnthropicDriver     // Claude Sonnet 4.6 (Eng review Q4 pick)"
$olds += $old1
$news += $new1

# Block 2: heading "LLM driver abstraction**" - add eng note
$old2 = "**LLM driver abstraction**"
$new2 = "**LLM driver abstraction** (Eng review Q9: Anthropic + Canned only - OpenAiDriver deferred to production)"
$olds += $old2
$news += $new2

# Block 3: cost cap default - find by "(default $40), force-flip"
$old3 = "(default `$40), force-flip to ``canned`` driver + alert admin"
$new3 = "(default **RM 200 = ~`$40 USD = ~7.5k Sonnet 4.6 chats**, Eng review Q6), force-flip to ``canned`` driver + admin email alert. 80% threshold (~RM 160) also triggers email alert. Monthly reset via Hostinger cron ``0 0 1 * * php artisan llm:reset-cap`` (Eng review Q14)"
$olds += $old3
$news += $new3

# Block 4: verify - LLM answer
$old4 = "Bot responds with LLM-driven answer (when driver=anthropic/openai) or KB-matched (when driver=canned)"
$new4 = "Bot responds with LLM-driven answer (when driver=anthropic, Sonnet 4.6) or KB-matched (when driver=canned)"
$olds += $old4
$news += $new4

$replaced = 0
for ($i = 0; $i -lt $olds.Length; $i++) {
  $o = $olds[$i]
  $n = $news[$i]
  $oNorm = $o -replace "`r`n", "`n"
  $cNorm = $content -replace "`r`n", "`n"
  if ($cNorm.Contains($oNorm)) {
    $content = $cNorm.Replace($oNorm, $n)
    $replaced++
    Write-Host ('OK[' + ($i+1) + '] len=' + $o.Length)
  } else {
    Write-Host ('MISS[' + ($i+1) + '] first 60 chars: ' + $o.Substring(0, [Math]::Min(60, $o.Length)))
  }
}

Set-Content -Path $path -Value $content -NoNewline -Encoding UTF8
Write-Host ('Done. ' + $replaced + '/' + $olds.Length)
