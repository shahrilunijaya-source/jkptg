$path = 'c:\Users\User\Desktop\Claude\ClaudeCode\Website\Portal-JKPTG\VARIANTS.md'

$content = @'
# Portal-JKPTG - Stage 5 Visual Variants (LOCKED)

**Locked:** 2026-05-06
**Method:** /design-shotgun, 3 variants per page (LESSONS rule 1), pick winner
**Next stage:** Stage 6 HTML mockup builds these 4 winners

---

## Page 1 - Homepage hero

**Winner: A - Overlay (full-bleed photo + persona doors floating)**

Pattern:
- Full-bleed Putrajaya skyline / land-admin photo above-fold
- Mission statement overlay text top-half ("Mengurus tanah Persekutuan dengan amanah")
- 3 persona doors as translucent cards, anchored bottom-half
- 6 service tiles strip below hero (separate band)

Risks to mitigate at HTML mockup:
- Text-on-photo contrast: WCAG AA requires 4.5:1. Use dark gradient overlay on photo (rgba(0,0,0,0.45)) or solid scrim band behind text.
- Mobile: photo may dominate, push doors below fold. At <768px, switch to Variant C sequential layout (photo banner + doors row below).
- Photo source: must be JKPTG/Putrajaya specific, not Unsplash placeholder. Sourcing in Phase 6.

References:
- DESIGN.md A5.1 Hero
- SPEC A4 Hybrid hero
- malaysia.gov.my hero pattern (72px h1, bold)

---

## Page 2 - Persona landing template (Orang Awam exemplar)

**Winner: A - Classic (hero + service grid + news + sidebar)**

Pattern:
- Breadcrumb top
- Hero strip: "Selamat datang. Pilih perkhidmatan anda." + search input
- Main column: 3-col service grid (6 popular services with icon + label + 1-line desc)
- Main column lower: Berita & Pengumuman list (3 items + see-all link)
- Right sidebar (sticky on desktop): Pautan Pantas (Borang, FAQ, Hubungi, Status permohonan)

Component reuse: same template for Kementerian/Jabatan + Warga JKPTG personas. Only top hero copy + service set differ.

Risks:
- Sidebar collapses below main content on mobile (< 1024px)
- Service grid: 6 cards on desktop, 2-col on tablet, 1-col on mobile

References:
- DESIGN.md A5.2 Persona landings (Phase 5.2.1 component)
- digital.gov.my Quick Links pattern

---

## Page 3 - Service detail (Pengambilan Tanah exemplar)

**Winner: B - Sticky left nav + sticky apply CTA**

Pattern:
- Breadcrumb top
- H1 service name
- Two-column body:
  - Left rail (sticky on desktop, top-aligned with header offset): anchor nav listing sections (Tentang / Kelayakan / Proses / Dokumen / FAQ / Borang)
  - Right column: long-form content with section anchors
- Persistent floating "Mohon Sekarang ->" button bottom-right (sticky)

Production behavior:
- Anchor nav highlights current section on scroll (Intersection Observer in Alpine)
- Mobile (<1024px): left rail collapses to dropdown "Pada halaman ini" at top of content
- Apply button: solid navy primary CTA, always visible. On mobile becomes full-width sticky bar.

Risks:
- Left rail width consumes content area: cap at 240px, content gets remaining
- Sticky offset must account for header height + accessibility panel

References:
- DESIGN.md A5.3 Service pages
- JPA service detail pattern (sticky left nav)

---

## Page 4 - Megamenu open state (PERKHIDMATAN exemplar)

**Winner: C - Hero-style with featured cards**

Pattern:
- Megamenu drops down full-width on click (DESIGN.md locked: click + keyboard only, no hover)
- Top region: section title "PERKHIDMATAN JKPTG" + 1-line tagline
- Featured strip: 3 cards with image thumbnails (Pengambilan, Pusaka, Pajakan) - the most-trafficked services
- Categories row below: Tanah | Pajakan | Lesen | Strata - inline text links
- Footer link: "Lihat semua perkhidmatan ->"

Why C beats A and B:
- A (4-col grid) too dense for occasional citizen visitor; resembles old portal pain point
- B (2-col expander) requires hover-style interaction which contradicts click-only lock
- C reads like a content-marketing landing page, lowers cognitive load, encourages "I want X" intent

Risks:
- Image thumbnails for featured cards: need 3 service photos in Phase 6. Stub with icon-only if photo unavailable.
- Mobile: megamenu collapses to off-canvas drawer (vertical accordion of categories) - distinct mobile pattern.
- Click-outside or ESC to close. Trap focus while open.

References:
- DESIGN.md A4 Header & megamenu (locked click + keyboard)
- DESIGN.md A12 Anti-pattern: hover-only menus
- malaysia.gov.my service category hero pattern

---

## Component impact

| Component | Variant change | Action |
|-----------|----------------|--------|
| `Hero` (Phase 5.1) | Overlay variant locked | Build with dark scrim layer + responsive fallback |
| `PersonaLanding` (Phase 5.2.1) | Classic 3-col-grid + sidebar | Build with sticky right sidebar at desktop |
| `ServicePage` (Phase 6) | Sticky left nav + sticky CTA | Add Alpine intersection observer for nav highlight |
| `Megamenu` (Phase 4) | Hero-style with featured cards | Featured-card slot system (3 cards, image + label) |

---

## Next stage

**Stage 6 - HTML Mockup** (`/design-html`)

Build static HTML/CSS mockup of the 4 winning patterns + locked DESIGN.md tokens. Approve mockup before Phase 7 Laravel Blade integration.

Pages to mockup in priority order:
1. Homepage (hero + service tiles + news + footer)
2. Persona landing - Orang Awam
3. Service detail - Pengambilan Tanah
4. Megamenu open state (interactive demo via static HTML + small JS)
'@

Set-Content -Path $path -Value $content -NoNewline -Encoding UTF8
Write-Host ('OK - VARIANTS.md written, ' + $content.Length + ' chars')
