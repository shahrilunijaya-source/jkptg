# Portal JKPTG — Walkthrough Video Script

**Format:** Loom unlisted, 5:00 ± 0:30, 1080p, mic clear
**Audience:** JKPTG tender evaluators (procurement panel)
**Goal:** Prove capability in under 5 min. Every claim demonstrable on screen.
**Tone:** Calm, confident, formal Bahasa Melayu (English captions optional). No hype, no jargon.

## Pre-record checklist

- [ ] `php artisan serve --host=127.0.0.1 --port=8000` running
- [ ] Browser at 1440×900, zoom 100%, single tab
- [ ] DevTools closed
- [ ] Notifications muted (Slack, Teams, email)
- [ ] Mic test: `loom test` voice level
- [ ] Have admin credentials ready: super-admin email + password from `UserSeeder.php`
- [ ] Page open: `http://127.0.0.1:8000/`
- [ ] Cursor visible setting on in Loom
- [ ] BM mode active (cookie cleared if needed)

## Script (timed)

### 0:00 – 0:20 · Hook + identity

> "Demo prototaip Portal JKPTG. Dibina untuk tender SH05/2026. Stack: Laravel 11, Filament v3, Livewire 3, MySQL 8. Patuh PPPA Bil 1/2025 dan WCAG 2.1 AA."

**Screen:** static homepage hero. Don't move yet.

### 0:20 – 0:50 · Hero + 3 persona doors

> "Halaman utama. Hero hibrid — misi jabatan dan tiga pintu persona: orang awam, kementerian, warga JKPTG."

**Click sequence:**
1. Hover persona "Orang Awam" door — fade reveal
2. Click → `/untuk/orang-awam`
3. Pause 2s on persona landing
4. Browser back to home

### 0:50 – 1:15 · Megamenu

> "Megamenu hero-cards. Klik atau Tab untuk buka — bukan hover, ikut WCAG. Lima kategori, sembilan perkhidmatan."

**Click sequence:**
1. Click "PERKHIDMATAN" in header
2. Megamenu opens — 5 columns visible
3. Hover one card to show detail
4. Press Escape → closes
5. Tab focus through nav to show keyboard a11y

### 1:15 – 1:50 · Service detail with sticky nav

**Click sequence:**
1. Click megamenu → "Pengambilan Tanah" → `/perkhidmatan/pengambilan`
2. Show sticky 240px left rail
3. Scroll down — IntersectionObserver highlights active section in left rail
4. Show sticky bottom-right "Mohon Sekarang" CTA appearing on scroll

> "Skema variant B sticky-nav. Kandungan panjang dipecah enam seksyen. Pemerhati kekal tahu di mana mereka berada."

### 1:50 – 2:15 · Borang library + search

**Click sequence:**
1. Header search input → type `borang pengambilan` → Enter
2. `/cari?q=borang+pengambilan` shows cross-model hits (Service, Form, FAQ, KB)
3. Click "Borang" section result
4. Land on `/panduan/borang` with sidebar filter

> "Carian Scout. Cari merentas tujuh model — halaman, perkhidmatan, berita, tender, borang, FAQ, pangkalan pengetahuan chatbot. JSON_EXTRACT MySQL — dwi-bahasa serentak."

### 2:15 – 2:35 · Hubungi + Leaflet map

**Click sequence:**
1. Footer → "Hubungi Kami"
2. `/hubungi` loads, Leaflet OpenStreetMap embed visible
3. Pan/zoom map briefly

> "Peta cawangan — OpenStreetMap. Tiada API key, tiada bayaran, patuh PDPA — tiada data pengguna dihantar Google."

### 2:35 – 2:55 · BM/EN switch

**Click sequence:**
1. Top utility bar → "EN"
2. Page reloads — utility bar, header, megamenu labels, footer all flip
3. Open service page — content `name`, `summary`, `process_steps` flipped
4. Click "BM" — restored

> "Dwi-bahasa penuh. Spatie Translatable, JSON columns, satu pangkalan data, dua bahasa serentak."

### 2:55 – 3:25 · Chatbot

**Click sequence:**
1. Click chatbot bubble (bottom-right)
2. Greeting appears in BM
3. Click quick reply "Pengambilan tanah"
4. Bot replies with Akta 486 reference + KB citation
5. Type free-form: "berapa lama proses pajakan negeri?"
6. Bot replies — citation tag visible

> "Chatbot Livewire. Default canned dari pangkalan pengetahuan. Boleh tukar ke Anthropic Sonnet 4.6 melalui satu config. Cap kos RM 200/bulan dengan kill-switch automatik. Rate limit 10 soalan/IP/jam. Sanitizer terhadap prompt injection."

### 3:25 – 4:10 · Admin panel login + dashboard

**Click sequence:**
1. Header → "Log Masuk"
2. `/admin/login` — Filament form
3. Type super-admin email + password
4. Land on `/admin` dashboard
5. Pan over 4 widgets:
   - StatsOverview (8 kotak: Halaman 12, Perkhidmatan 6, Berita 5, Tender 2, Borang 9716 muat turun, KB 6, Pengguna 4, Lawatan 24j 126)
   - LlmCostMeter (RM 0.00 / 200.00 hijau)
   - VisitorChart (7 hari, peak hari ini 126)
   - RecentActivity table

> "Filament admin. Empat widget — stat, kos LLM, lawatan tujuh hari, aktiviti terkini. Semua live dari Spatie Activity Log."

### 4:10 – 4:40 · Admin resource CRUD demo

**Click sequence:**
1. Sidebar → "Berita" (NewsResource)
2. Show table 5 rows
3. Click first row → Edit
4. Show LocaleSwitcher action top-right (BM ↔ EN tabs in form)
5. Show RichEditor body field
6. Cancel

> "Sembilan sumber CRUD: Halaman, Perkhidmatan, Berita, Tender, Borang, FAQ, Cawangan, Pengguna, Pangkalan Pengetahuan Chatbot. Semua boleh terjemah. Penukar bahasa per-rekod."

### 4:40 – 4:55 · Audit log

**Click sequence:**
1. Sidebar → "Pentadbiran" group → "Log Audit"
2. `/admin/log-audit` — read-only, Spatie Activity stream
3. Show filter "Hari ini" / "Minggu ini" / event type

> "Setiap perubahan direkod. Siapa, bila, model apa, properti mana. Audit penuh untuk pematuhan kerajaan."

### 4:55 – 5:00 · Close

> "Lima minit. Stack moden, patuh, demonstrable. Repo penuh + dokumen deploy hadir bersama tender. Terima kasih."

**Screen:** back to homepage hero.

## Tender talking-points cheat sheet (off-camera)

If panel asks during Q&A:

| Topic | One-liner |
|-------|-----------|
| Stack choice | "Laravel 11 LTS — disokong sehingga 2027. Filament admin — RAD untuk pegawai BM. Livewire — interaksi tanpa SPA." |
| PPPA Bil 1/2025 | "16 meta SPLaSK, sitemap.xml, robots.txt, security.txt, dwi-bahasa, single h1, skip-link, audit row-by-row dalam SOC matrix." |
| WCAG 2.1 AA | "Skip-link, landmark roles, single h1, ARIA-live pada chatbot, prefers-reduced-motion, high-contrast toggle, keyboard-only megamenu." |
| Security | "X-Frame, X-Content-Type, Referrer, Permissions, CSP, COOP, HSTS. CSRF semua POST. Encrypted chat. Rate limit chatbot. whereRaw bind parameter. Admin role-gated FilamentUser." |
| Performance | "Vite production build 124KB CSS / 42KB JS gzipped. Page load <600ms. Cache config/route/view." |
| Cost LLM | "RM 200/bulan cap. Auto kill-switch. Default canned. API key flip dalam .env, tiada deploy semula." |
| myID / MyLAND / SISPAA | "Stub demo. Real integration scope-out — separate engagement." |
| Scope flexibility | "Reka bentuk bermodul. Tambah perkhidmatan = tambah satu rekod ServiceResource. Tiada code change." |
| Deploy | "Hostinger Git auto-pull. deploy.sh idempotent. SSL Let's Encrypt auto-renew. Cron schedule:run." |
| Maintenance | "Pegawai JKPTG urus content via /admin sepenuhnya. Vendor support pada code level only." |

## Loom upload checklist

- [ ] Trim front 0.5s (mouse settling) and tail 1s (cursor reset)
- [ ] Caption track: auto-generate, manual review for "JKPTG", "PPPA", "Spatie", "Livewire"
- [ ] Privacy: Unlisted (link required, no public listing)
- [ ] Title: `Portal JKPTG — Demo Prototaip Tender SH05/2026`
- [ ] Description: 1 paragraph + GitHub repo URL + DEPLOY-CHECKLIST.md link
- [ ] Generate share link → paste into tender response document Lampiran X

## Rehearsal pass (do twice)

1. Solo run with mic muted — pure click-through, time each section
2. Solo run with mic on — voice over, check pace
3. Real take — one shot, no edits

If a take goes >5:30, cut the megamenu Escape detour or trim the audit log demo.
