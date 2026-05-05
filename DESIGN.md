# Portal-JKPTG — Design System

**Status:** locked 2026-05-06
**Profile:** Navy (govt formal), per CLAUDE.md
**Scope:** Tokens + components for the prototype Laravel build (Stage 7).
**Source:** `reference/design-extract/EXTRACT-SUMMARY.md` + Stage 1 `SPEC.md` + PPPA Bil 1/2025 design rules + SOC functional rules.

Decisions in this document are **locked**. Late changes require explicit user override (LESSONS rule 2).

---

## 1. Brand Identity

| Attribute | Value |
|-----------|-------|
| Mood | Formal, dignified, modern, accessible |
| Tone | Professional, trustworthy, citizen-first |
| Anti-pattern | Loud social-media banners (existing portal "JOM LIKE FOLLOW SHARE"), purple gradients, cluttered megamenus |
| Reference peers | JPA (formality), MyGovernment (citizen UX), digital.gov.my (modernity) |
| Primary accent | Navy blue + JATA Negara red/yellow as restrained accents |

---

## 2. Tokens

### 2.1 Color Palette

```
/* Primary — navy formal (JPA-style) */
--c-primary:        #243D57   /* navy header, primary CTA */
--c-primary-700:    #1E3247   /* hover dark */
--c-primary-500:    #4C75A0   /* secondary navy, links on dark */
--c-primary-300:    #8FA9C4   /* tertiary */
--c-primary-100:    #E8EFF6   /* pale wash, soft bg */

/* Surface */
--c-surface:        #FFFFFF
--c-surface-mute:   #F8FAFC   /* alternating sections */
--c-surface-dark:   #0F1E33   /* footer, dark hero overlay */

/* Body */
--c-body:           #1F2937   /* slate-800 — body text */
--c-body-mute:      #62707F   /* slate-500 — captions, meta */
--c-body-dim:       #94A3B8   /* slate-400 — placeholder, disabled */

/* Border */
--c-border:         #E5E7EB
--c-border-strong:  #CBD5E1

/* Functional */
--c-success:        #16A34A
--c-warning:        #EAB308
--c-info:           #4C75A0
--c-error:          #DC2626

/* JATA Negara accents (restraint — only on logo, badges, footer JATA) */
--c-jata-yellow:    #FBBF24
--c-jata-red:       #B91C1C

/* Focus ring (WCAG 2.4.7) */
--c-focus:          #4C75A0
```

**Tailwind config maps:** primary = `#243D57` family, neutrals = slate scale, accents = yellow/red sparingly.

### 2.2 Dark Mode (nice-to-have, Q7B)

Dark mode is implemented as Tailwind `dark:` variants but **not surfaced in user UI** unless approved post-prototype. Token swap below; toggle hidden.

```
[data-theme="dark"]
  --c-surface:      #0F1E33
  --c-surface-mute: #1B2D4D
  --c-body:         #E2E8F0
  --c-body-mute:    #94A3B8
  --c-border:       #243D57
```

### 2.3 Typography (Q1B — dual font)

```
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@600;700&display=swap');

--font-body:    'Inter', system-ui, -apple-system, sans-serif;
--font-display: 'Poppins', 'Inter', sans-serif;
--font-mono:    ui-monospace, 'JetBrains Mono', monospace;
```

**Self-host both fonts** at `public/fonts/` for production (avoid CDN dep, GDPR-clean).

### 2.4 Type scale

| Token | Size / line-height | Weight | Family | Usage |
|-------|--------------------|--------|--------|-------|
| `display-1` | 60 / 64 | 700 | Poppins | hero h1 (mobile: 40/44) |
| `display-2` | 48 / 56 | 700 | Poppins | section h1 |
| `h1` | 36 / 44 | 700 | Poppins | page h1 |
| `h2` | 30 / 36 | 600 | Poppins | major section |
| `h3` | 24 / 32 | 600 | Poppins | sub-section |
| `h4` | 20 / 28 | 600 | Inter | card title |
| `h5` | 18 / 26 | 600 | Inter | small heading |
| `body-lg` | 18 / 28 | 400 | Inter | lead paragraph |
| `body` | 16 / 24 | 400 | Inter | default |
| `body-sm` | 14 / 20 | 400 | Inter | captions, meta |
| `caption` | 12 / 16 | 500 | Inter | uppercase label |

PPPA §3.2.1.d "uniform fonts and sizes" — single dual-pair across every page; never override per-component.

### 2.5 Spacing scale (Tailwind native)

```
0  → 0
1  → 4px
2  → 8px
3  → 12px
4  → 16px
5  → 20px
6  → 24px
8  → 32px
10 → 40px
12 → 48px
14 → 56px
16 → 64px
20 → 80px
24 → 96px
32 → 128px
```

Section vertical padding: `py-16 md:py-24` (64px / 96px). Card padding: `p-6` (24px).

### 2.6 Radii

| Token | Value | Usage |
|-------|-------|-------|
| `sm` | 4px | chips, badges, inputs |
| `md` | 8px | cards, buttons, alerts |
| `lg` | 12px | persona doors, large cards |
| `xl` | 16px | hero card, modal |
| `full` | 9999px | chatbot bubble, avatars, pills |

### 2.7 Shadows

```
--shadow-xs:    0 1px 2px rgba(15, 30, 51, 0.05);
--shadow-sm:    0 1px 3px rgba(15, 30, 51, 0.10), 0 1px 2px rgba(15, 30, 51, 0.06);
--shadow-md:    0 4px 6px rgba(15, 30, 51, 0.10), 0 2px 4px rgba(15, 30, 51, 0.06);
--shadow-lg:    0 10px 15px rgba(15, 30, 51, 0.10), 0 4px 6px rgba(15, 30, 51, 0.05);
--shadow-xl:    0 20px 25px rgba(15, 30, 51, 0.10), 0 10px 10px rgba(15, 30, 51, 0.04);
--shadow-focus: 0 0 0 3px rgba(76, 117, 160, 0.4);
```

### 2.8 Breakpoints

```
sm: 640px       /* small tablets, large phones */
md: 768px       /* tablets */
lg: 1024px      /* small laptops */
xl: 1280px      /* desktops */
2xl: 1536px     /* large monitors */
```

Mobile-first base: 375px (iPhone SE).

### 2.9 Z-index scale

```
0   default
10  sticky elements
20  dropdowns
30  fixed header
40  utility bar overlays
50  chatbot bubble
60  modals
70  toast notifications
80  accessibility panel overlay
90  cookie banner
100 critical alerts
```

---

## 3. Iconography

- **Library:** Lucide (https://lucide.dev) — MIT, single-color SVG, `currentColor` stroke
- **Size constraint:** ≤1KB per icon (PPPA §3.2.1.f) — Lucide tree-shaken icons hit ~300–500 bytes each
- **4 Pautan Utama icons (PPPA §3.2.1.i):**
  - `circle-help` → Soalan Lazim
  - `phone` → Hubungi Kami
  - `megaphone` → Aduan & Maklum Balas
  - `map` → Peta Laman
- **Service icons:** consistent stroke-width 1.75, 24×24 viewport
- **Decorative icons:** dim color (`--c-body-mute`)
- **Functional icons:** primary color (`--c-primary`)

---

## 4. Logo Usage

| Asset | File | Usage |
|-------|------|-------|
| Jata Negara | `reference/JATA-NEGARA.png` (also AI-01 variant) | Top-left header (PPPA §3.2.1.b.i + Surat Pekeliling Am 1/2022 — federal agency) |
| JKPTG logo (text mark) | `reference/LOGO JKPTG PNG.png` | Top-left header beside Jata |
| Combined header | scrape `Portal_Header_BM_DIS2023.png` / `Portal_Header_BI_FEB2024.png` | Reference layout, will recreate in SVG |

**Rules:**
- Jata Negara always **left** of agency name
- Min height: 40px desktop, 32px mobile
- Always full-color; no monochrome treatment of Jata
- Clear-space: equal to height of Jata on all sides
- Never on busy backgrounds — solid white or solid navy only

---

## 5. Components

### 5.1 Button

| Variant | Bg | Text | Border | Radius | Padding | Use |
|---------|----|------|--------|--------|---------|-----|
| primary | `--c-primary` | white | none | `md` | `px-6 py-3` | main CTAs |
| primary-lg | `--c-primary` | white | none | `md` | `px-8 py-4` | hero CTAs |
| secondary | white | `--c-primary` | 1px `--c-primary` | `md` | `px-6 py-3` | secondary action |
| ghost | transparent | `--c-primary` | none | `md` | `px-4 py-2` | inline actions |
| danger | `--c-error` | white | none | `md` | `px-6 py-3` | destructive |
| disabled | `--c-border` | `--c-body-dim` | none | `md` | as base | disabled state |

**Hover:** primary → `--c-primary-700`. Secondary → fill `--c-primary` text white.
**Focus:** visible ring `--shadow-focus`.
**Touch target:** min 44×44px (WCAG 2.5.5).

### 5.2 Persona Door (homepage hero)

```
┌──────────────────────────┐
│     [icon, 48×48]        │
│                          │
│  Orang Awam              │  ← h4
│                          │
│  Senarai perkhidmatan    │  ← body-sm muted
│  atas talian JKPTG       │
│                          │
│  [Akses Sekarang →]      │  ← ghost button
└──────────────────────────┘
```

- Card bg: white, `--shadow-sm`
- Border: 1px `--c-border` → on hover: `--c-primary` + `--shadow-md` lift
- Padding: `p-8` (32px)
- Radius: `lg` (12px)
- Click anywhere routes to persona landing
- Mobile: stacks full-width, `p-6`

### 5.3 Service Tile (homepage strip + Perkhidmatan index)

```
┌────────────────────────┐
│  [icon, 32×32 navy]    │
│                        │
│  Pengambilan Tanah     │  ← h5
│                        │
│  Akta 1960. SOP &      │  ← body-sm mute
│  carta alir.           │
│                        │
│  [Lihat lanjut →]      │  ← inline link, primary
└────────────────────────┘
```

- 6-tile strip on homepage: `grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4`
- Card bg: `--c-surface-mute`
- Hover: lift + border `--c-primary-300`
- Padding: `p-6`
- Radius: `md`

### 5.4 News Card (Maklumat Terkini)

```
┌──────────────────────────────┐
│  [thumbnail 16:9]            │
├──────────────────────────────┤
│  Berita · 13 Apr 2026        │  ← caption
│                              │
│  Pegawai, pentadbir tanah    │  ← h4
│  perlu teliti, berhemah...   │
│                              │
│  Berita lebih lanjut boleh   │  ← body-sm
│  diperolehi di pautan...     │
│                              │
│  [Baca lanjut →]             │  ← ghost button
└──────────────────────────────┘
```

- Image with `loading="lazy"` + alt
- Date format: `D MMM YYYY` (BM: `13 Apr 2026`)
- Hover: image zoom 1.03, card lift

### 5.5 4 Pautan Utama (PPPA mandatory, top-right)

```
┌───┐ ┌───┐ ┌───┐ ┌───┐
│ ❓ │ │ ☎ │ │ 📢 │ │ 🗺 │
│FAQ│ │Hub│ │Adu│ │Peta│
└───┘ └───┘ └───┘ └───┘
```

- 4 buttons in a row, top-right corner of header
- Each: vertical stack of icon (24×24) + label (caption, 11px)
- Min target: 48×48
- Hover: bg `--c-primary-100`
- Mobile: collapses to single dropdown

### 5.6 Megamenu

**Open behavior:** **Click + keyboard only** (locked by design review 2026-05-06 — supersedes earlier Q4B hover-delay). WCAG 2.1.1 / 2.4.3 compliant.

**Structure:**

```
nav (sticky on scroll, --c-primary background, white text)
  > ul (6 items)
    > li (top-level)
      └── on hover (200ms delay) OR click:
          ┌─────────────────────────────────────────────────────────┐
          │ Sub-tree panel (full-width, --c-surface, --shadow-lg)   │
          │  - 4 columns desktop                                    │
          │  - 1 column mobile (accordion)                          │
          │  - links: body-sm, hover --c-primary                    │
          │  - section headings: caption, --c-primary, uppercase    │
          └─────────────────────────────────────────────────────────┘
```

- Top-level item: `px-4 py-3`, hover underline (animated 200ms)
- Active state: persistent underline + breadcrumb on inner pages
- ESC: close panel; Tab: cycle within open panel; arrows: navigate items
- Mobile breakpoint: ≤1024px → hamburger → slide-in drawer + accordion sub-trees

### 5.7 Floating Chatbot Bubble

- Position: `fixed bottom-6 right-6` (16px safe area)
- Size: 56×56px (WCAG ≥44×44 + visual prominence)
- Bg: `--c-primary`, white icon
- Shadow: `--shadow-lg`
- Hover: scale 1.05 + `--c-primary-700`
- Pulse animation on first page load (3 cycles, then static)
- Click → side panel slides in from right (320px desktop) or fullscreen mobile

**Side panel:**

```
┌────────────────────────────────────┐
│ 💬 Tanya JKPTG (AI)            ✕  │  ← header, --c-primary, white
├────────────────────────────────────┤
│ "Selamat datang. Saya boleh       │
│  bantu anda mengenai perkhidmatan │
│  tanah JKPTG."                    │
│                                    │
│ [Pengambilan] [Pusaka] [Borang]   │  ← quick replies (chips)
│ [Pajakan] [Hubungi]                │
├────────────────────────────────────┤
│ User msg (right-aligned bubble,   │
│ --c-primary bg, white text)       │
│                                    │
│ Bot msg (left-aligned bubble,     │
│ --c-surface-mute bg, body text)   │
│   📚 Sumber: Soalan Lazim →...    │
├────────────────────────────────────┤
│ [_____ taipkan soalan ____] [➤]   │  ← input + send btn
└────────────────────────────────────┘
```

### 5.8 Accessibility Panel (Q5B — top-right utility entry)

**Trigger:** ♿ icon in top utility bar (right side).
**Panel:** opens as **dropdown** below trigger (NOT pinned to edge).

```
┌────────────────────────────────┐
│ ♿ Aksesibiliti                │
├────────────────────────────────┤
│ Saiz fon                       │
│  [A−]  [A]  [A+]              │  ← 3-step toggle
│                                │
│ Mod paparan                    │
│  ○ Standard                    │
│  ● Kontras Tinggi              │  ← radio
│  ○ Mod Buta Warna              │
│                                │
│ Lain-lain                      │
│  ☐ Saiz teks lebih besar       │  ← checkbox toggles
│  ☐ Baca halaman (TTS)          │
└────────────────────────────────┘
```

- Persists settings to localStorage
- Live updates without reload
- Keyboard accessible (radio group + tab order)

### 5.9 Footer (Q6A — dark navy 4-column)

```
┌───────────────────────────────────────────────────────────────────────┐
│  Bg: --c-surface-dark (#0F1E33)                                       │
│  Text: white / --c-body-mute                                          │
├───────────────────────────────────────────────────────────────────────┤
│ HUBUNGI KAMI    │ KOD QR        │ KATA KUNCI POPULAR │ BILANGAN PELAWAT│
│ JATA logo small │ [QR PNG]      │ • Tanah            │ Hari Ini  ____  │
│ Aras 4 Podium 1 │               │ • SOP              │ Minggu    ____  │
│ Menara PETRA    │ Imbas untuk   │ • Pengambilan      │ Bulan     ____  │
│ Putrajaya       │ versi mudah   │ • Penyerahan       │ Total     ____  │
│ ☎ +603 8000 8000│ alih          │ • Pelepasan        │                 │
│ ✉ webadmin@...  │               │                    │                 │
│ [social icons]  │               │                    │                 │
├───────────────────────────────────────────────────────────────────────┤
│ Bottom strip (--c-primary-700 bg)                                     │
│ Penafian │ Dasar Web │ Dasar Keselamatan │ Dasar Privasi │ Panduan    │
│ Pengguna │ Peta Laman │ Hak Cipta © 2026 Jabatan Ketua Pengarah ...  │
│                                                                       │
│ Pautan Mandatori: MyGov ↗ │ Open Data Portal ↗                        │
│ Tarikh Kemas Kini: 6 Mei 2026                                          │
└───────────────────────────────────────────────────────────────────────┘
```

### 5.10 Header (sticky on scroll)

```
┌────────────────────────────────────────────────────────────────────┐
│ Utility bar — --c-surface-dark, h-9                                │
│  [empty]                          🔍 │ BM | EN │ ♿ │ Login         │
├────────────────────────────────────────────────────────────────────┤
│ Brand bar — white, h-20                                            │
│  [Jata 48px] PORTAL RASMI                          ❓ ☎ 📢 🗺      │
│  [JKPTG]    JKPTG                                  Pautan Utama (4)│
│             Kementerian Sumber Asli & Kelestarian Alam             │
├────────────────────────────────────────────────────────────────────┤
│ Megamenu bar — --c-primary, h-12, sticky                           │
│  UTAMA · PERKHIDMATAN · PANDUAN & BORANG · KORPORAT · SUMBER · HUBUNGI│
└────────────────────────────────────────────────────────────────────┘
```

### 5.11 Form Fields (Aduan, Maklum Balas, Login, Filament)

- Input bg: white, border `--c-border`, radius `sm`
- Focus: border `--c-primary`, ring `--shadow-focus`
- Label: caption, above input, `mb-2`
- Help text: body-sm muted, below input
- Error: border `--c-error`, message below in `--c-error`
- Required mark: red asterisk after label

### 5.12 Breadcrumb

```
🏠 Laman Utama  ›  Korporat  ›  Visi · Misi · Objektif
```

- Body-sm color `--c-body-mute`
- Separator: `›` (10px gap)
- Current page: `--c-body` (no link)
- Above page h1, `mb-4`

### 5.13 Accordion (FAQ, Sub-trees, Akta listing)

- Header: white bg, border-bottom `--c-border`
- Hover: bg `--c-primary-100`
- Open state: bg `--c-primary-100` border-bottom `--c-primary`
- Icon: chevron-right (closed) → chevron-down (open), 200ms transition
- Body: `--c-surface-mute` bg, `p-6`
- One-at-a-time mode default for FAQ; multi-open for sub-trees

### 5.14 Alert / Toast

| Variant | Bg | Border-left | Icon | Use |
|---------|----|-----|------|-----|
| info | `--c-primary-100` | 4px `--c-primary` | info | system messages |
| success | `#DCFCE7` | 4px `--c-success` | check | confirmation |
| warning | `#FEF3C7` | 4px `--c-warning` | alert-triangle | maintenance |
| error | `#FEE2E2` | 4px `--c-error` | x-circle | failures |

### 5.15 Table (Data Terbuka, Cawangan listing, audit log)

- Header row: `--c-primary-100` bg, caption uppercase, `--c-primary`
- Body row: white, `--c-border` bottom
- Hover: `--c-surface-mute`
- Zebra: every other row `--c-surface-mute`
- Sortable columns: arrow icon

### 5.16 Pagination

```
‹ Sebelumnya   1   2  [3]  4   5   ...  10   Seterusnya ›
```

- Active: `--c-primary` bg, white text, `md` radius
- Inactive: hover `--c-primary-100`
- Disabled: `--c-body-dim`

---

## 6. Layout System

### 6.1 Containers

```
.container-tight   max-w-3xl  (768px)   — articles, korporat content
.container-base    max-w-6xl  (1152px)  — most pages
.container-wide    max-w-7xl  (1280px)  — homepage hero, megamenu width
.container-full    full-bleed         — hero backgrounds, footer
```

Mobile padding: `px-4` (16px). Tablet+: `px-6` (24px). Large: `px-8` (32px).

### 6.2 Homepage layout (per SPEC §5.3)

```
[utility bar]                        h-9     full-bleed
[brand bar]                          h-20    container-wide
[megamenu]                           h-12    full-bleed bg, container-wide content
[hero]                               aspect 16:7 desktop / stacked mobile  container-wide
[perkhidmatan teras strip]           py-16   container-base
[news + calendar split]              py-16   container-base, alt bg
[info JKPTG icon strip]              py-12   container-base
[pautan agensi carousel]             py-12   container-base, alt bg
[footer]                             auto    full-bleed, content container-base
```

### 6.3 Standard content page layout

```
[header (sticky)]
[breadcrumb]
[h1 page title]
[body content — container-tight or two-column with sidebar]
[related links / next-prev]
[footer]
```

---

## 7. Hero (Q3B — photographic)

**Pattern:** photographic background image of land/Putrajaya/JKPTG building, dark overlay (rgba 36 61 87 / 0.7), content overlaid in white.

```
┌────────────────────────────────────────────────────────────────────┐
│                                                                    │
│   PORTAL RASMI                                                     │
│                                                                    │
│   Jabatan Ketua Pengarah                                          │
│   Tanah & Galian Persekutuan                              ┌─────┐ │
│                                                            │ 👤  │ │
│   Pentadbiran tanah negara                                 │OA   │ │
│   yang berintegriti & berhemah.                            └─────┘ │
│                                                            ┌─────┐ │
│   [Mohon Perkhidmatan →]    [Tanya JKPTG 💬]              │ 🏛️  │ │
│                                                            │KJ   │ │
│                                                            └─────┘ │
│                                                            ┌─────┐ │
│                                                            │ 👨  │ │
│                                                            │WJ   │ │
│                                                            └─────┘ │
└────────────────────────────────────────────────────────────────────┘
[image: aerial Putrajaya / Menara PETRA / land registration scene]
```

Mobile: image stays full-width but reduced height; persona doors stack below.

### 7.1 Hero h1

- Use `display-1` (60/64 desktop, 40/44 mobile)
- White, weight 700
- Text shadow: `0 2px 4px rgba(0,0,0,0.4)` for legibility on photo

### 7.2 Hero CTAs

- Primary: "Mohon Perkhidmatan" → `/perkhidmatan`
- Secondary (ghost on dark): "Tanya JKPTG 💬" → opens chatbot panel

### 7.3 Image policy

- Min resolution: 1920×1080 (loaded with `srcset` + AVIF/WebP/JPG fallback)
- License: own photography (JKPTG provides) or CC-BY/Unsplash
- Subject: official JKPTG / Putrajaya / land scene — never stock-cliché office handshake
- Alt text: descriptive, BM + EN parallel

---

## 8. Motion / Interaction

| Effect | Duration | Easing | Where |
|--------|----------|--------|-------|
| Fade-in on scroll | 500ms | ease-out | section reveals |
| Card hover lift | 200ms | ease | cards, tiles |
| Megamenu open | 200ms hover-delay + 250ms slide | ease-out | top-level items |
| Side-panel chatbot slide | 300ms | cubic-bezier(0.4, 0, 0.2, 1) | bubble open |
| Accordion expand | 250ms | ease-in-out | FAQ rows |
| Button press | 100ms | ease | active state |
| Pulse (chatbot first-load) | 1500ms × 3 | ease-in-out | onboarding nudge |
| Reduce motion | use `@media (prefers-reduced-motion)` to disable all transitions ≤100ms |

**No carousel autoplay** (PPPA-friendly; user controls).

---

## 9. Accessibility (WCAG 2.1 AA)

- Min contrast: 4.5:1 body, 3:1 large text and UI
- Verify in CI: `axe-core` Playwright pass on every page
- Keyboard: tab order matches visual order; Enter activates; ESC closes overlays
- Focus ring: always visible on `:focus-visible`
- Skip-to-content link: visible on focus only, top-left
- Form labels: every input has associated `<label for>`
- Image alts: required field on Filament Image upload
- Headings: one h1 per page, no skipped levels
- Lang attr: `<html lang="ms">` or `<html lang="en">` per current locale
- Screen-reader-only text: `.sr-only` Tailwind class for context (e.g., button icon labels)
- Live regions: `aria-live="polite"` for chatbot responses, `aria-live="assertive"` for errors
- Color is never the sole differentiator (icons + text labels everywhere)
- TTS: Web Speech API on accessibility panel toggle

---

## 10. Print Stylesheet (`print.css`)

```css
@media print {
  body { font-family: serif; color: #000; background: #fff; }
  header, footer, nav, .megamenu, .chatbot, .accessibility-panel,
  .utility-bar, .pautan-agensi, .news-section { display: none !important; }
  main { max-width: 100%; padding: 0; }
  h1 { font-size: 24pt; page-break-after: avoid; }
  h2 { font-size: 18pt; page-break-after: avoid; }
  a[href]::after { content: " (" attr(href) ")"; font-size: 10pt; color: #555; }
  table, tr, td, th { page-break-inside: avoid; }
  @page { margin: 1.5cm; @bottom-right { content: "JKPTG · " counter(page); } }
}
```

- Only on `/perkhidmatan/*/sop`, `/perkhidmatan/*/carta-alir`, `/panduan/akta/*`, `/panduan/borang/*`, `/korporat/piagam-pelanggan`
- "🖨 Cetak" button on these pages → `window.print()`
- Top-5 legal docs also have **"📄 Muat Turun PDF"** generated server-side via Browsershot

---

## 11. BM/EN parallel content

- Every Filament resource has `_bm` + `_en` columns where copy lives
- Locale switcher reads `app()->getLocale()`
- Null EN → BM fallback with `[BM only]` badge
- URL strategy: cookie-based (no `/en/` prefix segment)
- Date format: BM `D MMM YYYY` (`6 Mei 2026`); EN `D MMM YYYY` (`6 May 2026`)
- Number format: BM `1,234.56`; EN `1,234.56` (same locale convention)

---

## 12. Filament Admin Theme

- Same primary navy palette
- Filament v3 supports custom theme via `filament:upgrade` + Tailwind config
- Sidebar bg: `--c-primary` navy
- Top-bar: white with `--c-primary` accent border
- Resource cards use same shadow + radius tokens
- Dashboard widgets: chart colors → primary palette progression
- `/admin/login` page: same logo treatment as public

---

## 13. Component checklist (must build in Stage 7)

- [ ] Header (3-tier: utility / brand / megamenu)
- [ ] Megamenu (with sub-tree panels per SPEC §5.1)
- [ ] 4 Pautan Utama icon group
- [ ] Hero (homepage variant — photo + persona doors)
- [ ] Hero (interior page variant — title + breadcrumb only)
- [ ] Persona Door card (3 variants: orang awam, kementerian, warga)
- [ ] Service Tile (6 variants minimum, expandable)
- [ ] News Card
- [ ] Tender / Pengumuman list row
- [ ] FAQ Accordion
- [ ] Service Detail layout (SOP + Carta Alir + Borang sidebar)
- [ ] Cawangan Negeri page (15 cards, map embed)
- [ ] Aduan form
- [ ] Login page (sample-user picker + manual form + disabled myID)
- [ ] Floating Chatbot bubble + side panel
- [ ] Accessibility dropdown panel
- [ ] BM/EN toggle
- [ ] Search box (header) + search results page
- [ ] Footer (4-col + bottom strip)
- [ ] Cookie banner
- [ ] Skip-to-content link
- [ ] Print stylesheet
- [ ] Breadcrumb
- [ ] Pagination
- [ ] Alert / Toast
- [ ] Form fields (input, textarea, select, checkbox, radio, file)
- [ ] Table (Data Terbuka, Cawangan, audit log)
- [ ] Filament admin theme override (login page, sidebar, dashboard widgets)
- [ ] 404 + 503 + maintenance pages

---

## 14. Don'ts (anti-patterns)

- ❌ Purple gradients (existing portal)
- ❌ "JOM LIKE FOLLOW SHARE" social-banner megaphones in hero
- ❌ Hover-only menus (keyboard inaccessible)
- ❌ Carousel autoplay (PPPA-friendly: user controls)
- ❌ Tiny text < 14px for body
- ❌ Color-only differentiation (always icon + text + color)
- ❌ Modal walls on first visit (anon-first SPEC §6.5)
- ❌ Hover-only tooltips (need touch + keyboard alt)
- ❌ Outline removal on focus (`outline: none` without replacement ring)
- ❌ More than 3 primary colors (PPPA §3.2.1.e)
- ❌ Mismatched fonts per page (PPPA §3.2.1.d)
- ❌ Background-image on text without overlay (contrast fail)

---

## 15. Sign-off

Tokens, components, layouts, motion, accessibility, print all locked here. Stage 5 design variants must vary **decoration only** (illustration style, photo choice, micro-copy) — not break tokens. Stage 7 build implements this exactly.

Next stage: `claude-mem:make-plan` (Stage 4) → `PLAN.md` with phased build steps, then run `/plan-ceo-review`, `/plan-design-review`, `/plan-eng-review` before Stage 5.
