# Portal-JKPTG — PRODUCT.md

**Status:** synthesised 2026-05-06 from Lampiran_1 (SH05/2026), DESIGN.md, CLAUDE.md, STATE.md.
**Register:** product
**Stack:** Laravel 11 + Livewire + Blade (public) / Filament (admin) / Tailwind / Alpine.

---

## Product Purpose

The official portal of **Jabatan Ketua Pengarah Tanah dan Galian Persekutuan (JKPTG)** — Federal Department of Director General of Lands and Mines, under Kementerian Sumber Asli dan Kelestarian Alam (NRES), Putrajaya. Single platform for federal land administration: policy publication, citizen services, public complaints (aduan), tender publication, gallery, open data, and an AI-assisted FAQ chatbot. Replaces the legacy http://www.jkptg.gov.my portal. Mandatory compliance: PPPA Bil 1/2025, RAKKSSA, ISO/IEC 27001, WCAG 2.1 AA.

Tender ref: SH05/2026. Five months build, twelve months warranty.

## Users

Three primary personas are surfaced on the home page as the core entry points.

### Orang Awam (Public citizens)
Malaysian citizens, landowners, applicants, journalists. Need fast, plain-language access to land services, forms, FAQs, complaints submission, and current news. Bilingual BM (default) and EN. Frequently mobile, often older, varying literacy. They scan, they don't read. They satisfice — first reasonable option wins.

### Kementerian / Jabatan (Govt agencies and ministries)
Officers from sister agencies (PTG state, JPPH, JPS, JKR, KPKT) needing inter-agency reference: circulars, akta, statutes, contact directories, official forms. They navigate by document name, reference number, or year. Density and metadata matter more than imagery.

### Warga JKPTG (Internal staff)
Department officers logging in to internal systems (MyLAND v2.0, e-Tanah, SIDREA, JPN, etc). Need a consistent launchpad to authenticated services and internal announcements. They want the launchpad out of their way once they identify themselves.

## Brand

| Attribute | Value |
|-----------|-------|
| Mood | Formal, dignified, modern, accessible, trustworthy |
| Tone | Professional, citizen-first, plain bilingual prose |
| Voice | Direct, calm, no marketing hype, no exclamation marks |

## Strategic Principles

1. **Citizen scan time is the budget.** Three seconds to find what they came for. If they don't see it on first scan, the design failed.
2. **Bilingual parity is non-negotiable.** Every visible string lives in BM and EN with full content parity. PPPA mandate.
3. **Density without clutter.** Govt portals fail by either dumping links or hiding everything in megamenus. Show what matters; subordinate what doesn't.
4. **Trust through restraint.** No marketing copy, no hype banners, no rotating carousels of self-congratulation. Information first, decoration never.
5. **Compliance is hygiene.** PPPA / RAKKSSA / ISO 27001 / WCAG AA / Bahasa Kebangsaan first / JATA correctness — these are baseline, not features. They never appear in user-facing copy as bragging points.
6. **No happy talk.** "Selamat datang ke laman web rasmi kami" and similar self-introductions die. The header already says where you are.

## Anti-references

These are explicitly NOT what JKPTG should look or feel like.

- The **legacy jkptg.gov.my** portal — loud "JOM LIKE FOLLOW SHARE" social-media banners, purple gradients, cluttered megamenus, animated carousels.
- **State-portal template clones** — uniform 6-tile persona/service grid, glassmorphic cards, gradient buttons, drop-shadow stacks, photographic stock-image heroes with overlay text.
- **Marketing-SaaS dashboards** — hero metric template (big number + small label + supporting stat row + gradient accent).
- **Citizen-app-pretending-to-be-fun** — playful illustrations, mascots, emoji in nav, rounded oversized pill buttons.
- **Decorative-first govt portals** — JATA Negara enlarged as hero ornament, looping background animations, parallax scroll.

## Reference peers (formal, modern, restrained)

- **JPA (Jabatan Perkhidmatan Awam)** — formality template for federal Malaysian govt.
- **MyGovernment portal** — citizen UX pattern reference.
- **digital.gov.my** — Malaysian govt modernity bar.
- **GOV.UK** — copy and density discipline.
- **Singapore Gov / SG.gov / Smart Nation** — bilingual handling, structural restraint.
- **Stripe / Linear** — typographic precision and component density (visual register only, not domain).

## Visual Direction (locked 2026-05-06, from /design-shotgun)

**Direction: Modern Gov-Tech.** Stripe / Linear precision applied to a federal land authority. The portal feels like a calm, data-forward product — not a brochure.

### Type
- **Inter** — UI sans, all body and nav.
- **JetBrains Mono** — metadata, reference numbers, timestamps, status pill labels, case codes.
- Hierarchy via scale + weight, not color or decoration.
- Numerals: tabular, lining figures.

### Color strategy: Restrained
- Surface: slate-50 / white (#F8FAFC, #FFFFFF).
- Text: slate-900 (#0F172A) primary, slate-600 (#475569) secondary, slate-400 (#94A3B8) meta.
- Borders: slate-200 / slate-300 hairlines, 1px sharp, no shadow stacking.
- Accent: navy `#243D57` reserved for primary CTA, focus ring, active nav, JATA monogram strip.
- JATA red / yellow used only on the JATA Negara monogram itself, never as decorative chrome.
- One accent ≤10% of surface — Restrained law.

### Patterns
- **Status pills** with mono labels: `OPEN` / `CLOSED` / `DRAFT` / `BARU` / `KEMASKINI`.
- **Mono reference codes** beside titles: `[T-2026/14]`, `[B-08]`, `[FAQ-12]`.
- **Dense data tiles** for services — 1px slate border, no shadow, no gradient, hover = border-color shift only.
- **Command-K search** — keyboard hint visible in header search field.
- **Segmented language toggle** — BM | EN, no flag icons.
- **Live-status tender / news cards** — date as mono `2026-05-04`, status pill on right.
- **Persona entry as stat-card buttons** — mono code `P-01 / P-02 / P-03`, label, one-line helper, no illustration.
- **Tabular footer directory** — three or four columns, mono section headers, no social-media-icon row.

### Bans (project-specific, on top of impeccable-flat shared bans)
- No drop shadows on cards.
- No gradient backgrounds, gradient buttons, gradient text.
- No photographic hero overlay.
- No rounded-2xl cards. Radius is 4–6px max, deliberate.
- No decorative dividers (`<hr>` with stars, double rules, etc).
- No emoji as iconography. Phosphor / Lucide line icons, slate-600.
- No marquee, ticker, carousel, parallax.
- No "Selamat datang" hero subtitle.
- No animated counters / stat odometers.

## Density and rhythm

- Body text: 16–17px, 1.55 line-height, 65–75ch line length.
- Section spacing: vary deliberately — hero 96px, list 48px, dense data 24px. No uniform 64px everywhere.
- Tile padding: 20–24px. Card-as-button hit area ≥ 64px tall.
- Mobile: same density discipline; nav collapses to slide-in panel, persona blocks stack, service grid 2-up at <768px.

## Motion

- Ease: `cubic-bezier(0.22, 1, 0.36, 1)` (ease-out-quart).
- Duration: 150ms hover state, 220ms panel slide, 320ms language toggle. No bounce.
- Animate transform / opacity only. Never animate width / height / top / left.
- Reduce motion respected (`prefers-reduced-motion`).

## Compliance hooks (visible UI implications)

- **PPPA Bil 1/2025** — visible on every page: copyright, last-updated date, JATA monogram in footer, accessibility statement link, BM/EN toggle, sitemap link, contact link, search.
- **WCAG 2.1 AA** — visible focus ring (navy #243D57, 2px outline + 2px offset), keyboard reachable nav, skip-to-content link.
- **Bahasa Kebangsaan first** — BM is the default; EN toggle never collapses BM out of view.
- **JATA Negara correctness** — colors locked, no resize below 32px, never combined with non-govt logos as a row.

## Out of scope for visual upgrade

- Filament admin panel — separate register (admin tool), not touched by this pass.
- Chatbot bubble — already styled in Phase 9; restyle as a follow-up only if it visibly clashes after page redesign.

## Success criteria

A visitor arriving at `/` from search:
1. Knows what JKPTG is in <2 seconds (header wordmark + role).
2. Sees their persona path within the fold (P-01 / P-02 / P-03 stat cards).
3. Reaches Perkhidmatan or Hubungi in one click.
4. Sees current tender status without scrolling past hero.
5. Trusts the page enough to log in, file a complaint, or submit a tender bid.
