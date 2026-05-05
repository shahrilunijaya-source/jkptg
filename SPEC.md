# Portal-JKPTG — Specification (Stage 1)

**Status:** locked 2026-05-05
**Scope:** Functional prototype for tender JKPTG SH05/2026 — capability showcase, not full production system.
**Prerequisite docs:** `CONSTRAINTS.md`, `STACK-ADR.md`, `LESSONS.md`, `STATE.md`.
**No re-debate** of locked decisions in later stages (LESSONS rule 2).

---

## 1. Wedge

**Job-to-be-done:** win JKPTG SH05/2026 tender by demonstrating, in one navigable demo, that we understand:
- PPPA Bil 1/2025 mandates,
- SOC functional + security requirements (Items 1–6),
- existing portal pain points (mobile broken, peta-laman empty, content stale),
- how we'd execute the rebuild end-to-end.

The prototype is a real Laravel application, runnable on Hostinger, with seeded sample content drawn from the Stage 0.5 scrape. Some features are stubbed (LLM call, myID, MyLAND, SISPAA push) — clearly labeled as "demo" but visibly designed.

---

## 2. Users

| Persona | Primary task | Where they enter |
|---------|--------------|------------------|
| Orang Awam (citizen) | Find SOP / borang / contact / submit aduan / ask chatbot | Persona door + Perkhidmatan megamenu + chatbot |
| Kementerian / Jabatan | Look up land service for govt project | Persona door + Perkhidmatan |
| Warga JKPTG (staff) | Internal info (intranet shortcut) | Persona door |
| Tender evaluator (cross-cutting) | Score against PPPA + SOC checklists | Every page (compliance visible everywhere) |

---

## 3. Stack (locked, no re-debate)

| Layer | Choice | Rationale |
|-------|--------|-----------|
| Backend | Laravel 11 LTS (PHP 8.3) | SOC §4 mandate (PHP) |
| Admin | Filament v3 at `/admin` | STACK-ADR; CMS UI in tender narrative |
| Public | Livewire 3 + Blade | STACK-ADR |
| CSS | Tailwind 3 | STACK-ADR |
| DB | MySQL 8 / MariaDB 10 | SOC §4 mandate |
| Auth | Laravel built-in + Spatie Permission (roles) | LESSONS rule 3 — `FilamentUser` interface implemented in Stage 6.5 before features |
| Search | Laravel Scout — DB driver | No external dep for prototype; switch to Meilisearch in production |
| Chatbot | Livewire component + DB-backed KB | Real LLM call out-of-scope for prototype |
| i18n | Laravel localization, BM default + EN | PPPA §3.3.1.a |
| Hosting | Hostinger via GitHub auto-pull | Existing `/deploy` flow |

---

## 4. System Architecture

```
┌──────────────────────────────────────────────────────────────┐
│  Browser (BM/EN, mobile + desktop, WCAG 2.1 AA)              │
└────────────────────┬─────────────────────────────────────────┘
                     │ HTTPS
                     ▼
┌──────────────────────────────────────────────────────────────┐
│  Laravel app (PHP 8.3, nginx, Hostinger)                     │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────────┐    │
│  │ Public       │  │ /admin       │  │ /api/* (chatbot, │    │
│  │ Livewire+    │  │ Filament     │  │ search-as-you-   │    │
│  │ Blade        │  │ + roles      │  │ type)            │    │
│  └──────┬───────┘  └──────┬───────┘  └────────┬─────────┘    │
│         └─────────────────┼─────────────────┬─┘              │
│                           ▼                 ▼                │
│   ┌──────────────────────────────────────────────────────┐   │
│   │ Domain services: Pages, Services, Forms, News,       │   │
│   │ Tenders, Cawangan, FAQ, Chatbot, AuditLog,           │   │
│   │ SearchIndex, Settings                                 │   │
│   └────────────────────────┬─────────────────────────────┘   │
│                            ▼                                 │
└──────────────────────────────────────────────────────────────┘
                             │
                  ┌──────────┴──────────┐
                  ▼                     ▼
            MySQL/MariaDB          Stubbed externals (UI shown):
            (content + audit       ├ myID/IDN (button disabled)
             + chat sessions)      ├ MyLAND (link-out)
                                   ├ SISPAA (link-out)
                                   ├ LLM (canned KB matcher)
                                   └ SPLaSK (tracking pixel)
```

**Compliance layer (cross-cutting middleware):**
- HSTS, CSP, X-Frame-Options, X-Content-Type, Referrer-Policy headers
- CSRF token on all forms (Laravel default)
- Rate-limit: 5/min on `/login`, 60/min on chatbot input
- Audit log entry on auth events + admin CRUD
- SPLaSK `<meta>` tags on every public page

**Environments (hosting):**
- Hostinger production (single instance for prototype)
- Local dev via Laravel Sail / Herd
- Production uses `.env` set in Hostinger panel for DB
- Tender narrative references planned 3-env topology (Pusat Data JKPTG / PDSA Teras / PDSA Perisai) per SOC §4 — not realized in prototype

---

## 5. Information Architecture (LOCKED)

### 5.1 Megamenu — 6 top-level (no late additions)

```
UTAMA · PERKHIDMATAN · PANDUAN & BORANG · KORPORAT · SUMBER · HUBUNGI KAMI
```

Sub-trees per top-level (full enumeration):

#### PERKHIDMATAN
- Pengambilan Tanah (SOP, Carta Alir, Senarai Semak, Pekeliling)
- Pembahagian Pusaka Kecil (Panduan, Carta Alir, Borang)
- Pajakan Tanah Persekutuan (Panduan, Senarai Semak, Borang)
- Penyewaan Tanah Persekutuan
- Lesen Pasir
- Hakmilik Strata & Stratum
- Penyerahan Balik / Pelepasan Tanah
- MyLAND ↗ (link-out)

#### PANDUAN & BORANG
- Permohonan SOP (alphabetical index)
- Carta Alir
- Senarai Semak
- Borang Muat Turun (PDF library, searchable, category filter)
- Senarai Pekeliling (Terbuka + Dalaman[login])
- Senarai Undang-Undang (Kanun Tanah Negara, Akta Pengambilan 1960, Strata 1985, Pusaka Kecil 1955, Pemuliharaan Tanah, Pelantar Benua, Rizab Melayu, Kawasan Penempatan Berkelompok 1960)
- Tabung Khas Hakmilik Strata
- Peperiksaan

#### KORPORAT
- Perutusan Ketua Pengarah
- Latar Belakang JKPTG
- Visi · Misi · Objektif
- Piagam Pelanggan + Prestasi
- Fungsi Jabatan
- Carta Organisasi (Kementerian + Jabatan)
- Pengurusan Tertinggi (KP, TKP-SKPP, TKP-SPO, CIO/CDO — PPPA §3.4.1.d)
- Profil Bahagian (BKP, BD&K, BSI, BPPT, BHTP, BPT, BPP, BHSS, BPICT-PT, Unit Integriti, Unit Undang-Undang)
- Cawangan Negeri × 15 (Kedah, WP KL, Selangor, P. Pinang, Perak, N. Sembilan, Perlis, Pahang, Terengganu, Kelantan, Melaka, Johor, Sabah, Sarawak, Putrajaya/WP)

#### SUMBER
- Galeri (Gambar, Audio, Video)
- Pelan Strategik (PSP 2022–2026, Strategik 2022–2026, Strategik 2026–2030)
- Penerbitan
- Data Terbuka Kerajaan (PPPA mandatory pautan)
- Pembentangan Kertas Kerja
- Teach-In JKPTG
- Infografik
- Arkib (Keratan Akhbar, Pengumuman, Berita, Sebut Harga/Tender)

#### HUBUNGI KAMI
- Ibu Pejabat (Putrajaya — alamat, peta, tel, fax)
- Cawangan Negeri × 15
- Direktori Bahagian (group emails, no individual — PPPA §3.4.1.c)
- Aduan & Maklum Balas (SISPAA ↗, MyLAND ↗, Borang Maklum Balas in-portal)
- Webmaster (webadmin@jkptg.gov.my)

### 5.2 Megamenu rules

- Click-to-open (not hover) — keyboard accessible
- ESC closes; Tab cycles; Enter activates
- Sub-tree: full-width panel under header
- Mobile: collapses to hamburger → accordion
- Active state: top-level underlined + breadcrumb on inner pages
- Each top-level reachable as standalone index page (`/perkhidmatan`, `/korporat`, etc.)

### 5.3 Homepage sections (top → bottom)

1. **Utility bar** (right): 🔍 Carian │ BM | EN │ ♿ Aksesibiliti │ Log Masuk
2. **Header**: JATA + Logo + agency name + ministry name + 4 Pautan Utama icons (Soalan Lazim, Hubungi Kami, Aduan & Maklum Balas, Peta Laman) — top-right, PPPA §3.2.1.i mandatory
3. **Megamenu** (6 items)
4. **Hero** (split — desktop): mission/CTA half + 3 persona doors stacked half. Mobile: stacked.
5. **Perkhidmatan Teras** — 6 service tiles (Pengambilan, Pusaka, Pajakan, Penyewaan, Lesen Pasir, Strata)
6. **Maklumat Terkini** (3-tab): Berita, Pengumuman, Tender + **Kalendar Aktiviti**
7. **Info JKPTG** icon strip (myID, Infografik, Consent Online, Data Terbuka, MyHRMIS)
8. **Pautan Agensi** logo carousel (NRES, JUPEM, MyGeo, MAMPU, MSC, JPA, MOF, etc.)
9. **Floating chatbot** bubble (always)
10. **Footer** (4 cols): Hubungi Kami | KOD QR | Kata Kunci Popular | Bilangan Pelawat
11. **Mandatory links bar**: Penafian, Dasar Web, Dasar Keselamatan, Dasar Privasi, Panduan Pengguna, Peta Laman, Hak Cipta + Pautan Mandatori (MyGov, Open Data Portal) + Tarikh Kemas Kini

### 5.4 Click-depth contract

Any service or info page reachable in **≤ 3 clicks** from homepage (PPPA §4.4.2.b.iii + SOC Item 1 §19).

---

## 6. Auth + Login Flow

### 6.1 Sample users (seeded)

| Email | Password | Role | Access |
|-------|----------|------|--------|
| admin@jkptg.demo | password | super-admin | full Filament + dashboard + all resources |
| editor@jkptg.demo | password | editor | Pages, News, Tenders, Forms, FAQ, Chatbot KB |
| viewer@jkptg.demo | password | viewer | read-only dashboard + audit log |
| citizen@jkptg.demo | password | citizen | `/akaun` stub only |

### 6.2 Routes

```
/                        public homepage
/login                   sample-user picker + manual form + myID button (disabled)
/admin                   Filament panel (auth required)
/akaun                   citizen stub (auth required)
/logout                  POST → returns to /
```

### 6.3 Login UI

- Card 1 (primary, **disabled** w/ tooltip): Log Masuk dengan myID — "Demo: gunakan akaun sampel"
- Card 2 (sample-user one-click buttons): admin / editor / viewer / citizen
- Divider "Atau"
- Card 3: manual email + password form
- Footer note: "Pelawat? Tidak perlu log masuk untuk akses kandungan."

### 6.4 Roles (Spatie Permission)

| Role | /admin | Resources allowed | Dashboard widgets | Audit log |
|------|--------|-------------------|--------------------|-----------|
| super-admin | full | all CRUD | full | full |
| editor | partial | Pages, News, Tenders, Forms, FAQ, Chatbot KB | content widgets only | own actions |
| viewer | read-only | none CRUD | read-only widgets | read-only |
| citizen | none | — | — | — |

### 6.5 Filament admin

- `App\Models\User` implements `FilamentUser::canAccessPanel(Panel $panel): bool` (LESSONS rule 3 — done **before any features**)
- Admin-side panel locale switcher (BM/EN)
- Profile page (name, email, avatar, password change, 2FA toggle)
- Logout returns to public homepage

### 6.6 Personalization (`/akaun`, citizen stub)

- "Selamat datang kembali, {{ name }}"
- "Lawatan terakhir: {{ last_login_at }}"
- "Topik anda ikuti" — tag list (seeded)
- "Borang anda muat turun terkini" (seeded)
- "Aduan anda" case list (seeded)
- Edit profile + notification preferences

### 6.7 Security demo touches (visible)

- HTTPS-only cookies, SameSite=Strict
- CSRF tokens
- Password rules: min 12, mixed case, digit, symbol
- Throttle: 5 attempts / minute on login
- 2FA toggle (TOTP, real)
- Audit log entries for login + admin CRUD

---

## 7. Chatbot (prototype)

### 7.1 Position + UX

- Floating bubble bottom-right on every public page (48×48px, ≥44px touch)
- Click → side panel (320×600 desktop / fullscreen mobile)
- 5 quick-reply chips on open
- Markdown rendering of responses with source citations

### 7.2 Tech (Livewire 3)

- `App\Livewire\Chatbot\Bubble` component
- In-memory `$messages` array per session
- "Typing…" indicator 1–1.5 s simulated delay
- Resolve: exact KB keyword match → Scout fulltext fallback → "Saya belum tahu jawapannya" + webmaster link
- Persist to DB if user authed (else memory-only — anon-first)

### 7.3 Tables

| Table | Columns |
|-------|---------|
| chatbot_knowledge | id, slug, question_bm, question_en, answer_bm (md), answer_en (md), keywords (jsonb), source_url, category, active |
| chatbot_quick_replies | id, label_bm, label_en, payload_query, sort, active |
| chat_sessions | id, user_id, session_uuid, started_at, ended_at, message_count, locale |
| chat_messages | id, session_id, role, content, citation, created_at (encrypted at rest via Laravel cast) |
| chatbot_settings | mode (ai/normal), quota_used_month, quota_limit, alert_threshold |

### 7.4 Filament admin

- Chatbot Knowledge resource — CRUD with BM+EN side-by-side editor
- Quick Replies — sortable list
- Chat Sessions — read-only with drill-down
- Settings — AI/Normal mode toggle + quota progress bar (seeded fake data)
- GUI Flow Builder — capability page (form-based, non-executable)

### 7.5 Capability narrative (visible to evaluator)

- Dashboard widget: "AI: 23,847 / 100,000 sesi (24%)"
- Widget: "Token: 287,394 / 1,000,000 (29%)"
- Notification rule UI: alert before 80%
- Auto-fallback to "Normal Chatbot" when AI quota empty (mode switch demo)

### 7.6 Privacy

- First-open notice: "Sembang ini direkodkan untuk penambahbaikan. PDPA terpakai. [Baca dasar privasi]"
- Encryption-at-rest claim (Laravel encrypted cast)
- "Eksport sesi CSV" admin action

---

## 8. Edge Case Implementations

### 8.1 OKU / WCAG 2.1 AA

**Floating accessibility panel** (right edge, collapsible):
- A− A A+ font size (3 steps, persists localStorage)
- High-contrast toggle
- Color-blind safe palette toggle
- Larger text + line-height + letter-spacing toggle
- TTS "Baca Halaman" (Web Speech API)

Plus: skip-to-content link, alt on every img (Filament-required), proper `<label for>`, `lang` attr on `<html>`, focus rings always visible, full keyboard reachability.

### 8.2 BM / EN toggle

- Top utility bar, persistent across pages
- Default = BM (PPPA §3.3.1.a)
- `LocaleMiddleware`: `?lang=` param + cookie + `Accept-Language`
- Strings via `__('messages.key')`
- Content tables have parallel `_bm` / `_en` columns; null EN → BM fallback with "(BM only)" badge

### 8.3 Mobile-first

- Tailwind breakpoints from 375px up
- Header → hamburger ≤ 768px
- Megamenu → accordion on mobile
- Hero stacks vertically
- Service strip wraps to 2×3 grid <640px
- Footer 4 → 2×2 → 1 col
- Touch targets ≥ 44×44px
- Test viewports: 375, 414, 768, 1440

### 8.4 Print-friendly

- `print.css` via `@media print`
- Hide chrome (header utility, megamenu, footer, chatbot, a11y panel)
- Show: title, breadcrumb, content, contact, "Tarikh Cetak", URL footer
- "🖨 Cetak" button on service / SOP / Akta pages
- "📄 Muat Turun PDF" on top 5 legal docs (Browsershot/wkhtmltopdf, real)

### 8.5 Anonymous-first

- All public routes accessible without auth
- No content walls
- Login required only for `/admin` and `/akaun`
- Cookie banner soft + dismissable
- Chatbot usable anonymously
- Aduan submit usable anonymously (optional email)

### 8.6 Cross-cutting (SOC mandates carried even though not user-picked)

- Legacy URL preservation: `redirects.php` map for ~80 scraped URLs → 301 → new IA paths
- SPLaSK tagging: `<meta>` + tracking pixel placeholder in master `<head>`
- Performance: Tailwind purge, lazy-load images, AVIF/WebP. Targets: LCP <2.5s, INP <200ms, CLS <0.1
- OWASP: CSRF, prepared statements (Eloquent), CSP, X-XSS-Protection, X-Content-Type nosniff

---

## 9. Public Pages (deliverable list)

| Path | Type | Notes |
|------|------|-------|
| `/` | Livewire homepage | hybrid hero + sections per §5.3 |
| `/perkhidmatan` | index | grid of 8 service cards |
| `/perkhidmatan/{slug}` | service detail | × 8 (real scraped content) |
| `/perkhidmatan/{slug}/sop` | sub | × 4 (those that have SOP) |
| `/perkhidmatan/{slug}/carta-alir` | sub | × 4 |
| `/panduan` | index | section landing |
| `/panduan/borang` | borang library | searchable, filter by category |
| `/panduan/sop` | SOP index | alphabetical |
| `/panduan/akta/{slug}` | legal doc | × 8 (Kanun Tanah, Akta Pengambilan, etc.) |
| `/korporat` | index | section landing |
| `/korporat/perutusan-kp` | page | scraped |
| `/korporat/visi-misi` | page | scraped |
| `/korporat/piagam-pelanggan` | page | scraped |
| `/korporat/fungsi-jabatan` | page | scraped |
| `/korporat/carta-organisasi` | page | scraped |
| `/korporat/pengurusan-tertinggi` | page | KP + 2 TKP + CDO/CIO |
| `/korporat/profil-bahagian/{slug}` | page | × 11 |
| `/cawangan/{state}` | page | × 15 |
| `/sumber` | index | section landing |
| `/sumber/data-terbuka` | page | seeded list of datasets |
| `/sumber/infografik` | gallery | scraped infografik PNGs |
| `/sumber/arkib/{type}` | listing | × 4 |
| `/hubungi/ibu-pejabat` | page | full address + map embed |
| `/hubungi/cawangan` | listing | 15-row table |
| `/hubungi/aduan` | form | in-portal Maklum Balas |
| `/soalan-lazim` | accordion | 5 categories × ~6 Q&A each |
| `/peta-laman` | sitemap | full nested tree (fixes existing portal's empty Peta Laman) |
| `/penafian` | page | mandatory PPPA |
| `/dasar-web` | page | mandatory |
| `/dasar-keselamatan` | page | mandatory |
| `/dasar-privasi` | page | mandatory |
| `/panduan-pengguna` | page | mandatory |
| `/hak-cipta` | page | mandatory |
| `/search` | results page | Scout DB driver |
| `/tanya-jkptg` | full chat page | optional deep-link from bubble |
| `/login` | auth picker | sample-user buttons + manual form |
| `/akaun` | citizen stub | seeded personalization |

Total: ~70 page templates (many share Livewire components).

### 9.1 Admin pages (placeholder showcase)

| Path | Type | Notes |
|------|------|-------|
| `/admin/login` | Filament default | BM-localized |
| `/admin` | dashboard | widgets per §10 |
| `/admin/pages` | resource | CRUD |
| `/admin/news` | resource | CRUD with publish/expire |
| `/admin/tenders` | resource | CRUD with deadline |
| `/admin/forms` | resource | CRUD with file upload |
| `/admin/services` | resource | CRUD |
| `/admin/cawangan` | resource | CRUD |
| `/admin/faqs` | resource | CRUD BM+EN |
| `/admin/chatbot-knowledge` | resource | CRUD BM+EN |
| `/admin/chatbot-flows` | page | GUI flow builder demo |
| `/admin/users` | resource | role assignment |
| `/admin/audit-log` | listing | read-only |
| `/admin/migration` | page | content import wizard demo |
| `/admin/settings` | page | site-wide config |

### 9.2 Dashboard widgets (super-admin)

- Visitor stats (seeded fake — daily/weekly/monthly bars)
- Top 10 pages this month
- Latest news (5)
- Pending tenders (deadline coming)
- Audit log feed (last 20 actions)
- AI Chatbot usage (quota progress)
- Forms downloaded (top 10)
- Activity calendar (current month)
- Quick actions (new page, new news, new tender)

---

## 10. Database Schema (high-level)

```
users (id, name, email, password, role, last_login_at, created_at)
roles + permissions (Spatie standard tables)
pages (id, slug, title_bm, title_en, body_bm, body_en, parent_id, sort, published, meta_*)
services (id, slug, name_bm, name_en, summary_bm, summary_en, sop_bm, sop_en, carta_alir_path, sort, active)
news (id, slug, title_bm, title_en, banner_path, body_bm, body_en, published_at, expires_at)
tenders (id, slug, title, doc_path, deadline, status, created_at)
forms (id, slug, name_bm, name_en, file_path, category, downloads_count)
faqs (id, category, question_bm, question_en, answer_bm, answer_en, sort, active)
cawangan (id, state, address, phone, fax, email, lat, lng)
chatbot_knowledge (see §7.3)
chatbot_quick_replies (see §7.3)
chatbot_settings (see §7.3)
chat_sessions (see §7.3)
chat_messages (see §7.3)
audit_logs (id, user_id, action, model, model_id, before, after, ip, ua, created_at)
settings (key, value_bm, value_en) — site config k/v
visit_stats (id, date, page_path, views, unique_visitors) — seeded fake
```

---

## 11. Migration / Sample-content Seeding

### 11.1 Seeders draw from `reference/scrape/`

```
JKPTGContentSeeder
  ├─ Pages × 25 (korporat sub + cawangan)
  ├─ Services × 8 (perkhidmatan teras)
  ├─ News × 10 (sample berita)
  ├─ Tenders × 5
  ├─ Forms × 14 (PDFs already in 04-pdfs/)
  ├─ FAQs × 30 (5 categories)
  ├─ Chatbot KB × 50 (BM+EN)
  ├─ Cawangan × 15
  ├─ Users × 4 (sample)
  └─ Settings (contact, social, footer)
```

Source: `reference/scrape/02-html/_index.csv` for metadata, body via cheerio strip in seeder. PDFs copied to `storage/app/public/borang/`.

### 11.2 Legacy URL redirect demo

`config/redirects.php` (~80 entries) → `LegacyRedirect` middleware → 301:

```
/index.php/orang-awam              → /akaun?persona=orang-awam
/my/soalan-lazim-3                 → /soalan-lazim
/my/hubungi-kami/ibu-pejabat       → /hubungi/ibu-pejabat
/my/peta-laman                     → /peta-laman
/my/borang-3                       → /panduan/borang
/my/korporat/visi-misi-objektif    → /korporat/visi-misi
/my/panduan/permohonan/pengambilan-tanah        → /perkhidmatan/pengambilan-tanah
/my/panduan/permohonan/pengambilan-tanah/sop    → /perkhidmatan/pengambilan-tanah/sop
/my/panduan/permohonan/pengambilan-tanah/carta-alir → /perkhidmatan/pengambilan-tanah/carta-alir
/my/panduan/permohonan/pembahagian-harta-pusaka → /perkhidmatan/pusaka-kecil
/my/panduan/permohonan/pajakan-tanah-persekutuan → /perkhidmatan/pajakan
/my/panduan/permohonan/penyerahan-balik-pelepasan-tanah → /perkhidmatan/penyerahan-balik
/my/panduan/permohonan/lesen-pasir → /perkhidmatan/lesen-pasir
/my/panduan/permohonan/permohonan-penyewaan-tanah-persekutuan → /perkhidmatan/penyewaan
/my/sumber/data-terbuka-kerajaan   → /sumber/data-terbuka
/my/korporat/cawangan-negeri/jkptg-{state} → /cawangan/{state}
…
```

### 11.3 Migration capability page

Filament `/admin/migration` (super-admin only):
- Upload SQL dump or scrape JSON → preview rows → import (real for Pages + News + Forms)
- MyLAND import button disabled (capability stub)

### 11.4 Migration plan deliverable

`MIGRATION-PLAN.md` (Stage 0.5 follow-up — separate doc, not part of code). Outlines: pre-cutover audit, cutover window, post-cutover monitoring, rollback. Bundled with tender response.

---

## 12. Out of Scope for Prototype

- Real LLM integration (chatbot uses canned KB matcher)
- Real myID/IDN OIDC handshake (button disabled)
- Real MyLAND data sync (link-out only)
- Real SISPAA push (link-out)
- Real Centralized Log forwarding
- Real SPLaSK reporting (only tracking pixel placeholder)
- 3-environment topology (single Hostinger instance)
- Stress test, FAT, SPA hardening (production-stage Item 5)
- Performance baseline via Lighthouse / axe (Stage 9 Track A)
- Production myID licence + integration

These are documented in `CONSTRAINTS.md` for production scope and called out as "demo: not implemented" UI labels in the prototype.

---

## 13. Acceptance Criteria

The prototype is "done" (Stage 6 mockup ready for tender) when:

1. Public homepage renders all 11 sections (§5.3) responsive across 375 / 768 / 1440px without overflow.
2. All 6 megamenu sections clickable, sub-trees populated, breadcrumbs working.
3. Click-depth verified: 8 core services + every korporat sub-page reachable in ≤ 3 clicks from `/`.
4. BM/EN toggle persists across pages, all UI strings translated.
5. Accessibility panel toggles work + persist; keyboard-only nav reaches every interactive element.
6. Chatbot bubble appears on every public page, opens, accepts input, returns canned answer with source citation.
7. `/login` works for all 4 sample users; super-admin lands at full Filament dashboard.
8. Filament resources (Pages, News, Tenders, Forms, FAQ, Chatbot KB, Users) all CRUD-functional with role gates.
9. Audit log records login + admin CRUD events.
10. ≥ 50 legacy URLs from scrape redirect (301) to new IA paths.
11. 4 Pautan Utama PPPA visible top-right on every public page; mandatory footer links present.
12. Print stylesheet works on `/perkhidmatan/{slug}/sop` and `/panduan/akta/{slug}` pages.
13. PDF download generates on top 5 legal pages.
14. Site deployed to Hostinger via GitHub auto-pull, accessible at demo URL.
15. README documents: deploy steps, sample logins, demo URL, list of stubbed features with rationale.

---

## 14. Open Questions for Stage 4 (`/plan-eng-review`)

- Filament v3 vs v4 — confirm latest stable
- Spatie Permission vs Filament Shield for role mgmt
- Scout DB driver vs Meilisearch (for prototype only — DB simpler)
- Browsershot (Chrome) vs wkhtmltopdf for PDF gen on Hostinger shared
- Filament locale switcher — community plugin or roll own
- Audit log: spatie/laravel-activitylog vs custom
- Map embed on `/hubungi/ibu-pejabat` — Google Maps (key required) vs OpenStreetMap (no key)

---

## 15. Compliance Cross-reference

| Requirement | Source | Section in this SPEC |
|-------------|--------|----------------------|
| 4 Pautan Utama top-right | PPPA §3.2.1.i | §5.3 (2), §13 ac11 |
| Jata Persekutuan on homepage | PPPA §3.2.1.b.i + Surat Pekeliling Am 1/2022 | §5.3 (2) |
| Bilingual BM + EN | PPPA §3.3.1.a + SOC §I/II §9 | §8.2 |
| FAQ section | PPPA §3.3.1.b | `/soalan-lazim` |
| AI Chatbot | SOC Item 2 | §7 |
| WCAG OKU | PPPA §3.3.1.o + SOC §I/II §10–13 | §8.1 |
| Hot topic / kata kunci | PPPA §3.3.1.i | Footer §5.3 (10) |
| Customer Charter | PPPA §3.3.1.u | `/korporat/piagam-pelanggan` |
| Privacy + Disclaimer + Copyright | PPPA §3.3.1.v/w/x | mandatory footer links |
| Mobile + QR | PPPA §3.3.1.s | §8.3 + footer |
| MyGov + Open Data link | PPPA §3.3.1.q.ii | mandatory footer |
| Mandatory pages: Visi/Misi, Hubungi, CDO, etc. | PPPA §3.4.1 | §5.1 KORPORAT |
| Auto-luput on tender/news | PPPA §3.4.1.e | tenders + news tables |
| End-to-end services | PPPA §3.4.1.k | acknowledged, prototype links to MyLAND |
| e-Penyertaan | PPPA §3.4.1.l | `/hubungi/aduan` (in-portal Maklum Balas) |
| Open Data | PPPA §3.4.1.m | `/sumber/data-terbuka` |
| HTTPS + GPKI | PPPA §4.6.3 | §4 compliance layer |
| OWASP | SOC §I/II §20 | §4 compliance layer |
| SPLaSK tagging | SOC §I/II §20.iv | §4 compliance layer |
| Audit Trail + Centralized Log | SOC §I/II §26.viii | audit_logs table |
| 11 plugins (Security, Slider, Caching, SEO, Backup, Image-Opt, Cleaning, Analytics, Broken-Link, Social, Flipping Book) | SOC §I/II §14–15 | implemented as Laravel packages or visible in admin Settings |
| End-to-end response time <5s | SOC §I/II §19.i | Tailwind purge + lazy load |
| Click depth ≤3 | SOC §I/II §19.ii | §5.4 |
| Redirect ≤5 chain | SOC §I/II §19.iii | redirect map |
| Source code handover | SOC §6 | GitHub repo public/handover |
| Sample users + login | user-requested | §6.1 |
| Dashboard | user-requested | §9.2 |

---

## 16. Sign-off

Decisions in §3 (stack), §5 (IA), §6 (auth), §7 (chatbot), §8 (edge cases), §11 (migration) are **locked**. Re-debate gated to: explicit user override, RFI/PPPA update, or scope-rewrite.

Next stage: `/extract-design` against `https://www.jkptg.gov.my` + 2-3 reference govt portals (Stage 2), then `/design-consultation` to produce `DESIGN.md` (Stage 3), then `claude-mem:make-plan` to produce `PLAN.md` (Stage 4).
