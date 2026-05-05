$path = 'c:\Users\User\Desktop\Claude\ClaudeCode\Website\Portal-JKPTG\PLAN.md'
$content = Get-Content $path -Raw -Encoding UTF8

# Insert Phase 0.5 Hostinger verification BEFORE Phase 1
$marker = [char]10 + '## Phase 1'

$insertion = @'

## Phase 0.5 - Hostinger Plan Tier Verification (Eng review Q11)

**Goal:** Confirm Hostinger plan supports prototype requirements BEFORE Phase 1 scaffold. Block builds if Premium needed.

### 0.5.1 Verification checklist

Run these checks via Hostinger hPanel + SSH:

- [ ] PHP version selectable to **8.3+** (composer 11.x requires PHP 8.2+, recommend 8.3)
- [ ] MySQL **8.0+** or MariaDB **10.6+** (for JSON_EXTRACT translatable search)
- [ ] **Composer SSH access** (Hostinger Premium+ tier - verify via `ssh user@host composer --version`)
- [ ] **Outbound HTTPS** to `api.anthropic.com` (some shared hosts block outbound non-HTTP-listener traffic). Test: `ssh user@host curl -sS https://api.anthropic.com/v1/messages -o /dev/null -w "%{http_code}"` should return 401 (auth-required, not blocked).
- [ ] **Cron support** - able to schedule `* * * * * php artisan schedule:run` and `0 0 1 * * php artisan llm:reset-cap`
- [ ] **Git auto-pull webhook** or manual `git pull` via SSH from GitHub repo
- [ ] Disk quota >= **2 GB** (Laravel + Filament vendor/ ~ 250 MB, plus storage for PDF cache + visit_logs archives)
- [ ] **Memory limit** PHP `memory_limit >= 256M` (Filament resource forms + DomPDF render)

### 0.5.2 Blocker matrix

| If missing | Action |
|------------|--------|
| PHP 8.3 | Negotiate Hostinger plan upgrade OR pin PHP 8.2 + adjust composer constraints |
| Composer SSH | Switch plan tier OR use deploy script that runs composer locally + uploads vendor/ |
| Outbound HTTPS to api.anthropic.com | Disable real LLM driver, fallback to canned-only - notify user before tender |
| Cron support | Use external cron (cron-job.org) hitting webhook URL OR plan upgrade |

### 0.5.3 Output

Document confirmed plan name + limits in `CONSTRAINTS.md` section 16 (new). Block Phase 1 git init until all checks pass.

---
'@

$idx = $content.IndexOf($marker)
if ($idx -ge 0) {
  $content = $content.Substring(0, $idx) + $insertion + $content.Substring($idx)
  Set-Content -Path $path -Value $content -NoNewline -Encoding UTF8
  Write-Host ('OK - inserted Phase 0.5 at index ' + $idx)
} else {
  Write-Host 'MISS - Phase 1 marker not found'
}
