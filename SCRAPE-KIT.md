# Scrape Kit — Comprehensive Portal Capture

**Goal:** Mirror existing govt portal end-to-end. Tender evaluators see "they already understand our entire site." Maximize perceived thoroughness.

**Lesson from JPPH:** scrape was thin (1 HTML + sitemap + announcements). This time — capture everything.

---

## Output structure

```
reference/scrape/
  00-meta/
    crawl-config.json        # base url, depth, exclusions
    crawl-log.txt            # urls visited, errors, timing
    site-summary.md          # totals: N pages, M pdfs, K images
  01-sitemap/
    sitemap.json             # full URL tree from crawl
    sitemap-official.xml     # /sitemap.xml if exists
    menu-tree.json           # megamenu IA extracted from DOM
    breadcrumbs.json         # breadcrumb chains per page
  02-html/
    {url-slug}.html          # raw HTML, every page
    _index.csv               # url,title,h1,meta_desc,word_count,lang
  03-screenshots/
    desktop/{url-slug}.png   # 1440px viewport
    mobile/{url-slug}.png    # 375px viewport
    full-page/{url-slug}.png # full scroll capture
  04-pdfs/
    {filename}.pdf           # every linked PDF downloaded
    _index.csv               # filename,source_page,size,pages,title
    _extracted/{name}.txt    # text extracted via pdftotext
  05-assets/
    images/                  # all <img> + CSS bg images
    fonts/
    icons/
    _manifest.json           # url,bytes,type,referenced_from
  06-forms/
    {form-id}.json           # fields, validation, action url, method
    _summary.md              # all forms found, their purpose
  07-network/
    api-endpoints.json       # XHR/fetch URLs captured during crawl
    har/{url-slug}.har       # HAR per page (selective)
  08-content-inventory/
    pages.csv                # url,title,h1,h2_list,word_count,last_mod
    services.csv             # service-pages with their forms+pdfs
    directory.csv            # staff/contact pages structured
    news.csv                 # announcements, dates, titles
  09-multilingual/
    coverage.csv             # url_bm,url_en,both_present,word_count_diff
  10-audits/
    lighthouse/{url-slug}.json   # perf + a11y + seo + best-practices
    axe/{url-slug}.json          # accessibility violations
    pa11y-summary.csv            # rolled-up a11y issues
  11-gap-analysis/
    pppa-violations.md       # current portal vs PPPA-Bil-1-2025 rules
    ui-ux-gaps.md            # design-debt observations
    content-gaps.md          # missing per LAMPIRAN T checklist
    performance-baseline.md  # Web Vitals per page, slowest pages
```

---

## Run order

### Phase A — Discovery
1. Find official sitemap at `https://www.jkptg.gov.my/sitemap.xml`. If absent, BFS crawl from homepage depth 4.
2. Extract megamenu tree from homepage DOM → `menu-tree.json`.
3. Detect language switcher (BM/EN). Note URL pattern (`?lang=en`, `/en/`, etc.).

### Phase B — Mirror
4. Wget/httrack full site to `02-html/`. Respect robots.txt? **NO** for tender prep — capture everything (still don't hammer server, throttle to 1 req/sec).
5. Download every linked PDF → `04-pdfs/`. Run `pdftotext` on each.
6. Download all `<img>` src + CSS `background-image` → `05-assets/images/`.

### Phase C — Snapshots
7. Playwright: navigate every URL, screenshot desktop (1440x900) + mobile (375x812) + full-page scroll.
8. Capture HAR file for top 20 pages (homepage, service hubs, login, search).

### Phase D — Structure
9. Parse every HTML: title, h1, h2 list, meta desc, lang attr, word count → `pages.csv`.
10. Parse every `<form>`: action, method, fields with name+type+required → `06-forms/`.
11. Capture network requests during Playwright crawl → `api-endpoints.json` (dedup by host+path).

### Phase E — Audits (the tender wow)
12. Lighthouse CLI on every URL: perf + a11y + seo + best-practices → `10-audits/lighthouse/`.
13. axe-core via Playwright on every URL → `10-audits/axe/`.
14. pa11y rolled report.

### Phase F — Gap analysis (the tender pitch)
15. **PPPA violations:** read `reference/PPPA-extracted.txt`, check current portal against each mandatory rule (footer requirements, official mark, accessibility, language, etc.). Write `11-gap-analysis/pppa-violations.md`.
16. **UI/UX gaps:** stale design patterns, broken hierarchy, slow interactions, mobile failures.
17. **Content gaps:** cross-ref `LAMPIRAN T` checklist — what's missing or broken.
18. **Performance baseline:** worst LCP/INP/CLS pages, biggest assets.

---

## Tools

| Tool | Use | Install |
|------|-----|---------|
| `httrack` or `wget --mirror` | Static HTML mirror + asset download | choco install httrack |
| **Playwright MCP** | Screenshots, dynamic pages, forms | already installed |
| `pdftotext` (poppler) | PDF → text extraction | choco install poppler |
| Lighthouse CLI | Per-page audits | npm i -g lighthouse |
| axe-core | A11y via Playwright | npm i -D @axe-core/playwright |
| pa11y | A11y batch | npm i -g pa11y-ci |
| `csvkit` | CSV manipulation | pip install csvkit |

---

## Tender presentation angle

Bundle scrape output into:

1. **`SITE-AUDIT.pdf`** — exec summary (1 page) + key gaps + screenshots
   - "We crawled X pages, Y PDFs, Z assets"
   - "Found N PPPA violations, M a11y issues, P performance regressions"
   - "Current Lighthouse scores: perf 42 / a11y 67 / seo 88"
   - Side-by-side: current vs proposed (mockup)
2. **`CONTENT-INVENTORY.xlsx`** — every page categorized + linked to LAMPIRAN T
3. **`MIGRATION-PLAN.md`** — how every existing URL maps to new IA (no orphan content)
4. **`RISK-REGISTER.md`** — what breaks during migration + mitigation

Evaluators see this = "they did homework, low risk pick."

---

## Run command (when scaffolded)

```powershell
# from Portal-JKPTG/ root
node scripts/scrape-runner.mjs --target "https://www.jkptg.gov.my" --out "reference/scrape"
```

Build `scripts/scrape-runner.mjs` once per portal (or generic in template).

---

## Anti-patterns (don't repeat JPPH)

- ❌ Scrape only homepage HTML — miss everything
- ❌ Skip PDFs — they ARE the govt portal content
- ❌ No screenshots — can't reference visually in pitch
- ❌ No gap analysis — wasted scrape, no tender win
- ❌ Forget multilingual — BM + EN both required
- ❌ Skip a11y/perf audits — these are PPPA mandatory checks
