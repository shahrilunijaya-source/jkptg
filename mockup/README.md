# Portal-JKPTG Stage 6 HTML Mockup

**Locked:** 2026-05-06
**Purpose:** Static HTML/CSS demo of Stage 5 visual variant winners. Approval gate before Phase 7 Laravel Blade integration.

---

## Files

| Path | Page | Stage 5 variant |
|------|------|-----------------|
| `index.html` | Homepage | Hero variant A (overlay) + 6 service tiles + 3-tab news + agency carousel |
| `persona/orang-awam.html` | Persona landing - Orang Awam | Variant A (classic hero + 3-col grid + sidebar) |
| `perkhidmatan/pengambilan.html` | Service detail - Pengambilan Tanah | Variant B (sticky left nav + sticky CTA) |
| Megamenu | Embedded in `index.html` header | Variant C (hero-style featured cards) |

## How to view

Open any HTML file directly in a browser. No build step needed:

```powershell
start mockup/index.html
```

Or serve via any static server for cleaner relative paths:

```powershell
# Python
python -m http.server 8000 --directory mockup
# Then visit http://localhost:8000

# Node (npx)
npx serve mockup
```

## Mockup-only shortcuts (will change in Phase 7)

| Mockup uses | Production uses |
|-------------|-----------------|
| Tailwind via CDN (`cdn.tailwindcss.com`) | `npm run build` compiled bundle (DESIGN.md anti-pattern: no CDN in prod) |
| Lucide icons via CDN (`unpkg.com/lucide`) | Inline SVG sprites or per-icon import (PPPA 3.2.1.f: <=1KB each) |
| Inline `<script>` for tailwind.config + Alpine-style toggles | External JS bundle, Alpine.js via Livewire 3 |
| Unsplash placeholder hero photo | Real JKPTG/Putrajaya photography (sourced in Phase 7) |
| Icon placeholder for JATA Negara | Real JATA SVG from official source |
| Hardcoded BM strings | `lang/ms/*.php` + `lang/en/*.php` translation keys |
| Static megamenu featured cards (3 hardcoded) | DB-driven via `ChatbotKnowledge` / `Service` models, admin-curated |
| Mock visitor count "1,234,567" | Real `visit_logs` aggregate |
| Empty `href="#"` links | Laravel named routes |

## What is intentionally NOT in mockup

- Login + auth flow (Phase 2 wiring)
- Filament admin (Phase 6.5)
- Working chatbot (visual bubble only - Phase 9)
- Actual search results (Phase 11)
- BM/EN toggle behavior (visual only - Phase 4 + 10)
- Borang download links (Phase 12.3)
- Form validation states (Phase 4.5 interaction state matrix)

## Approval checklist (sign off before Phase 7)

Visual:
- [ ] Hero photo + scrim contrast meets WCAG AA (4.5:1 for body text)
- [ ] Persona doors visible above-fold on desktop 1280x800
- [ ] Persona doors readable on mobile 375x667
- [ ] Megamenu opens on click, closes on outside click + ESC
- [ ] Service detail sticky nav highlights current section on scroll
- [ ] Sticky Mohon CTA does not overlap chatbot bubble on mobile
- [ ] Footer 4-col stacks cleanly on mobile
- [ ] BM language selector visually highlighted

Brand:
- [ ] Navy `#243D57` matches DESIGN.md A2 token
- [ ] Inter body + Poppins display loaded from Google Fonts
- [ ] JATA Negara placement top-left (PPPA 3.2.1.b)
- [ ] 4 Pautan Utama top-right (PPPA mandatory)

Accessibility:
- [ ] Skip-to-content link works on Tab
- [ ] Accessibility panel left edge visible on >=md screens
- [ ] All interactive elements keyboard-reachable
- [ ] Focus rings visible on Tab nav
- [ ] `lang="ms"` attribute present
- [ ] Form inputs have associated labels (search input)
- [ ] Color contrast passes for navy-on-white text

PPPA + tender:
- [ ] Mandatory footer links (Disclaimer, Polisi Privasi, Polisi Keselamatan, Peta Laman) present
- [ ] Visitor count + last-update date in footer
- [ ] Hak Cipta line bottom

## Open issues / parking lot

- Hero photo Unsplash placeholder must be replaced with JKPTG-licensed photography for tender submission. Action: Phase 7 content audit.
- Megamenu featured cards (3) are hardcoded; production needs admin-curated. Acceptable for mockup.
- Pautan Agensi carousel (homepage bottom band) shows mock partners. Design review noted: kill if no real partner agreements. Action: Phase 7 content audit.
- Mobile megamenu drawer not in mockup (off-canvas drawer pattern). Will build during Phase 4 Livewire layout.
- Chatbot bubble is visual only - no panel. Live demo built Phase 9.

## Next stage

**Stage 6.5 - Auth + Filament admin scaffold** (proceeds after mockup approval)

1. `composer create-project laravel/laravel:^11 portal-jkptg`
2. Phase 0.5 Hostinger plan tier verification (PHP 8.3, MySQL 8, SSH composer, outbound HTTPS to api.anthropic.com)
3. Phase 1 scaffold + .gitignore + GitHub repo
4. Phase 2 User implements `FilamentUser`, sample users seeded, `/admin/login` works (LESSONS rule 3)

Then Phase 3+ build resumes per `PLAN.md`.
