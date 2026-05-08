# Stage 2 — Design Extract Summary

**Captured:** 2026-05-06
**Method:** Playwright `getComputedStyle()` + full-page screenshots on each homepage

---

## 1. Portals analyzed

| # | Portal | Stack | Lang | Primary purpose |
|---|--------|-------|------|-----------------|
| 1 | https://www.jkptg.gov.my (existing) | Joomla 3.x + Gantry5 + RT Antares | ms-MY | baseline (pain points + brand) |
| 2 | https://www.digital.gov.my | Modern (oklch CSS, likely Tailwind) | en-GB | JDN — sets tone for all govt portals |
| 3 | https://www.malaysia.gov.my | Modern | ms | MyGovernment — gold UX reference |
| 4 | https://www.jpa.gov.my | Joomla | ms-my | federal formal navy peer |

---

## 2. Token comparison

### Typography

| Portal | Body font | Display font | h1 size | h1 weight | Body size / lh |
|--------|-----------|--------------|---------|-----------|----------------|
| JKPTG | Montserrat | Montserrat | 36px | 700 | 16/24 |
| digital.gov.my | **Inter** | **Poppins** | 36px | 600 | 16/24 |
| malaysia.gov.my | **Poppins** | **Poppins** | 72px | 700 | 16/24 |
| JPA | IBM Plex Sans | Heebo | 52px | 600 | 16/28 |

**Pattern:** Sans-serif everywhere. Modern govt favors **Inter** or **Poppins**. PPPA §3.2.1.d mandates uniform font across all pages — single family preferred.

### Color palette

| Portal | Primary (CTA) | Body text | Background | Footer bg |
|--------|---------------|-----------|------------|-----------|
| JKPTG | purple/indigo gradient #8a2be2 → #8c55e9 | #000 | #fff | #121212 |
| digital.gov.my | **blue #2563EB** | dark slate (oklch 0.21) | white | (light/multi) |
| malaysia.gov.my | **blue #1B76DE** | near-black #020817 | #fff | (light) |
| JPA | navy **#243D57** + **#4C75A0** | gray #62707F | #fff / very light blue #FAFBFF | navy |

**Pattern:** Blue dominant. Navy = formal (JPA, federal). Lighter blue = service-oriented (digital, malaysia.gov.my). JKPTG's purple gradient is the **outlier** — needs realignment to formal navy per CLAUDE.md design profile.

### Patterns observed

| Pattern | JKPTG | digital | malaysia.gov.my | JPA |
|---------|-------|---------|-----------------|-----|
| Top utility bar | yes | yes | yes (black) | minimal |
| 4 Pautan Utama in top-right | **yes** | no (different scope) | yes (lang + login) | yes (icons left) |
| Megamenu | yes (dj-megamenu) | horizontal nav | horizontal nav | megamenu |
| Hero slider | yes (Smart Slider) | static + animation | static photo | static photo |
| Persona doors | yes (3 cards) | no | **yes** (8 illustrated) | no |
| Service tiles | yes (Info JKPTG) | yes (Quick Links) | **yes** (categorized tabs) | minimal |
| News section | yes (3-tab) | yes (timeline + grid) | yes (Topik Popular) | yes (announcements) |
| Calendar | yes | no | yes (Tarikh Penting) | no |
| Logo carousel (related agencies) | yes | yes | no | no |
| Floating chatbot | no | no | no | no (only floating help) |
| Accessibility panel | yes (left edge icons) | hidden | yes (top-right link) | **yes** (left edge) |
| Feedback widget | no | no | **yes** (smiley scale + comment) | no |
| Footer 4-column | yes | yes | yes | yes |

---

## 3. Pain in JKPTG (baseline) → fix targets

| Pain | Source | Fix in new portal |
|------|--------|-------------------|
| Mobile cropped + overlap | screenshots Stage 0.5 | mobile-first Tailwind, tested 375/414/768 |
| Peta Laman shows only "Main Menu" + icon | screenshot | full nested sitemap rendered server-side |
| Borang page only 2 links | screenshot | searchable PDF library, category filter |
| Old `Construction_4.png` placeholder content | scrape | seeded real content from scrape |
| jQuery 1.x + MooTools (dead) | recon | Livewire 3 + Alpine (modern) |
| Purple gradient feels off-brand for govt | comparison | navy palette per CLAUDE.md profile |
| Hero "JOM LIKE FOLLOW SHARE" social-banner is loud | screenshot | dignified mission statement + CTA |
| 4 Pautan Utama tiny + cramped | screenshot | larger, clearer icons with labels |
| News image broken (`<image>` element) | screenshot | proper `<img>` with alt + lazy load |
| "Paparan terbaik 1366×768" footer note | screenshot | remove (modern responsive) |
| `images_image_Construction_4.png` stale | scrape | content audit + replace |
| Footer black `#121212` w/ social blast | screenshot | navy footer, professional |

---

## 4. Best-of patterns to adopt

From **digital.gov.my:**
- Pastel-gradient hero background (subtle, not busy)
- Timeline-style "Latest Achievements" component
- Stats chart for transparency (visitor counts)
- Quick Links grid (matches our SOC §I/II §26.iii Audit Trail + Dashboard pitch)

From **malaysia.gov.my:**
- **Persona-first illustrated doors** — directly applicable to our hybrid hero
- Categorized service tabs (Pelancongan, Perniagaan, Pendidikan, etc.) — adapt to JKPTG land services
- "Adakah Portal ini Membantu Anda?" feedback widget — PPPA §3.3.1.h kaji selidik
- Bold typography for hero h1 (72px)
- Soft blue band CTA before footer

From **JPA:**
- Navy formality (matches `Navy (govt formal)` design profile)
- Mission statement as hero image overlay
- Accessibility panel pinned left edge (consistent with existing JKPTG)
- Two-button CTA pair below hero (Pengumuman + Iklan Perolehan)

From **JKPTG existing (keep):**
- 4 Pautan Utama positioning (top-right) — already PPPA-compliant
- 3-persona cards concept (just modernize visuals)
- 3-tab news/announcement/tender component (just typography fix)
- QR code in footer
- "Kata Kunci Popular" footer column

---

## 5. Recommended tokens for JKPTG (input to Stage 3 DESIGN.md)

### Typography

```
Body: Inter (variable), 16/24, weight 400
Display (h1-h6): Inter, weights 600/700
Mono (code/audit-log only): JetBrains Mono or system monospace
Loading: ...with system-ui fallback per PPPA §3.2.1.d uniform fonts
```

Single family (Inter) for both body and display = strongest PPPA §3.2.1.d compliance ("seragam pada semua halaman"). Drops Poppins as display because Inter at 600/700 weight is sufficient for govt formal.

### Color palette (navy, formal)

```
Primary       #1E3A8A   (Tailwind blue-900) — header bg, primary CTA
Primary-light #3B82F6   (Tailwind blue-500) — hover, accent CTA
Primary-pale  #DBEAFE   (Tailwind blue-100) — soft background, card highlight
Surface       #FFFFFF   (PPPA §3.2.1.e white background recommended)
Surface-mute  #F8FAFC   (Tailwind slate-50) — alternating sections
Body          #1F2937   (Tailwind gray-800) — body text
Body-mute     #6B7280   (Tailwind gray-500) — secondary text
Border        #E5E7EB   (Tailwind gray-200)
Footer-bg     #0F172A   (Tailwind slate-900) — dark navy
Success       #16A34A
Warning       #EAB308
Error         #DC2626   — alert banners
JATA-yellow   #FBBF24   — JATA Negara accents (sparingly)
JATA-red      #B91C1C   — JATA Negara accents (sparingly)
```

PPPA §3.2.1.e: ≤3 colors not strictly enforced — neutrals don't count. Primary palette = 3 (navy + light + pale) + 4 functional + 2 JATA accents.

### Spacing scale (Tailwind defaults — match)

```
0   4   8   12  16  20  24  32  40  48  56  64  80  96  128
0   1   2   3   4   5   6   8   10  12  14  16  20  24  32   (Tailwind spacing units)
```

### Type scale

```
display-1   72px / 80lh / 700   — hero h1
display-2   48px / 56lh / 700   — section h1
h1          36px / 44lh / 700
h2          30px / 36lh / 600
h3          24px / 32lh / 600
h4          20px / 28lh / 600
body-lg     18px / 28lh / 400
body        16px / 24lh / 400   — default
body-sm     14px / 20lh / 400
caption     12px / 16lh / 500   — uppercase labels
```

### Radii

```
sm  4px   — chips, small badges
md  8px   — cards, buttons (matches gov modernity)
lg  12px  — large cards (persona doors)
xl  16px  — hero cards
full 9999 — chatbot bubble, avatars
```

### Shadows

```
xs   0 1px 2px rgba(0,0,0,0.05)
sm   0 1px 3px rgba(0,0,0,0.10), 0 1px 2px rgba(0,0,0,0.06)
md   0 4px 6px rgba(0,0,0,0.10), 0 2px 4px rgba(0,0,0,0.06)
lg   0 10px 15px rgba(0,0,0,0.10), 0 4px 6px rgba(0,0,0,0.05)
focus-ring  0 0 0 3px rgba(59,130,246,0.4)   — keyboard focus
```

### Iconography

- **Icons ≤1KB each** (PPPA §3.2.1.f) — use SVG inline, single-color, current-color stroke
- Source: **Lucide** (React-friendly, MIT, 1KB-each-after-tree-shake) or **Heroicons** (Tailwind native)
- 4 Pautan Utama icons: question-mark-circle (FAQ), phone (Hubungi), megaphone (Aduan), map (Peta)

---

## 6. Open design decisions for Stage 3 / `/design-consultation`

1. **Single font (Inter) vs dual (Inter body + Poppins display)** — Inter solo is cleaner; Poppins display gives more brand personality
2. **Primary navy shade** — #1E3A8A (Tailwind blue-900, distinctive) vs #243D57 (JPA's mute) vs #1B76DE (malaysia.gov.my service blue)
3. **Hero illustration style** — flat illustrated personas (malaysia.gov.my) vs photographic (JPA) vs iconographic (digital.gov.my)
4. **Megamenu open behavior** — click vs hover-with-delay
5. **Accessibility panel position** — left edge (JKPTG/JPA) vs top-right utility (most others)
6. **Footer style** — dark navy + 4-col vs light + minimal
7. **Dark mode** — out of scope or "nice to have"
