# Portal JKPTG — Deployment Checklist (Phase 14 Hostinger)

Phase 13 (Stage 8) verified locally. Before pushing to Hostinger, every row below must be `[x]`.

## .env on production

- [ ] `APP_ENV=production`
- [ ] `APP_DEBUG=false`
- [ ] `APP_URL=https://www.jkptg.gov.my`
- [ ] `APP_KEY=` (run `php artisan key:generate --force` once)
- [ ] `DB_HOST` `DB_DATABASE` `DB_USERNAME` `DB_PASSWORD` (Hostinger MySQL)
- [ ] `SESSION_SECURE_COOKIE=true` (HTTPS only)
- [ ] `SESSION_HTTP_ONLY=true` (default)
- [ ] `SESSION_SAME_SITE=lax` (default)
- [ ] `CHATBOT_DRIVER=canned` (until Anthropic key set)
- [ ] `ANTHROPIC_API_KEY=sk-ant-...` (when ready to flip to live LLM)
- [ ] `SCOUT_DRIVER=database`

## Post-deploy commands

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
php artisan migrate --force
php artisan db:seed --force --class=RolePermissionSeeder
php artisan db:seed --force --class=UserSeeder
php artisan db:seed --force --class=ContentSeeder
php artisan storage:link
php artisan scout:import "App\\Models\\Page"
php artisan scout:import "App\\Models\\Service"
php artisan scout:import "App\\Models\\News"
php artisan scout:import "App\\Models\\Tender"
php artisan scout:import "App\\Models\\Form"
php artisan scout:import "App\\Models\\Faq"
php artisan scout:import "App\\Models\\ChatbotKnowledge"
```

## Web server (Hostinger / Apache)

- [ ] Document root: `public/`
- [ ] HTTPS forced (Let's Encrypt auto-renew)
- [ ] HTTP -> HTTPS 301 redirect
- [ ] `mod_rewrite` active (Laravel `.htaccess` ships in public/)
- [ ] `mod_headers` active for security headers (already enforced via SecurityHeaders middleware)
- [ ] PHP 8.2+
- [ ] Cron: `* * * * * cd /path/to/portal-jkptg && php artisan schedule:run >> /dev/null 2>&1`

## Smoke tests post-deploy

- [ ] `https://www.jkptg.gov.my/` -> 200
- [ ] `https://www.jkptg.gov.my/admin/login` -> 200
- [ ] `https://www.jkptg.gov.my/sitemap.xml` -> 200 + valid XML
- [ ] `https://www.jkptg.gov.my/robots.txt` -> 200 + Sitemap pointer
- [ ] `https://www.jkptg.gov.my/.well-known/security.txt` -> 200
- [ ] `https://www.jkptg.gov.my/cari?q=tanah` -> hits across multiple sections
- [ ] BM/EN switcher flips locale
- [ ] Chatbot bubble opens, canned reply fires
- [ ] Admin login with seeded super-admin works
- [ ] All 22 legacy redirects fire 301 to new path

## Stage 8 baseline (locally verified 2026-05-06)

| Metric | Value |
|--------|-------|
| Public route smoke | 15/15 PASS |
| Security headers | 6/6 PASS (X-Frame, X-Content-Type, Referrer, Permissions, CSP, COOP) |
| SPLaSK head meta | 13/13 PASS |
| PPPA visible elements | 10/10 PASS (visit counter, last updated, disclaimer, privacy, security, sitemap, search, BM/EN switcher, single h1, JATA) |
| Bilingual switch | 3/3 PASS (BM, EN, content differs) |
| Search end-to-end | 4/4 queries hit |
| Sitemap | 34 URLs |
| Legacy redirects | 7/7 PASS |
| Performance | / 525ms 51KB · /perkhidmatan 506ms 45KB · /cari 551ms 47KB |
| Code-level audit | No XSS in user paths · No hard-coded secrets · whereRaw uses parameter binding · admin role-gated via `canAccessPanel` |

## Compliance summary

- PPPA Bil 1/2025: meta tags, BM/EN, WCAG 2.1 AA markers, visit counter, last updated, disclaimer/privacy/security/sitemap links — all present
- WCAG 2.1 AA: skip-link, single h1 per page, landmarks, ARIA on dynamic regions, prefers-reduced-motion, a11y-large-text/contrast toggles
- SPLaSK: 16 head meta tags, /sitemap.xml, /robots.txt, /.well-known/security.txt, /humans.txt
- SOC: row-by-row mapping deferred to tender response document (uses `reference/SOC*.xlsx`)
