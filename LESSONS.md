# Lessons from Portal-JPPH

Pain points → rules. Apply on every new portal.

## 1. Design variant explosion

**JPPH burned:** 30+ design iterations across homepage (v1/v2/v3), hero (v1/v2/v3 + waves + edsl + animated + webgl), login (v1/v2), megamenu (v1/v2), profil (v1/v2), visi-misi (v1/v2/desktop), direktori (v1/v2).

**Rule:** **Max 3 variants per page in Stage 5.** After v3 = pick + ship. No v4.

**Why:** Diminishing returns. v4+ is taste loop, not progress. Stakeholder eye fatigue.

## 2. Late-stage IA redesigns

**JPPH burned:** Megamenu structure changed in Stage 6 (mockup phase) after Stage 5 variants picked. Forced re-shotgun.

**Rule:** **Lock IA in Stage 1 SPEC.** Homepage sections, megamenu tree, login flow = decided before any visual work. SPEC.md is contract.

**Why:** IA changes invalidate all downstream design work. Cheap to change in spec, expensive in HTML.

## 3. Filament auth scaffolded too late

**JPPH burned:** Recent commits show `FilamentUser interface` + dashboard parse errors fixed AFTER pages built. Caused production access bugs.

**Rule:** **New Stage 6.5 — Auth + Admin scaffold BEFORE Stage 7 build.** Filament installed, `FilamentUser` interface implemented on User model, admin login verified working before any feature code.

**Why:** Filament has hidden lifecycle (panel providers, gates, FilamentUser). Fighting it post-build = parse errors, redirect loops, gate failures.

## 4. Dashboard placeholder phase

**JPPH burned:** Built dashboard with fake data first, wired real data later. Recent commit: "Wire dashboard to real data".

**Rule:** **Wire real data first iteration.** Even if minimal — 2 widgets with real queries beat 10 widgets with placeholders.

**Why:** Placeholder phase = throwaway code + double work + masks query problems until late.

## 5. Logo source

**JPPH burned (initial):** Tried screenshot of logo from website.

**Rule:** **Source SVG/PNG from official site assets URL.** Inspect page, find `/assets/logo.svg` or equivalent. Screenshot = lossy + retina issues.

## 6. Reference doc reading order

**JPPH worked:** Reading PPPA → LAMPIRAN A → LAMPIRAN T → SOC in order.

**Rule:** **Keep this order on every portal.** PPPA sets rules, A sets scope, T sets features, SOC = compliance checklist (last, most detail).

## 7. STATE.md hygiene

**JPPH worked when followed:** Updating STATE.md after each stage = clean session resume.

**Rule:** **Update STATE.md at end of every stage.** Otherwise next session re-explores.

## 8. Thin scrape of current portal

**JPPH burned:** Scraped only homepage HTML + sitemap + announcements. Missed PDFs, forms, screenshots, audits. No gap analysis = nothing to pitch evaluators.

**Rule:** **Stage 0.5 — comprehensive scrape** (see `SCRAPE-KIT.md`). Capture every page, PDF, image, form, screenshot, plus Lighthouse + a11y audits + PPPA gap analysis. Bundle into tender artifacts (SITE-AUDIT.pdf, CONTENT-INVENTORY.xlsx, MIGRATION-PLAN.md).

**Why:** Tender evaluators reward "they understand our portal end-to-end." Comprehensive scrape = perceived low risk = win bid.

## Carry-forward checklist for new portals

- [ ] Cap design variants at 3 per page
- [ ] Lock IA in SPEC before any visual work
- [ ] Filament + FilamentUser interface in Stage 6.5, before features
- [ ] No placeholder data — wire real from day 1
- [ ] Logo from official asset URL, not screenshot
- [ ] Read references in order: PPPA → A → T → SOC
- [ ] Run Stage 0.5 comprehensive scrape — every page + PDF + audits + gap analysis
- [ ] Update STATE.md after each stage
