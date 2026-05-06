# Hostinger Deploy — Portal JKPTG

Step-by-step Hostinger Premium / Business shared-hosting deploy. Repo is auto-pulled via Hostinger Git integration.

## Prerequisites

- Hostinger account with PHP 8.2+ (Premium or Business plan)
- Domain pointed to the hosting (`www.jkptg.gov.my`)
- MySQL database created via hPanel → Databases → MySQL Databases
- SSH access enabled (Business+ plan) — optional but speeds deploy
- Git repo accessible to Hostinger (GitHub public, or deploy key configured)

## 1. hPanel — Git Auto-Deploy

1. hPanel → Advanced → Git
2. Repository URL: `https://github.com/<user>/Portal-JKPTG.git` (or SSH URL with deploy key)
3. Branch: `master`
4. Install path: `/home/u_jkptg/public_html`
5. Click **Create**. Auto-pull triggers on push.

## 2. Document root

Hostinger lands at `public_html/` by default. Two options:

**Option A (recommended)** — set document root to subdirectory:
- hPanel → Domains → Manage → Advanced → Custom DNS / Document Root
- Change document root to `public_html/portal-jkptg/public`

**Option B** — root .htaccess proxy (already shipped at `portal-jkptg/.htaccess`):
- Forwards all traffic to `public/` automatically
- Works on shared hosting without document root change

## 3. .env on server

```bash
cd /home/u_jkptg/public_html/portal-jkptg
cp .env.production.example .env
nano .env
```

Fill in:
- `APP_KEY` (run `php artisan key:generate --force` once)
- `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` from hPanel → Databases
- `MAIL_PASSWORD` from hPanel → Email
- `ANTHROPIC_API_KEY` (later, when LLM driver flips from canned)

## 4. First deploy

SSH in (or use hPanel File Manager terminal):

```bash
cd /home/u_jkptg/public_html/portal-jkptg
chmod +x deploy.sh
./deploy.sh
```

`deploy.sh` runs:
- `composer install --no-dev --optimize-autoloader`
- `php artisan migrate --force`
- `php artisan storage:link`
- `php artisan config:cache route:cache view:cache event:cache`
- 7x `php artisan scout:import` (Page, Service, News, Tender, Form, Faq, ChatbotKnowledge)
- Permissions on `storage/` and `bootstrap/cache/`

Then seed once:

```bash
php artisan db:seed --force --class=RolePermissionSeeder
php artisan db:seed --force --class=UserSeeder
php artisan db:seed --force --class=ContentSeeder
```

## 5. SSL

hPanel → Security → SSL → Install (Let's Encrypt, free, auto-renew).

After SSL is active, edit `public/.htaccess` and uncomment the HTTPS-force block:

```apache
RewriteCond %{HTTPS} !=on
RewriteCond %{HTTP:X-Forwarded-Proto} !https
RewriteRule ^ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
```

Then push. Auto-pull triggers, redeploy fires.

## 6. Cron

hPanel → Advanced → Cron Jobs:

```
* * * * * cd /home/u_jkptg/public_html/portal-jkptg && php artisan schedule:run >> /dev/null 2>&1
```

## 7. Subsequent deploys

```bash
git push origin master
```

Hostinger auto-pulls. SSH and run `./deploy.sh` to refresh caches and Scout indexes (or wire as a Git hook in hPanel).

## 8. Smoke test post-deploy

```bash
curl -I https://www.jkptg.gov.my/
curl -I https://www.jkptg.gov.my/admin/login
curl https://www.jkptg.gov.my/sitemap.xml | head -5
curl https://www.jkptg.gov.my/robots.txt
curl -I https://www.jkptg.gov.my/services            # expect 301 -> /perkhidmatan
curl https://www.jkptg.gov.my/cari?q=tanah | grep -c 'class="text-primary"'
```

Expected:
- `200 OK` on /, /admin/login, /sitemap.xml
- `301` on /services with `Location: /perkhidmatan`
- Search returns multiple result rows for "tanah"

## 9. Rollback

If a deploy breaks:

```bash
cd /home/u_jkptg/public_html/portal-jkptg
git log --oneline -5
git reset --hard <last-good-sha>
./deploy.sh
```

Or via hPanel → Git → Pull (selects an earlier commit).

## 10. Checklist before tender submission

Run `verify-stage8.ps1` against the production URL by replacing `$base = 'http://127.0.0.1:8000'` with `$base = 'https://www.jkptg.gov.my'`. Must return 73/73 PASS, 0 FAIL.

Smoke must show:
- All 16 SPLaSK head meta tags
- All 6 security headers (X-Frame, X-Content-Type, Referrer, Permissions, CSP, COOP)
- HSTS header (present only over HTTPS)
- BM/EN switcher functional
- Chatbot bubble loads and responds to a quick reply
- Admin login works with seeded super-admin (email/password from `UserSeeder.php`)
- `verify-i18n.php`, `verify-content.php`, `verify-admin.php`, `verify-widgets.php`, `verify-chatbot.php`, `verify-search.php` all run clean against production DB
