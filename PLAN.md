# Portal-JKPTG â€” Implementation Plan

**Status:** drafted 2026-05-06 (pre-review)
**Scope:** Stage 7 build of the prototype Laravel app per `SPEC.md` + `DESIGN.md`.
**Hosting target:** Hostinger PHP+MySQL via GitHub auto-pull.
**Timeline guide:** ~5â€“7 sprints (2 weeks each) for tender-quality prototype. Adjust after `/plan-eng-review`.

This plan is structured for execution in fresh chat contexts. Each phase is self-contained with documentation references and verification steps.

---

## Phase 0 â€” Documentation Discovery (Allowed APIs)

### 0.1 Stack versions (locked)

| Package | Version | Source |
|---------|---------|--------|
| `laravel/framework` | 11.x (LTS) | composer require laravel/framework:^11 |
| `livewire/livewire` | 3.x | composer require livewire/livewire:^3 |
| `filament/filament` | 3.3.x | composer require filament/filament:^3.3 |
| `spatie/laravel-permission` | latest 6.x or 7.x | composer require spatie/laravel-permission |
| `filament/spatie-laravel-translatable-plugin` | matches Filament 3.3 | composer require filament/spatie-laravel-translatable-plugin |
| `spatie/laravel-translatable` | latest | composer require spatie/laravel-translatable |
| `laravel/scout` | latest | composer require laravel/scout |
| `tailwindcss` | 3.x | npm install -D tailwindcss postcss autoprefixer |
| `alpinejs` | 3.x (bundled with Livewire 3) | n/a |

### 0.2 Allowed Filament v3.3 APIs

Source: https://github.com/filamentphp/filament/blob/v3.3.28/packages/panels/docs/

| Pattern | API |
|---------|-----|
| Panel access guard | `class User extends Authenticatable implements FilamentUser { public function canAccessPanel(Panel $panel): bool { ... } }` |
| Panel locale switcher (BM/EN) | Filament uses Laravel `app()->getLocale()`. For BM/EN translatable resources install `filament/spatie-laravel-translatable-plugin` and call `$panel->plugin(SpatieLaravelTranslatablePlugin::make()->defaultLocales(['ms','en']))` |
| Translatable form/table actions | `Tables\Actions\LocaleSwitcher::make()` |
| Resource registration | `php artisan make:filament-resource Page` etc. |
| Dashboard widget | `php artisan make:filament-widget StatsOverview --stats-overview` |
| Custom theme | `php artisan filament:upgrade` + `vendor/filament/filament/resources/css/theme.css` Tailwind config |
| 2FA | Filament v3 does NOT ship 2FA. Use `pragmarx/google2fa-laravel` package + custom Filament action page (validated via `/plan-eng-review`) |

### 0.3 Allowed Spatie Permission APIs

Source: https://github.com/spatie/laravel-permission/blob/main/docs/

| Pattern | API |
|---------|-----|
| Trait | `use Spatie\Permission\Traits\HasRoles;` on User model |
| Migration | `php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"` then `php artisan migrate` |
| Create role | `Role::create(['name' => 'super-admin'])` |
| Assign | `$user->assignRole('super-admin')` |
| Check | `$user->hasRole('super-admin')` or `$user->can('edit pages')` |
| Seeder pattern | always call `app()[PermissionRegistrar::class]->forgetCachedPermissions()` before + after create |
| Filament integration | combine `canAccessPanel` with `$user->hasRole('admin role list')` |

### 0.4 Allowed Livewire 3 patterns

Source: https://livewire.laravel.com/docs

| Pattern | API |
|---------|-----|
| Component | `php artisan make:livewire Chatbot/Bubble` |
| Reactive prop | `public bool $open = false; public function toggle() { $this->open = !$this->open; }` |
| Loading state | `<div wire:loading wire:target="ask">Typing...</div>` |
| Real-time validation | `protected $rules = [...]; updated($field) { $this->validateOnly($field); }` |
| Event dispatch | `$this->dispatch('chat-opened');` |
| Listen | `#[On('chat-opened')]` or `protected $listeners = [...]` |
| Layout | `<x-layouts.app><livewire:chatbot.bubble /></x-layouts.app>` |

### 0.5 Allowed Laravel localization

Source: https://laravel.com/docs/11.x/localization

| Pattern | API |
|---------|-----|
| Translation files | `lang/ms/messages.php`, `lang/en/messages.php` |
| In Blade | `{{ __('messages.welcome') }}` |
| Set locale | `app()->setLocale('ms')` or via middleware reading cookie/`Accept-Language` |
| Pluralization | `trans_choice('messages.apples', 5)` |
| Content tables | use `spatie/laravel-translatable` `HasTranslations` trait + `protected $translatable = ['title', 'body']` columns as JSON OR maintain `_bm` / `_en` parallel columns |

### 0.6 Allowed Laravel Scout â€” DB driver

Source: https://laravel.com/docs/11.x/scout#database-engine

| Pattern | API |
|---------|-----|
| Install | `composer require laravel/scout`; `php artisan vendor:publish --provider="Laravel\Scout\ScoutServiceProvider"` |
| Driver | `SCOUT_DRIVER=database` in `.env` |
| Searchable | `use Laravel\Scout\Searchable;` on model + `toSearchableArray()` returns array |
| Search | `Page::search('pengambilan')->get()` |
| No external dep â€” runs on MySQL fulltext + LIKE |

### 0.7 Allowed PDF generation (production-ready, prototype-friendly)

| Package | Use | Note |
|---------|-----|------|
| `barryvdh/laravel-dompdf` | server-side HTMLâ†’PDF for legal docs | works on shared hosting (Hostinger), no Chrome required |
| `spatie/browsershot` | full-fidelity (CSS, fonts, images) | requires Node + Chrome on host â€” DEFER unless Hostinger Premium plan supports |

**Decision for prototype:** start with `barryvdh/laravel-dompdf`. Revisit Browsershot in `/plan-eng-review` if fidelity insufficient.

### 0.8 Allowed audit log

Source: https://github.com/spatie/laravel-activitylog

| Pattern | API |
|---------|-----|
| Install | `composer require spatie/laravel-activitylog` |
| Trait | `use LogsActivity;` on tracked models |
| Config | `protected static $logAttributes = ['*'];` and `protected static $logName = 'page';` |
| Read | `Activity::all()` or via `Spatie\Activitylog\Models\Activity` |
| Filament listing | custom Filament resource over `Activity` model |

### 0.9 Anti-patterns (do NOT do)

- âŒ Filament v4/v5 syntax â€” use v3.3.x only for prototype
- âŒ Use Filament Shield (separate package) for roles â€” Spatie Permission directly is sufficient
- âŒ Mock/skip the `FilamentUser` interface â€” must implement BEFORE any feature work (LESSONS rule 3)
- âŒ Translate via auto-detection â€” explicit toggle only (PPPA + tender-controlled)
- âŒ Browsershot on cheap shared hosting (no Chrome)
- âŒ ALLOWED: Real Anthropic Sonnet 4.6 LLM (CEO review + Eng Q9) - WAS deprecated External LLM call â€” prototype uses canned KB matcher only
- âŒ JSON `$translatable` columns AND parallel `_bm/_en` columns simultaneously â€” pick one approach per table
- âŒ Tailwind via CDN in production â€” compile via `npm run build`
- âŒ Scout meilisearch driver in prototype (deferred â€” `database` driver only)
- âŒ Skip `forgetCachedPermissions()` in role/permission seeders

### 0.10 Open questions to resolve in `/plan-eng-review` (ALL RESOLVED 2026-05-06 - see Eng Review Decisions section at end of PLAN.md)

1. Filament 3.3.x â€” confirm latest patch version
2. spatie/laravel-translatable JSON-in-column vs parallel `_bm`/`_en` columns â€” pick one
3. DomPDF vs Browsershot for PDF on Hostinger â€” confirm Hostinger plan capability
4. Filament 2FA â€” pragmarx/google2fa custom action vs deferred to production
5. Map embed on `/hubungi/ibu-pejabat` â€” Google Maps (key required) vs OpenStreetMap (no key)
6. Audit log â€” Spatie ActivityLog vs custom thin table
7. Visit stats widget â€” seeded fake data only OR add real `visit_logs` table that increments per request

---

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
## Phase 1 â€” Project Scaffold + Dev Environment

**Goal:** New Laravel 11 project with Livewire + Filament installed, runs locally, GitHub repo created, CI does composer + npm build.

### 1.1 Tasks

1. `composer create-project laravel/laravel:^11 portal-jkptg`
2. `cd portal-jkptg && git init && git add -A && git commit -m "chore: initial scaffold"`
3. Add `.gitignore` entries: `.superpowers/`, `.playwright-mcp/`, `node_modules/`, `vendor/`, `public/build/`, `storage/app/public/borang/*`, `.env`
4. `composer require livewire/livewire:^3`
5. `composer require filament/filament:^3.3`
6. `php artisan filament:install --panels`
   - Panel ID: `admin`, path: `admin`
7. `composer require spatie/laravel-permission`
8. `php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"`
9. `composer require spatie/laravel-translatable`
10. `composer require filament/spatie-laravel-translatable-plugin`
11. `composer require laravel/scout`
12. `composer require spatie/laravel-activitylog`
13. `composer require barryvdh/laravel-dompdf`
14. `npm install -D tailwindcss postcss autoprefixer @tailwindcss/forms @tailwindcss/typography`
15. `npx tailwindcss init -p`
16. Configure `tailwind.config.js` with tokens from `DESIGN.md` Â§2 (colors, fonts, spacing)
17. SKIPPED - Eng review Q5: 2FA deferred to production phase. Do NOT install `pragmarx/google2fa-laravel` in prototype. (Note kept for reference â€” install but don't wire until Phase 2.x)
18. Create GitHub repo `Portal-JKPTG-prototype`. Push initial scaffold.
19. Add minimal CI workflow `.github/workflows/ci.yml`: composer install, npm install, npm run build, php artisan test (basic smoke).
20. Configure Hostinger pull (deferred to Phase 14).

### 1.2 Verification

- [ ] `php artisan serve` runs without error
- [ ] `/admin/login` reachable
- [ ] `npm run build` produces `public/build/manifest.json`
- [ ] `php artisan filament:upgrade` runs clean
- [ ] All packages in `composer show` match Phase 0.1 versions
- [ ] `git log` shows initial commit pushed to GitHub

### 1.3 Anti-pattern guards

- Do NOT generate Filament admin user yet (Phase 2.3)
- Do NOT touch `User` model yet (Phase 2.1)

---

## Phase 2 â€” Auth + FilamentUser + Roles (LESSONS rule 3)

**Goal:** User model implements `FilamentUser`, Spatie roles wired, sample users seeded, login works, role gates enforced. **Done before any feature work.**

### 2.1 Update User model

Reference: SPEC Â§6.1, DESIGN.md (no UI yet â€” backend only)

```php
// app/Models/User.php
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{
    use HasRoles;

    public function canAccessPanel(Panel $panel): bool
    {
        if ($panel->getId() === 'admin') {
            return $this->hasAnyRole(['super-admin', 'editor', 'viewer']);
        }
        return true;
    }
}
```

### 2.2 Migrations

1. `php artisan migrate` (default + Spatie permission tables)
2. Add migration: `add_metadata_to_users_table` â€” fields: `last_login_at`, `last_login_ip`, `avatar_path` (nullable), `two_fa_secret` (nullable, encrypted), `two_fa_recovery_codes` (nullable, encrypted)

### 2.3 Roles + Permissions seeder

```
database/seeders/RolesAndPermissionsSeeder.php

- Reset cache via PermissionRegistrar
- Permissions: page.* news.* tender.* form.* faq.* chatbot.* user.* audit.* settings.*
- Roles:
  super-admin â†’ Permission::all()
  editor â†’ page.* news.* tender.* form.* faq.* chatbot.*
  viewer â†’ page.read news.read tender.read audit.read
  citizen â†’ no permissions (front-side only)
- Reset cache again
```

### 2.4 Sample users seeder

```
database/seeders/SampleUsersSeeder.php

User::create([
  'name' => 'Super Admin',
  'email' => 'admin@jkptg.demo',
  'password' => Hash::make('password'),
])->assignRole('super-admin');

// + editor, viewer, citizen with same pattern (from SPEC Â§6.1)
```

### 2.5 Custom login page

Reference: SPEC Â§6.3

- `routes/web.php`: `Route::view('/login', 'auth.login')`
- `resources/views/auth/login.blade.php`:
  - Hero: JKPTG logo + "Log Masuk Portal Rasmi"
  - Card 1: myID button DISABLED with tooltip
  - Card 2: 4 sample-user one-click forms (POST to standard Laravel auth `/login` endpoint with prefilled credentials hidden)
  - Card 3: manual email + password form
  - Footer note: "Pelawat? Tidak perlu log masuk untuk akses kandungan."
- Use Laravel Breeze auth scaffolding for form handler if convenient (`composer require laravel/breeze --dev && php artisan breeze:install blade`)

### 2.6 Filament admin override

- Set Filament login redirect: `Filament::serving(fn() => Filament::registerNavigationGroups([...]))` in `AppServiceProvider`
- Register sample-user one-click flow uses standard Laravel `Auth::login()` â†’ redirects to `/admin` if user has admin roles

### 2.7 Verification

- [ ] `php artisan db:seed --class=RolesAndPermissionsSeeder` succeeds
- [ ] `php artisan db:seed --class=SampleUsersSeeder` succeeds
- [ ] All 4 sample users can log in via `/login`
- [ ] super-admin/editor/viewer redirect to `/admin` and see Filament panel
- [ ] citizen redirects to `/akaun` (which is empty for now â€” Phase 4)
- [ ] Logout returns to `/`
- [ ] Database: `users`, `roles`, `permissions`, `model_has_roles`, `role_has_permissions` tables populated

### 2.8 Anti-patterns

- âŒ Hard-coding email-based access ("@jkptg.gov.my") â€” use roles
- âŒ Skipping `forgetCachedPermissions()` in seeder
- âŒ Plain-text passwords in seeders â€” use `Hash::make()`

---

## Phase 3 â€” Database Schema + Content Seeders

**Goal:** All content tables created, seeded from `reference/scrape/`, ready for both public consumption and Filament resources.

### 3.1 Migrations

Reference: SPEC Â§10

Create migrations (one per table):
- `pages` â€” id, slug (unique), title (json translatable), body (json translatable), parent_id, sort, published, meta_title, meta_description, timestamps
- `services` â€” id, slug, name (json), summary (json), sop_path, carta_alir_path, sort, active
- `news` â€” id, slug, title (json), banner_path, body (json), published_at, expires_at, author_id
- `tenders` â€” id, slug, title (json), doc_path, deadline, status, created_at
- `forms` â€” id, slug, name (json), file_path, category, downloads_count default 0
- `faqs` â€” id, category, question (json), answer (json), sort, active
- `cawangan` â€” id, state, address (json), phone, fax, email, lat, lng, opening_hours
- `chatbot_knowledge` â€” id, slug, question_bm, question_en, answer_bm, answer_en, keywords (json), source_url, category, active, updated_at
- `chatbot_quick_replies` â€” id, label_bm, label_en, payload_query, sort, active
- `chat_sessions` â€” id, user_id (nullable, FK), session_uuid (uuid index), started_at, ended_at, message_count, locale
- `chat_messages` â€” id, session_id (FK), role (enum: user|bot), content (encrypted text), citation, created_at
- `chatbot_settings` â€” id, mode (enum: ai|normal), quota_used_month default 0, quota_limit default 100000, alert_threshold default 80
- `audit_logs` (handled by spatie/laravel-activitylog migration â€” `php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider" --tag="activitylog-migrations"`)
- `settings` â€” id, key (unique), value (json â€” translatable), description
- `visit_stats` (seeded fake) â€” id, date, page_path, views, unique_visitors, country (nullable)
- Legacy redirect map: read from config/file at runtime; no DB table

### 3.2 Models with Translatable trait

```php
use Spatie\Translatable\HasTranslations;
use Laravel\Scout\Searchable;

class Page extends Model
{
    use HasTranslations, Searchable;

    public $translatable = ['title', 'body', 'meta_title', 'meta_description'];
    protected $fillable = ['slug','title','body','parent_id','sort','published','meta_title','meta_description'];
    protected $casts = ['published' => 'boolean'];

    public function toSearchableArray(): array
    {
        return [
            'title_bm' => $this->getTranslation('title', 'ms'),
            'title_en' => $this->getTranslation('title', 'en'),
            'body_bm' => strip_tags($this->getTranslation('body', 'ms')),
            'body_en' => strip_tags($this->getTranslation('body', 'en')),
        ];
    }
}
```

Same pattern for Service, News, Tender, Form, FAQ, Cawangan.

### 3.3 Content seeder â€” JKPTGContentSeeder

Reference: SPEC Â§11.1, scrape data at `reference/scrape/`

Strategy:
- Read `reference/scrape/02-html/_index.csv` â†’ metadata
- Use cheerio-equivalent (Symfony DomCrawler) to strip body HTML from `reference/scrape/02-html/{slug}.html`
- Skip header/footer/megamenu DOM nodes; keep main content
- Translate content: BM stays as scraped, EN seeded as English version (manually curated for top 10 pages, "(BM only)" placeholder fallback for the rest)
- Copy PDFs from `reference/scrape/04-pdfs/` â†’ `storage/app/public/borang/`
- Seed counts (per SPEC Â§11.1):
  - Pages Ã— 25
  - Services Ã— 8
  - News Ã— 10
  - Tenders Ã— 5
  - Forms Ã— 14
  - FAQs Ã— 30
  - Chatbot KB Ã— 50
  - Cawangan Ã— 15
- Settings: contact info (HQ Aras 4 Podium 1 Menara PETRA, +603 8000 8000, webadmin@jkptg.gov.my), social links, footer copy (BM + EN)

### 3.4 Visit stats seeder (fake data)

```
VisitStatsSeeder generates 90 days Ã— 30 page paths Ã— random views
For dashboard widget realism in Phase 8
```

### 3.5 Verification

- [ ] `php artisan migrate:fresh --seed` runs clean
- [ ] All tables populated; row counts match spec
- [ ] `Page::where('slug', 'visi-misi')->first()->getTranslation('title', 'ms')` returns BM title
- [ ] `Page::search('pengambilan')->get()` returns matching pages (Scout DB driver)
- [ ] PDF files present in `storage/app/public/borang/` and accessible at `/storage/borang/...`
- [ ] `php artisan storage:link` creates the symlink

### 3.6 Anti-patterns

- âŒ Don't seed real production data â€” only scrape-derived sample
- âŒ Don't include EN translations as auto-machine-translated (mark `[BM only]` fallback per SPEC Â§6.5)
- âŒ Don't load PDF files via DB blob â€” store on disk, reference path

---

## Phase 4 â€” Public Layouts (header, megamenu, footer, accessibility, BM/EN toggle)

**Goal:** Master Blade layout `layouts.public` with all chrome from DESIGN.md Â§5 working. No content yet â€” empty body slot.

### 4.1 Tasks

1. Create `resources/views/layouts/public.blade.php`:
   - 3-tier header (utility / brand / megamenu) per DESIGN Â§5.10
   - Slot for content
   - Footer per DESIGN Â§5.9
   - Floating chatbot bubble placeholder (`<livewire:chatbot.bubble />` â€” component shells in Phase 9)
2. Header components:
   - `<x-header.utility-bar>` â€” search icon, BM/EN toggle, accessibility trigger, login button
   - `<x-header.brand-bar>` â€” Jata + JKPTG logo + 4 Pautan Utama
   - `<x-header.megamenu>` â€” 6 top-level + sub-tree panels (data sourced from `config/megamenu.php`)
3. Footer components:
   - `<x-footer.contact>` â€” pulls from `settings` table
   - `<x-footer.qr>` â€” QR code SVG generator (Bacon QR Code package or static asset)
   - `<x-footer.kata-kunci>` â€” popular keywords (from `settings` or top-N most-searched)
   - `<x-footer.bilangan-pelawat>` â€” pulls from `visit_stats` aggregate
   - `<x-footer.mandatory-links>` â€” Penafian, Dasar Web, etc.
4. `LocaleMiddleware` â€” reads `?lang=` query, persists to cookie, applies `app()->setLocale()`
5. BM/EN toggle: Blade component issuing `?lang=ms` or `?lang=en` link
6. Accessibility panel â€” Livewire component `App\Livewire\Public\AccessibilityPanel` with localStorage-backed state (use Alpine `$persist`)
7. `tailwind.config.js`: full token set from DESIGN Â§2
8. `resources/css/app.css`: import Tailwind layers + design tokens as CSS variables
9. `vite.config.js`: standard Laravel + Tailwind setup, asset bundle
10. `routes/web.php`: temporary route `Route::view('/', 'public.placeholder')` to test layout

### 4.2 Megamenu data structure

```php
// config/megamenu.php
return [
    [
        'label_bm' => 'Utama',
        'label_en' => 'Home',
        'href' => '/',
    ],
    [
        'label_bm' => 'Perkhidmatan',
        'label_en' => 'Services',
        'href' => '/perkhidmatan',
        'children' => [
            ['label_bm' => 'Pengambilan Tanah', 'label_en' => 'Land Acquisition', 'href' => '/perkhidmatan/pengambilan-tanah'],
            // ... per SPEC Â§5.1
        ],
    ],
    // ... 4 more top-level
];
```

### 4.3 Accessibility persistence (Alpine.js)

```html
<div x-data="{
  fontSize: $persist('std').as('a11y-font'),
  contrast: $persist(false).as('a11y-contrast'),
  colorBlind: $persist(false).as('a11y-cb')
}" :class="{ 'text-lg': fontSize === 'lg', 'high-contrast': contrast, 'cb-mode': colorBlind }">
```

### 4.4 Verification

- [ ] `/` renders header + footer with no errors
- [ ] BM â†” EN toggle works, persists across navigation
- [ ] Accessibility panel toggles persist via Alpine `$persist`
- [ ] All 6 megamenu top-level items render with sub-trees on **click + keyboard** (no hover-open per design review 2026-05-06)
- [ ] Arrow keys navigate within open sub-tree; ESC closes; Tab cycles; Enter activates
- [ ] Mobile (375px): hamburger menu opens drawer with accordion sub-trees
- [ ] 4 Pautan Utama icons visible top-right; mobile collapses to dropdown
- [ ] Keyboard: tab cycles through nav, ESC closes overlays
- [ ] Tailwind classes in DESIGN Â§2 spacing/colors apply correctly
- [ ] `npm run build` produces optimized CSS (â‰¤ 60 KB gzipped)
- [ ] Lighthouse a11y on `/` â‰¥ 90

### 4.5 Anti-patterns

- âŒ Hardcoded copy in Blade â€” always `__('messages.key')` or content table lookup
- âŒ Non-keyboard megamenu (hover-only)
- âŒ Tailwind via CDN â€” must compile via Vite
- âŒ Font from Google CDN in production â€” self-host

---

## Phase 4.5 â€” Interaction State Matrix (Design review accepted)

**Goal:** every Livewire component + page has explicit empty / loading / error / success states. No "No items found." literals.

| Feature | Loading | Empty | Error | Success |
|---------|---------|-------|-------|---------|
| Borang search | skeleton row Ã— 5 | "Tiada borang sepadan. Cuba kata kunci lain atau hubungi webmaster@jkptg." + chatbot CTA | "Carian tergendala. Cuba semula." + retry btn | results table |
| News listing | skeleton card Ã— 6 | "Tiada berita terkini." + JATA-watermark BG | toast: "Sumber tergendala" | cards w/ banner + date |
| Tender listing | skeleton row Ã— 5 | "Tiada sebut harga aktif." + "Lihat arkib" link | toast | rows w/ deadline countdown |
| Service detail (no SOP) | skeleton | "SOP belum diterbitkan. Hubungi bahagian." + email | n/a | SOP rendered |
| Chatbot (first open) | n/a | greeting + 5 quick-reply chips + privacy notice | "Saya belum tahu. Webmaster: ..." | bot reply + citation + sources |
| Chatbot (mid-conv typing) | typing dots animation | n/a | "Sambungan tergendala. Cuba semula." | bubble appended |
| Chatbot (LLM quota out) | n/a | n/a | n/a | "Mod ringkas aktif" badge + canned answer |
| Filament dashboard | skeleton bars | "Belum ada data" + admin-doc link | toast top-right | widgets render |
| Aduan submit | spinner on btn (disabled) | n/a | inline field-level errors | green confirm card + reference no. e.g. `JKPTG-MB-2026-00123` |
| Search results | skeleton rows | "Tiada hasil untuk '{q}'." + 5 popular searches + chatbot CTA | "Carian gagal. Hubungi webmaster." | grouped tabs (Pages/Services/News/Forms/FAQ) |
| Login | spinner on btn | n/a | inline "E-mel atau kata laluan tidak betul." | redirect by role |
| Persona landing | n/a | n/a | n/a | persona-curated content |
| /akaun (citizen) | skeleton | "Belum ada lawatan terdahulu. Berikut adalah perkhidmatan teras..." + 3 CTAs | toast | greeted by name + last visit + topic feed |
| Galeri | skeleton grid | "Belum ada media." | toast | masonry/grid |
| Cawangan listing | skeleton rows Ã— 15 | n/a (always 15) | n/a | table rendered |
| Peta Laman | n/a | n/a | n/a | full nested tree (replaces existing portal's empty state) |

**Empty-state rules:**
- Always include a primary CTA when applicable (search â†’ chatbot, listing â†’ search, dashboard â†’ docs)
- Never show "No items found." â€” always Bahasa Melayu (per locale) + actionable guidance
- Use JATA-watermark or muted icon, never blank space

**Loading-state rules:**
- Skeleton matches final layout (avoid layout shift)
- Skeletons appear after 200ms delay (avoid flash on fast loads)
- Use Livewire `wire:loading` on bound elements

**Error-state rules:**
- Field-level errors inline (Filament + Livewire native)
- System errors â†’ toast top-right with retry where applicable
- Never expose stack traces to public users

---

## Phase 5 â€” Homepage + Persona Landings

**Goal:** Homepage renders all 11 sections from SPEC Â§5.3. 3 persona landing pages exist.

### 5.1 Homepage sections

Reference: SPEC Â§5.3, DESIGN Â§6.2 + Â§7

Build as Livewire components for each major section:
- `<livewire:public.hero>` â€” photographic hero with 3 persona doors (DESIGN Â§7)
- `<livewire:public.perkhidmatan-strip>` â€” 6 service tiles
- `<livewire:public.maklumat-terkini>` â€” 3-tab (Berita / Pengumuman / Tender) + sidebar `<livewire:public.kalendar>`
- `<livewire:public.info-jkptg-strip>` â€” 5 quick-link icons
- `<livewire:public.pautan-agensi>` â€” logo carousel with 12+ partner agency logos

### 5.2 Persona landings (Design review accepted: expanded content)

Each persona landing follows this template (per DESIGN Â§6.3):

```
[breadcrumb: Laman Utama â€º Akses â€º {Persona}]
[h1: persona-aware greeting]
[h3: tagline â€” what they came for]

â”Œâ”€ Top 3 task cards (persona-curated) â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”
â”‚ [icon] [task name]    [icon] [task name]    [icon] [task name]        â”‚
â”‚ [body]                [body]                [body]                    â”‚
â”‚ [primary CTA â†’]       [primary CTA â†’]       [primary CTA â†’]           â”‚
â””â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”˜

â”Œâ”€ Embedded chatbot CTA banner â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”
â”‚ "Tidak pasti? Tanya AI Chatbot JKPTG."   [ðŸ’¬ Mula Sembang]            â”‚
â””â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”˜

â”Œâ”€ Recent activity (persona-relevant news/tenders) â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”
â”‚ [3 cards filtered by persona tags]                                     â”‚
â””â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”˜

[breadcrumb back to /]
```

**`/akses/orang-awam`** â€” citizen-focused
- h1: "Selamat datang, warga Malaysia."
- h3: "Perkhidmatan tanah persekutuan untuk anda."
- Top 3 cards: Pembahagian Pusaka Kecil Â· Pengambilan Tanah Â· Pajakan Tanah Persekutuan
- Recent: Pengumuman + Berita umum

**`/akses/kementerian-jabatan`** â€” govt clients
- h1: "Selamat datang, agensi kerajaan."
- h3: "Permohonan tanah persekutuan untuk projek kerajaan."
- Top 3 cards: Pengambilan Tanah (Akta 1960) Â· Penyewaan Tanah Persekutuan Â· Penyerahan Balik
- Recent: Pekeliling + Tender/Sebut Harga + MyLAND link-out

**`/akses/warga-jkptg`** â€” internal staff
- h1: "Selamat datang, warga JKPTG."
- h3: "Pintasan kepada perkhidmatan dalaman."
- Top 3 cards: Intranet (link-out) Â· Direktori Bahagian Â· MyHRMIS (link-out)
- Recent: Pengumuman dalaman + announcements
- Visible login CTA top of card if not authed

Routes:
- `/akses/orang-awam`
- `/akses/kementerian-jabatan`
- `/akses/warga-jkptg`

### 5.2.1 Component reuse (DRY per CEO review Â§5)

```
<x-public.persona-landing
  :persona="$persona"
  :h1="$greeting"
  :tagline="$tagline"
  :top-cards="$cards"
  :recent="$news"
  :show-login="$persona === 'warga-jkptg' && !auth()->check()"
/>
```

Single Blade component drives all 3 landings + can be extended for additional personas.

### 5.3 Tasks

1. Source hero photo (placeholder Unsplash â†’ final JKPTG asset)
2. Implement `Hero` component with photo overlay, CTAs, 3 persona doors
3. Implement service strip pulling 6 services from DB
4. Implement news/announcements 3-tab (Livewire reactive)
5. Implement Kalendar Aktiviti widget â€” read from `events` (or `news` filtered by type)
6. Implement Info JKPTG icon strip (5 links to: myID stub, Infografik, Consent Online stub, Data Terbuka, MyHRMIS link-out)
7. Implement Pautan Agensi carousel (Alpine.js, no autoplay) **â€” design review constrained:** monochrome treatment (`filter: grayscale(1)` default + color on hover), uniform 80px height, 12 logos max, manual nav arrows, NO autoplay (PPPA-friendly per DESIGN Â§8). If insufficient real partner logos, kill section entirely (per DESIGN Â§14 â€” no decoration for decoration's sake).
8. Build 3 persona landing pages

### 5.4 Verification

- [ ] `/` renders all 11 sections in correct order per SPEC Â§5.3
- [ ] Hero h1 uses `display-1` (60/64 desktop, 40/44 mobile)
- [ ] Hero has photo + dark overlay; legible text
- [ ] 3 persona doors are clickable and route correctly
- [ ] 6 service tiles wrap to 2Ã—3 mobile, 6Ã—1 desktop
- [ ] News tab switching works without page reload (Livewire)
- [ ] Click-depth contract: any service from homepage in 2 clicks
- [ ] Persona landings load without errors

### 5.5 Anti-patterns

- âŒ Carousel with autoplay (PPPA-friendly: no auto-rotation)
- âŒ Decorative images without alt
- âŒ Hard-coded news data â€” pull from DB

---

## Phase 6 â€” Service + Korporat + Sumber + Hubungi pages

**Goal:** All ~70 page templates from SPEC Â§9 functional.

### 6.1 Service pages (`/perkhidmatan/*`)

- `/perkhidmatan` index â€” grid of 8 service cards
- `/perkhidmatan/{slug}` â€” service detail with sidebar (SOP, Carta Alir, Borang, Senarai Semak, Pekeliling)
- `/perkhidmatan/{slug}/sop` â€” SOP page (with print stylesheet support)
- `/perkhidmatan/{slug}/carta-alir` â€” Carta Alir page

### 6.2 Korporat pages (`/korporat/*`)

- `/korporat` index â€” section landing
- 11 sub-pages: perutusan-kp, latar-belakang, visi-misi, piagam-pelanggan (+ prestasi), fungsi-jabatan, carta-organisasi, pengurusan-tertinggi (incl. CDO), profil-bahagian Ã— 11
- `/cawangan/{state}` â€” 15 branch detail pages

### 6.3 Panduan & Borang

- `/panduan` index
- `/panduan/borang` â€” searchable PDF library with category filter (Livewire reactive search)
- `/panduan/sop` â€” alphabetical SOP index
- `/panduan/akta/{slug}` â€” Ã— 8 legal docs
- `/panduan/peperiksaan` â€” exam info
- `/panduan/tabung-khas-strata`

### 6.4 Sumber

- `/sumber` index
- `/sumber/galeri/{type}` â€” gambar/audio/video gallery
- `/sumber/pelan-strategik` â€” Ã— 3 strategic plans
- `/sumber/data-terbuka` â€” dataset listing table
- `/sumber/infografik` â€” gallery
- `/sumber/arkib/{type}` â€” Ã— 4 archive types

### 6.5 Hubungi

- `/hubungi/ibu-pejabat` â€” full address + map embed (OpenStreetMap, no key required â€” to be confirmed in eng review)
- `/hubungi/cawangan` â€” table of 15 branches
- `/hubungi/aduan` â€” Maklum Balas form (in-portal Livewire, captures to DB)
- `/soalan-lazim` â€” FAQ accordion, 5 categories

### 6.6 Mandatory PPPA pages

- `/penafian`, `/dasar-web`, `/dasar-keselamatan`, `/dasar-privasi`, `/panduan-pengguna`, `/hak-cipta`, `/peta-laman`

### 6.7 Verification

- [ ] All routes in SPEC Â§9 return 200
- [ ] Breadcrumb on every interior page
- [ ] BM/EN toggle works on every page
- [ ] Print stylesheet applies on `/perkhidmatan/*/sop`, `/panduan/akta/*`, `/korporat/piagam-pelanggan`
- [ ] PDF download works on top 5 legal pages (DomPDF)
- [ ] Maklum Balas form submits to DB and shows confirmation with reference number
- [ ] Peta Laman renders full nested tree (fixes existing portal's empty Peta Laman)

### 6.8 Anti-patterns

- âŒ Inline styles â€” use Tailwind classes only
- âŒ Hard-coded copy â€” always content tables or `__()`

---

## Phase 7 â€” Filament Admin Resources

**Goal:** Filament panel CRUD for all content. Role-gated. BM+EN translatable.

### 7.1 Resources to build

Reference: SPEC Â§9.1

For each resource, run `php artisan make:filament-resource {Name}` then customize:

| Resource | Form fields | Table columns | Filters |
|----------|-------------|---------------|---------|
| Page | slug, title (translatable BM/EN), body (rich-text BM/EN), parent (select), sort, published, meta_* | title, slug, parent, published, updated_at | published |
| Service | slug, name (translatable), summary (translatable), sop file, carta-alir file, sort, active | name, slug, active, updated_at | active |
| News | slug, title (translatable), banner upload, body (translatable rich), published_at, expires_at, author | title, status, published_at, expires_at | status, year |
| Tender | slug, title, doc upload, deadline, status | title, deadline, status | status |
| Form | slug, name (translatable), file upload, category | name, category, downloads_count | category |
| FAQ | category, question (translatable), answer (translatable), sort, active | question, category, active | category |
| Cawangan | state, address (translatable), phone, fax, email, lat, lng, hours | state, phone | state |
| ChatbotKnowledge | slug, question_bm, question_en, answer_bm (md), answer_en (md), keywords (tag), source_url, category, active | question_bm, category, active | category |
| ChatbotQuickReply | label_bm, label_en, payload_query, sort, active | label_bm, sort | active |
| ChatbotFlow (custom page) | visual flow builder demo | n/a | n/a |
| User | name, email, password, role assignment (multi-select), avatar, two_fa toggle | name, email, role, last_login_at | role |
| AuditLog | (read-only) | causer, action, model, created_at | model, action |
| Setting | key, value (translatable JSON) | key | n/a |

### 7.2 Translatable plugin setup

```php
// app/Providers/Filament/AdminPanelProvider.php

use Filament\SpatieLaravelTranslatablePlugin;

return $panel
    ->plugin(
        SpatieLaravelTranslatablePlugin::make()
            ->defaultLocales(['ms', 'en'])
    );
```

In each Resource form for translatable fields, wrap with locale switcher:

```php
Tables\Actions\LocaleSwitcher::make()
```

### 7.3 Role-based resource visibility

In each Resource:

```php
public static function canViewAny(): bool
{
    return auth()->user()->can('page.read');
}

public static function canCreate(): bool
{
    return auth()->user()->can('page.create');
}

// ...edit/delete
```

### 7.4 Migration capability page

`app/Filament/Pages/Migration.php` â€” super-admin only:
- Upload form for SQL or JSON
- Preview rows
- Confirm + execute import (Pages, News, Forms tables only)
- MyLAND import button visible but disabled with "Coming soon" tooltip

### 7.5 GUI Flow Builder (capability demo)

`app/Filament/Pages/ChatbotFlowBuilder.php`:
- Visualize Qâ†’Aâ†’next-Q tree as cards
- Drag-and-drop placeholder (non-executable)
- Saves as JSON to `chatbot_flows` (new table)

### 7.6 Verification

- [ ] All resources accessible at `/admin/{resource}`
- [ ] super-admin sees all resources
- [ ] editor sees only Pages/News/Tenders/Forms/FAQ/ChatbotKB
- [ ] viewer sees read-only views
- [ ] Translatable fields show LocaleSwitcher action
- [ ] Audit log captures every CRUD action
- [ ] File uploads work (banner, sop, carta-alir, borang) â†’ `storage/app/public/`
- [ ] Filament theme matches DESIGN Â§12 (navy primary)

### 7.7 Anti-patterns

- âŒ Override Filament resource skeleton too aggressively â€” use Filament conventions
- âŒ Use Filament Shield (separate package) â€” Spatie Permission directly is enough
- âŒ Skip `canViewAny()` â€” every resource needs role gate

---

## Phase 8 â€” Filament Dashboard + Audit Log

**Goal:** Admin dashboard with widgets per SPEC Â§9.2. Audit log readable.

### 8.1 Widgets

Reference: SPEC Â§9.2

For each: `php artisan make:filament-widget {Name} --stats-overview` (or `--chart-widget`)

| Widget | Type | Data source |
|--------|------|-------------|
| VisitorStatsOverview | Stats overview | `visit_logs` aggregate (real per Phase 12.6) |
| TopPagesChart | Chart (bar) | `visit_logs` group by page_path |
| LatestNewsTable | Table | `news` order desc |
| PendingTendersTable | Table | `tenders` where deadline >= today |
| AuditLogFeed | List | spatie/laravel-activitylog last 20 |
| ChatbotQuotaProgress | Stats overview | `chatbot_settings.quota_used / quota_limit` |
| ChatbotLlmHealth | Stats overview | `llm_api_logs` last hour: success rate, avg latency, fallback count |
| ChatbotLlmCost | Stats overview | `chatbot_settings.cost_month_to_date / cost_cap` (kill-switch indicator) |
| QueueDepth | Stats overview | jobs table count + visit_logs queue depth |
| FormsDownloadsChart | Chart | `forms.downloads_count` top 10 |
| ActivityCalendar | Calendar | events seeded |

### 8.2 Quick actions panel

Filament page action group on dashboard: "Pages baharu", "News baharu", "Tender baharu" â†’ modal forms.

### 8.3 Audit log resource

- Read-only Filament resource over `Activity` model from spatie/laravel-activitylog
- Columns: causer (user), description, subject_type, subject_id, properties (JSON viewer), created_at
- Filter by model type, date range, user

### 8.4 Verification

- [ ] `/admin` (super-admin) shows all widgets with seeded data
- [ ] Widgets re-render when underlying tables change
- [ ] Audit log resource lists entries from CRUD actions in Phase 7
- [ ] Quick actions launch modal forms successfully

### 8.5 Anti-patterns

- âŒ Real-time websockets in widgets â€” use polling or page-load refresh
- âŒ Querying full table on every widget load â€” use eager-loaded aggregates

---

## Phase 9 â€” Chatbot

**Goal:** Floating bubble + side panel works on every public page. Canned responses with source citations. Admin manages KB via Filament.

### 9.1 Tasks (CEO-review accepted: real LLM integration with fallback chain)

Reference: SPEC Â§7, DESIGN Â§5.7

1. `php artisan make:livewire Public/Chatbot/Bubble`
2. Component state: `$open`, `$messages`, `$input`, `$sessionUuid`
3. **LLM driver abstraction** (Eng review Q9: Anthropic + Canned only - OpenAiDriver deferred to production) (CEO review Â§10):
   ```php
   App\Services\Llm\LlmDriver           // interface
   App\Services\Llm\AnthropicDriver     // Claude Sonnet 4.6 (Eng review Q4 pick)
   App\Services\Llm\CannedDriver        // KB-matcher fallback
   ```
   Config switch: `config/chatbot.php` â†’ `driver`. Default `canned`. Tender demo flips to `anthropic` or `openai`.
4. On `$this->ask()`:
   - Append user message + sanitize input (strip `system:`, `<|...|>`, control sequences)
   - Rate-limit: 10 LLM calls / IP / hour (Laravel `RateLimiter`)
   - Cost cap: if `chatbot_settings.cost_month_to_date >= cost_cap` (default **RM 200 / ~$40 USD = ~7.5k Sonnet 4.6 chats**, Eng review Q6), force-flip to `canned` driver + admin email alert. Email alert also fires at 80% threshold (~RM 160). Monthly reset via Hostinger cron `0 0 1 * * php artisan llm:reset-cap` (Eng review Q14).
   - Simulate typing 1.2s (`sleep(1)` or wire-loading delay)
   - **Fallback chain (CEO review Â§2):**
     ```php
     try {
       $response = $driver->ask($input, $context); // 5s timeout, retry once
     } catch (RateLimitError|TimeoutError|InvalidResponseError $e) {
       Log::channel('chatbot')->warning('llm_fallback', ['reason' => get_class($e)]);
       $response = (new CannedDriver)->ask($input, $context);
     } catch (\Throwable $e) {
       Log::channel('chatbot')->error('llm_fatal', ['msg' => $e->getMessage()]);
       AdminAlert::dispatch('chatbot LLM down');
       $response = ['answer' => 'Saya belum tahu. Cuba semula.', 'citation' => null];
     }
     ```
   - Append bot message with citation
   - Persist to `chat_messages` if user authed; persist to `llm_api_logs` always (cost tracking)
5. Quick replies: pull `chatbot_quick_replies` order by sort
6. Side panel UI per DESIGN Â§5.7
7. Privacy notice on first open: store dismiss-state in session
8. Locale-aware: pick `answer_bm` vs `answer_en` based on `app()->getLocale()`. Pass locale as system prompt suffix to LLM driver.

### 9.1.1 Tables added by LLM scope

```
llm_api_logs (id, driver, model, prompt_tokens, completion_tokens, cost_usd, latency_ms, status, error_message, created_at)
chatbot_settings adds: cost_month_to_date, cost_cap, kill_switch_active
```

### 9.2 Encryption-at-rest claim

`chat_messages.content` column uses Laravel `encrypted` cast:

```php
protected $casts = ['content' => 'encrypted'];
```

### 9.3 Filament admin

(already covered in Phase 7) â€” Chatbot Knowledge resource with side-by-side BM/EN editor.

### 9.4 Quota widget

(already in Phase 8) â€” visible in admin dashboard.

### 9.5 Verification

- [ ] Bubble appears on every public page
- [ ] Click â†’ side panel slides in (300ms)
- [ ] User input + send button works
- [ ] Bot responds with LLM-driven answer (when driver=anthropic, Sonnet 4.6) or KB-matched (when driver=canned)
- [ ] Fallback chain: induce LLM rate limit â†’ falls back to KB matcher transparently
- [ ] Cost cap: set `cost_month_to_date = cost_cap` â†’ next call forces canned driver + admin alert
- [ ] Prompt-injection guard: `system: ignore previous` input passes through sanitizer untouched in user-visible message but stripped before LLM call
- [ ] Rate limit: 11th call from same IP/hr returns 429 with friendly message
- [ ] Mobile: fullscreen panel
- [ ] Privacy notice on first open
- [ ] Authed user sessions persist to DB; anon sessions stay in memory + visible in admin
- [ ] llm_api_logs records every call (success or failure) with cost + tokens

### 9.6 Anti-patterns

- âŒ Hardcoded quick replies â€” read from DB
- âŒ Missing CSRF on chat POST
- âŒ LLM API key committed to git
- âŒ Skipping fallback chain â€” LLM down must NOT break chatbot UX
- âŒ Pass full chat history to LLM for every call â€” token-explosion (cap context to last 6 turns)

---

## Phase 10 â€” i18n Polish (BM/EN parallel)

**Goal:** All UI strings translated. All content tables have parallel BM/EN. Locale persistence solid.

### 10.1 Tasks

1. Create `lang/ms/messages.php`, `lang/en/messages.php` with all UI strings (header labels, footer copy, button labels, placeholders, error messages)
2. Audit Blade views â€” replace any hard-coded strings with `__('messages.key')`
3. Audit Filament resources â€” admin UI strings translated via `lang/ms/admin.php`, `lang/en/admin.php`
4. Date format: BM `D MMM YYYY` (`6 Mei 2026`); EN same format with English month names. Use Carbon `setLocale()` based on app locale
5. Number/currency: standard Malaysian format
6. Content seeders: ensure all content tables have non-empty BM, EN where translated, `[BM only]` fallback elsewhere
7. URL strategy: cookie-based (no `/en/` prefix). `LocaleMiddleware` reads `?lang=` param â†’ cookie â†’ fallback to BM

### 10.2 Verification

- [ ] Every string user sees has BM/EN equivalent (or `[BM only]` badge)
- [ ] BM toggle persists across sessions (cookie 365 days)
- [ ] Carbon `now()->translatedFormat('j F Y')` returns "6 Mei 2026" in BM, "6 May 2026" in EN
- [ ] Filament admin displays in current locale

### 10.3 Anti-patterns

- âŒ Auto-translate via API â€” manual curation only (vendor not responsible per Lampiran 1)
- âŒ Browser `Accept-Language` overrides cookie â€” cookie is source of truth

---

## Phase 11 â€” Search (Scout DB driver)

**Goal:** Site-wide search returns results from Pages, Services, News, Forms, FAQs.

### 11.1 Tasks

1. `.env`: `SCOUT_DRIVER=database`
2. `php artisan vendor:publish --provider="Laravel\Scout\ScoutServiceProvider"`
3. Add `Searchable` trait to: Page, Service, News, Tender, Form, FAQ, Cawangan
4. Define `toSearchableArray()` per model with both BM + EN columns
5. `php artisan scout:import "App\Models\Page"` for each model
6. Build `/search` route + Livewire `Public\SearchResults` component
7. Build header search box â†’ submits to `/search?q=...`
8. Search results page: tabbed by model type (All / Pages / Services / News / Forms / FAQ)
9. Highlight matched terms in results
10. Pagination

### 11.2 Verification

- [ ] Header search â†’ `/search?q=pengambilan` returns relevant results
- [ ] Cross-table search works (Pages + Services + News + ...)
- [ ] Both BM and EN content searchable
- [ ] Results show snippet with highlight
- [ ] Pagination works
- [ ] Empty results: friendly message + chatbot CTA

### 11.3 Anti-patterns

- âŒ `LIKE %query%` â€” use Scout
- âŒ Searching encrypted fields (`chat_messages.content`)
- âŒ Returning >50 results per page

---

## Phase 12 â€” Edge Cases (legacy redirects, SPLaSK, print, accessibility)

**Goal:** All cross-cutting requirements from SPEC Â§8 done.

### 12.1 Legacy URL redirects

- `config/redirects.php` â€” array of ~80 entries (per SPEC Â§11.2)
- `app/Http/Middleware/LegacyRedirect.php` â€” checks request path against config, returns 301 if match
- Register in `app/Http/Kernel.php` as global middleware (early)

### 12.2 SPLaSK tagging

- Add `<meta name="agency" content="JKPTG">` etc. per JDN spec to `layouts/public.blade.php` `<head>`
- Tracking pixel placeholder (img to splask.gov.my/track per spec â€” confirm format)

### 12.3 Print stylesheet

- Add `resources/css/print.css` (Tailwind `@media print` rules per DESIGN Â§10)
- Apply `@media print` overrides on master layout
- "ðŸ–¨ Cetak" button on `/perkhidmatan/*/sop`, `/panduan/akta/*`, `/korporat/piagam-pelanggan` triggers `window.print()`
- "ðŸ“„ Muat Turun PDF" on top 5 legal docs uses `barryvdh/laravel-dompdf`:
  ```php
  $pdf = Pdf::loadView('pdf.akta', ['akta' => $akta]);
  return $pdf->download($akta->slug . '.pdf');
  ```

### 12.4 Accessibility persistence (Alpine $persist) and screen reader

- Verified in Phase 4
- Add `lang` attribute to `<html>` in master layout based on locale
- Add `aria-live="polite"` to chatbot message region
- Add `<main id="content">` and skip-to-content link

### 12.5 Cookie banner

- Livewire component `CookieBanner` â€” shows once per browser
- Soft, dismissable
- "Terima" stores acceptance in cookie

### 12.6 Visit-stats tracking â€” REAL (CEO review accepted)

- Middleware `TrackPageVisit` queued via `database` driver: end-of-request push to job â†’ writes `visit_logs` row asynchronously
- Live data feeds dashboard widgets in Phase 8 (replaces seeded fake data â€” drop `VisitStatsSeeder` after first 7 days of real data exists)
- **PDPA anonymization (CEO review Â§3):** scheduled command runs daily â€” for visit_logs older than 30 days, zero last octet of IP and clear UA hash
- Retention: 13 months (PDPA / SPA Bil 4/2024). Eng review Q7 = grow-and-archive. Monthly cron `0 2 1 * * php artisan visit-logs:archive` exports rows older than 13mo to CSV in storage/app/archives/ then DROPs them. Single MySQL table - no partitioning.
- Queue overflow guard (Eng review Q15): jobs use `tries=3`, `backoff=[60,300,900]`. Failed jobs land in `failed_jobs` table. Filament admin retry UI exposed. Email alert if backlog over 50. If queue depth over 10k, drop new visit_logs entries silently + log to errors channel.

### 12.6.1 Tables added

```
visit_logs (id, page_path, ip_address, user_agent_hash, country, locale, referer, user_id_nullable, created_at)
  index: page_path, created_at
```

### 12.7 Verification

- [ ] All 80 legacy URLs redirect 301 to new IA
- [ ] SPLaSK meta tags + pixel present on every public page
- [ ] Print stylesheet hides chrome on print preview
- [ ] PDF download generates real PDF on top 5 legal pages
- [ ] Cookie banner appears on first visit, dismissable
- [ ] `lang="ms"` or `lang="en"` switches with locale
- [ ] Screen reader announces chatbot bot replies (NVDA/JAWS test)

### 12.8 Anti-patterns

- âŒ Fail-loop redirect (legacy URL points to itself)
- âŒ Cookie banner blocks interaction
- âŒ Print stylesheet that breaks heavy tables

---

## Phase 13 â€” Verification + Acceptance Testing

**Goal:** Demonstrate every acceptance criterion in SPEC Â§13. Catch regressions.

### 13.1 Smoke tests

`tests/Feature/SmokeTest.php` â€” 30 routes return 200.

### 13.2 Acceptance criteria (SPEC Â§13)

For each of the 15 criteria, write a test or manual checklist:

| # | Criterion | Test |
|---|-----------|------|
| 1 | Homepage 11 sections render at 375/768/1440 | Playwright visual snapshot |
| 2 | 6 megamenu sections clickable | Playwright e2e |
| 3 | Click-depth â‰¤3 to any service | unit count from `/` to leaves |
| 4 | BM/EN toggle persists | Playwright cookie check |
| 5 | Accessibility toggles persist | Playwright localStorage check |
| 6 | Chatbot bubble on every page, returns canned answer | Playwright e2e |
| 7 | All 4 sample users can log in | Pest feature test |
| 8 | Filament resources CRUD works | Pest feature test per resource |
| 9 | Audit log records actions | Pest feature test |
| 10 | â‰¥50 legacy URLs 301 redirect | unit test reading config |
| 11 | 4 Pautan Utama on every page; mandatory footer | Playwright DOM check |
| 12 | Print stylesheet works | manual + Playwright `@media print` |
| 13 | PDF download on top 5 legal pages | Pest feature test |
| 14 | Hostinger deploy accessible at demo URL | manual |
| 15 | README documented | manual review |

### 13.3 Lighthouse + axe

- Install `npx @lhci/cli` for Lighthouse CI
- Install `@axe-core/playwright` for a11y assertions
- Run on top 10 pages: target Perf â‰¥ 80, A11y â‰¥ 95, SEO â‰¥ 95, Best Practices â‰¥ 90

### 13.4 Cross-browser smoke

- Test on Chrome, Firefox, Edge, Safari (mobile)
- IE11 best-effort (per SOC mandate) â€” but graceful degradation acceptable

### 13.5 Verification

- [ ] All 15 acceptance criteria pass
- [ ] Lighthouse â‰¥ targets on 10 pages
- [ ] axe-core: 0 violations
- [ ] Cross-browser: no regressions
- [ ] Pest test suite green

### 13.6 Anti-patterns

- âŒ Skipping a11y tests
- âŒ "Works on my machine" â€” test on Hostinger staging URL
- âŒ Manual-only verification on regression-prone areas

---

## Phase 14 â€” Hostinger Deploy

**Goal:** Site live on Hostinger demo subdomain. GitHub auto-pull works.

### 14.1 Tasks

1. Hostinger panel: create subdomain `jkptg-demo.{your-hostinger-domain}` or buy `jkptg-demo.my`
2. Configure document root: `/public_html` â†’ Laravel `public/` directory
3. Set `.env` in Hostinger panel:
   - `APP_ENV=production`
   - `APP_DEBUG=false`
   - `APP_URL=https://jkptg-demo...`
   - `DB_HOST=localhost`, `DB_DATABASE=...`, `DB_USERNAME=...`, `DB_PASSWORD=...`
   - `SCOUT_DRIVER=database`
   - `MAIL_*` (for password reset)
4. SSH to Hostinger, install Composer (if not available â€” use shared Composer or upload `vendor/`)
5. SSH: `cd domain root && composer install --no-dev --optimize-autoloader`
6. SSH: `php artisan migrate --force && php artisan db:seed --force`
7. SSH: `php artisan storage:link`
8. SSH: `php artisan config:cache && php artisan route:cache && php artisan view:cache`
9. Configure GitHub webhook â†’ Hostinger pull endpoint (per Shahril's existing `/deploy` skill flow). On push to main, triggers:
   - `git pull`
   - `composer install --no-dev --optimize-autoloader`
   - `php artisan migrate --force`
   - `php artisan view:cache`
10. SSL: Hostinger Free SSL (Let's Encrypt) â€” auto-enabled. Verify HTTPS works.
11. Test demo URL: smoke through 30 routes; sample-user login works.

### 14.2 Verification

- [ ] Demo URL loads with HTTPS
- [ ] All routes work
- [ ] Sample user login works
- [ ] Filament admin accessible
- [ ] Search works
- [ ] PDF download works
- [ ] Pushing to GitHub main triggers redeploy
- [ ] No console errors in browser DevTools
- [ ] Lighthouse on production matches local results

### 14.3 Anti-patterns

- âŒ Push `.env` to git â€” never
- âŒ Push `vendor/` â€” composer install on host
- âŒ Run `php artisan migrate:fresh` on production â€” only `migrate`
- âŒ Leave `APP_DEBUG=true` in production
- âŒ Skip SSL

---

## Phase 14.5 â€” Walkthrough Video (CEO review accepted)

**Goal:** 5-minute narrated screen recording covering the full feature surface for tender bundle.

### 14.5.1 Tasks

1. Script outline (~5 min):
   - 0:00â€“0:30 intro + JATA + portal name + design profile (navy formal, PPPA compliant)
   - 0:30â€“1:30 homepage walkthrough (hero + persona doors + service strip + news/calendar + footer + 4 Pautan Utama)
   - 1:30â€“2:15 service detail + SOP + Carta Alir + Borang download + print
   - 2:15â€“2:45 BM/EN toggle + accessibility panel toggles
   - 2:45â€“3:30 Chatbot demo (real LLM answering, fallback to canned shown, source citations)
   - 3:30â€“4:15 Login â†’ Filament admin: dashboard widgets + audit log + Chatbot KB CRUD + GUI Flow Builder
   - 4:15â€“4:45 Mobile responsive + legacy URL redirect demo + search
   - 4:45â€“5:00 outro + tender ref + contact
2. Tools: Loom unlisted (recommended) or YouTube unlisted
3. Captions in BM + EN (auto-caption + manual review)
4. Bundle URL in tender response document

### 14.5.2 Verification

- [ ] Video recorded, â‰¤5 min
- [ ] All key features demonstrated
- [ ] Captions accurate
- [ ] Hosted unlisted with shareable link
- [ ] Link in tender response artifact

---

## Final Phase â€” Verification

1. Walk through SPEC Â§13 acceptance â€” sign off each
2. Walk through DESIGN Â§13 component checklist â€” sign off each
3. Walk through CONSTRAINTS.md compliance cross-ref â€” match every PPPA + SOC requirement to implementation evidence
4. Bundle tender artifacts: walkthrough video link, demo URL, README, GitHub repo. (SITE-AUDIT.pdf, CONTENT-INVENTORY.xlsx, MIGRATION-PLAN.md skipped per CEO review.)
5. Update STATE.md â†’ all stages complete

---

## Phase Dependency Graph

```
Phase 0 (docs discovery) â”€â”€â”€ one-shot research, done before phases below
   â”‚
   â–¼
Phase 1 (scaffold) â”€â”€â”€â”€ prerequisite
   â”‚
   â–¼
Phase 2 (auth + roles) â”€â”€ prerequisite for everything (LESSONS rule 3)
   â”‚
   â–¼
Phase 3 (DB + seeders) â”€â”€ prerequisite for content phases
   â”‚
   â–¼
Phase 4 (layouts) â”€â”€â”€â”€â”€â”€â”€ prerequisite for public pages
   â”‚
   â”œâ”€â”€â–¶ Phase 5 (homepage + persona)
   â”‚       â”‚
   â”‚       â–¼
   â”‚    Phase 6 (service / korporat / sumber / hubungi)
   â”‚
   â””â”€â”€â–¶ Phase 7 (Filament resources)
           â”‚
           â–¼
        Phase 8 (dashboard + audit)

Phase 9 (chatbot) â”€â”€â”€â”€â”€â”€ after Phase 4 (needs layout) + Phase 7 (needs admin KB)

Phase 10 (i18n polish) â”€â”€ after all content phases (5-9)

Phase 11 (search) â”€â”€â”€â”€ after Phase 3 (needs models)

Phase 12 (edge cases) â”€â”€ after Phase 4

Phase 13 (verification) â”€â”€ after all of 5-12

Phase 14 (deploy) â”€â”€â”€â”€ final
```

Sprint slicing (2-week sprints):

| Sprint | Phases |
|--------|--------|
| 1 | 0, 1, 2, 3 (foundation) |
| 2 | 4, 5, 6 (public layouts + content) |
| 3 | 7, 8, 9 (admin + dashboard + chatbot) |
| 4 | 10, 11, 12 (i18n, search, edge cases) |
| 5 | 13, 14 (verify, deploy, polish for tender) |
| 6 (buffer) | bug-fix, design polish, tender artifact bundling |

---

## Design Review Decisions (2026-05-06)

| Finding | Resolution | Phase |
|---------|------------|-------|
| Interaction states matrix missing | Added Phase 4.5 â€” full empty/loading/error/success spec for every feature | 4.5 |
| Persona landings thin | Expanded Phase 5.2 with templated h1/tagline/top-3-cards/chatbot CTA/recent + DRY component | 5.2 |
| Megamenu hover/click contradiction | Locked **click + keyboard only**. SPEC Q4B hover-delay superseded. DESIGN Â§5.6 + PLAN Â§4.4 updated | 4 |
| Pautan Agensi logo carousel risk | Constrained: monochrome + uniform 80px height + no autoplay + kill entirely if insufficient real partners | 5.3 |
| `<x-public.persona-landing>` Blade component | Added DRY component spec | 5.2.1 |

Open design taste decisions deferred to implementation:
- Hero photo source â€” vendor sources during build
- Footer dark navy exact shade â€” tested visually in Stage 6
- Chatbot bubble color â€” primary navy unless contrast fails on navy header (then accent)
- Persona-door illustration vs icon â€” implementation taste

---

## CEO Review Decisions (2026-05-06)

Mode: SELECTIVE EXPANSION. Cherry-picks accepted/skipped:

| Cherry-pick | Decision | Phase impact |
|-------------|----------|--------------|
| Real LLM chatbot integration (OpenAI/Anthropic, sandboxed) | âœ… Accepted | Phase 9 expanded with driver abstraction + fallback chain + cost cap + injection guard |
| Real visit tracking (drop seeded fake) | âœ… Accepted | Phase 12.6 â†’ real tracking + 30-day IP anonymization (PDPA) + retention policy |
| 5-minute walkthrough video for tender | âœ… Accepted | New Phase 14.5 |
| SITE-AUDIT.pdf + CONTENT-INVENTORY + MIGRATION-PLAN bundle | âŒ Skipped | demo URL alone deemed sufficient |
| Lighthouse CI on every push | âŒ Skipped | one-shot Phase 13.3 enough for prototype |
| Demo URL â€” public open | âœ… Default | no auth wall |

Critical gaps from CEO review applied to plan:
- LLM fallback chain (RateLimit/Timeout/InvalidResponse/fatal) â†’ Phase 9.1
- LLM cost cap kill switch (auto-flip to canned at $40/mo) â†’ Phase 9.1
- LLM prompt-injection sanitizer + 10/IP/hr rate-limit â†’ Phase 9.1
- visit_logs PDPA anonymization + 13-mo retention â†’ Phase 12.6
- LLM driver abstraction (LlmDriver interface) â†’ Phase 9.1.1
- New widgets: ChatbotLlmHealth, ChatbotLlmCost, QueueDepth â†’ Phase 8.1
- New tables: llm_api_logs, visit_logs â†’ Phase 3
- Code quality DRY: `<x-public.persona-landing>` component, `HasRoleGate` trait â†’ Phase 5/7
- Test mock: `LlmDriver` mock for CI (don't call real API) â†’ Phase 13.1

---

## Eng Review Decisions (2026-05-06, FULL - 15 questions resolved)

### Batch 1 - Library / vendor picks

| # | Question | Decision |
|---|----------|----------|
| 1 | Translatable storage | **JSON column via `spatie/laravel-translatable`** (Scout DB driver searches via `JSON_EXTRACT` on MySQL 5.7+/MariaDB 10.2+ - Hostinger supports) |
| 2 | PDF library | **`barryvdh/laravel-dompdf`** (pure PHP, works on shared hosting, 90% fidelity adequate for legal docs) |
| 3 | Map embed | **OpenStreetMap via Leaflet.js** (zero-key, no usage limit, PDPA-clean) |
| 4 | LLM model | **Anthropic Claude Sonnet 4.6** (better BM nuance; ~3x cost vs Haiku; cap revised to RM 200 = ~$40 USD = ~7.5k chats/mo at avg 2k input + 500 output tokens) |

### Batch 2 - Architecture / scope picks

| # | Question | Decision |
|---|----------|----------|
| 5 | 2FA scope | **Defer to production** - no 2FA in prototype. Tender narrative: "production-ready hook"; sample admin login = email+password only |
| 6 | LLM monthly cost cap | **RM 200/mo (~$40 USD, ~7.5k Sonnet 4.6 chats)** - kill switch + email alert at 80%, configurable in admin |
| 7 | visit_logs growth | **Grow-and-archive at 13 mo** - single MySQL table, monthly cron archives older to CSV+drops; PDPA-clean (SPA Bil 4/2024); IP anonymized after 30 days |
| 8 | Queue driver | **`database` driver** on `jobs` table - Hostinger cron `php artisan queue:work --stop-when-empty` every minute |
| 9 | LLM driver abstraction | **Anthropic + Canned only** - `LlmDriver` interface stays open for future `OpenAiDriver`; ship 2 of 3 drivers |
| 10 | Filament version | **v3.3.x** - Laravel 11 compatible, stable plugin ecosystem, FILAMENT-PATTERNS.md tested |
| 11 | Hostinger plan tier verification | **Add Phase 0.5 verification step** - block builds until PHP 8.3 + MySQL 8 + Composer SSH + outbound HTTPS to api.anthropic.com confirmed; document plan in CONSTRAINTS.md |
| 12 | myID/IDN integration | **Disabled button + 'Demo' label** - tooltip "Memerlukan integrasi Production - JKPTG SH05/2026"; `MyIdProvider` interface scaffolded with stubbed SAML/OIDC flow |
| 13 | Audit log package | **`spatie/laravel-activitylog`** + Filament v3 activitylog plugin for timeline UI; maps to PPPA 5.2.1 audit-trail |
| 14 | LLM cost-cap reset cron | **Calendar-month reset on 1st** - Hostinger cron `0 0 1 * * php artisan llm:reset-cap` |
| 15 | Queue overflow handling | **3 retries + DLQ** - `tries=3`, `backoff=[60,300,900]`; `failed_jobs` table; Filament admin retry UI; email alert if backlog >50 |

---

## Open Questions for `/plan-design-review`

1. Hero photo source â€” final asset selection (Unsplash placeholder â†’ JKPTG/Putrajaya photos)
2. Persona-door illustration vs icon-only treatment
3. Logo layout in header â€” Jata + JKPTG side-by-side vs stacked
4. Chatbot bubble color â€” primary navy vs different accent for visibility
5. Footer dark navy â€” exact shade between #0F1E33 (current pick) and #243D57 (header pick)
6. Print stylesheet â€” does the JKPTG legal team have specific format requirements?
7. Map embed style â€” minimal vs full-info-window

---

## Open Questions for `/plan-ceo-review`

1. Is the prototype scope still appropriately ambitious for tender? (capability showcase is correct framing)
2. Are stubbed features (LLM, myID, MyLAND) clear enough labels for evaluators, or do we need stronger "demo only" badging?
3. Should dashboard widgets show real or seeded numbers? (real = small real demo data, seeded = larger looking-good numbers)
4. Sprint timeline (5â€“6 sprints) â€” too long, too short, OK?
5. Bundle MIGRATION-PLAN.md + SITE-AUDIT.pdf as separate tender artifacts â€” owner / timing?
6. Demo URL accessibility â€” public-facing or auth-walled with tender access code?
7. Should we also produce a 5-minute screen-recorded walkthrough video for evaluators?

---

## Stage 4 Final Lock (2026-05-06)

PLAN.md is **LOCKED**. Three reviews complete:

| Review | Date | Outcome |
|--------|------|---------|
| `/plan-ceo-review` | 2026-05-06 | SELECTIVE EXPANSION - real LLM (Sonnet 4.6), real visit tracking, walkthrough video accepted; SITE-AUDIT.pdf and Lighthouse CI deferred |
| `/plan-design-review` | 2026-05-06 | 7/10 -> 9/10 - added Phase 4.5 interaction state matrix, expanded Phase 5.2 persona-landing component, locked megamenu click-only (overrides SPEC Q4B) |
| `/plan-eng-review` | 2026-05-06 | 15 questions resolved across 2 batches - all picks documented in Eng Review Decisions section above |

### Stage 4 deliverables

- [x] PLAN.md drafted (14 phases + Phase 0/0.5/4.5/5.2.1/14.5)
- [x] CEO review applied
- [x] Design review applied
- [x] Eng review applied (Batch 1 + Batch 2)
- [x] Architecture critiques addressed (cost-cap cron Q14, queue overflow Q15)

### What changes vs initial draft

1. Phase 0.5 NEW - Hostinger plan tier verification (Eng Q11) - blocker checks before Phase 1
2. Phase 1 #17 SKIPPED - 2FA package install removed (Eng Q5: defer to production)
3. Phase 9.1 - Sonnet 4.6 model (Eng Q4), RM 200 cost cap (Eng Q6), Anthropic + Canned drivers only (Eng Q9)
4. Phase 9 cost-cap reset cron added (Eng Q14)
5. Phase 12.6 - grow-and-archive at 13mo (Eng Q7), 3-retry DLQ + email at 50 (Eng Q15)
6. Phase 0.9 - "no external LLM" anti-pattern OVERRIDDEN (CEO + Eng Q9 ship real Anthropic)

### Next stage

**Stage 5 - Visual Variants** (`/design-shotgun` capped at 3 variants per page per LESSONS rule 1).

Pages needing variants: homepage hero, persona landing, service detail, megamenu open state. Pick winners then proceed to Stage 6 HTML mockup.
