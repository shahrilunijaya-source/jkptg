# Portal-JKPTG — Constraints

**Tender ref:** JKPTG SH05/2026
**Source:** PPPA Bil 1/2025 + Lampiran 1 (Skop Kerja) + SOC compliance matrix
**Compiled:** 2026-05-05

---

## 1. Project Metadata

| Field | Value |
|-------|-------|
| Agency | Jabatan Ketua Pengarah Tanah dan Galian Persekutuan (JKPTG) |
| Ministry | Kementerian Sumber Asli dan Kelestarian Alam (NRES) |
| Target portal | https://www.jkptg.gov.my |
| Delivery address | Bahagian Digitalisasi dan Pentadbiran Tanah, Aras 10, Menara PETRA, No. 25, Persiaran Perdana, Presint 4, 62574 Putrajaya |
| Total contract | 17 months = 5 mo build + 12 mo warranty |
| Currency | RM (fixed, no escalation) |
| Helpdesk | 24x7 (telefon + email) |

---

## 2. Stack Mandates (SOC Item 4)

| Layer | Mandate |
|-------|---------|
| Backend lang | **PHP** (only) |
| CMS | **Open-source CMS** (vendor proposes) |
| Database | **MySQL OR MariaDB** |
| Web server | Open-standard, scalable |
| OS | Linux |
| SSL | required (CA cert on go-live) |
| Browser support | Edge, IE, Chrome, Firefox, Safari, Opera |
| Versions | "latest stable, officially supported" |

### Conflict with `STACK-ADR.md`

`STACK-ADR.md` locks **Laravel + Filament + Livewire+Blade**. SOC Item 4 §2 mandates "PHP + Content Management System (CMS) (open source) + MySQL/MariaDB". Laravel is a PHP framework, not a CMS — risk that evaluators interpret SOC as requiring WordPress / Joomla / Drupal.

**Action:** flag in `/plan-eng-review` Stage 4. Proposed framing: position Laravel + Filament as "open-source PHP CMS" since Filament admin = full CRUD CMS over Laravel ORM. Document this interpretation in tender response narrative.

### Existing portal stack (current jkptg.gov.my, scrape recon)

Joomla 3.x + Gantry5 + RT Antares + dj-megamenu Pro + Smart Slider 3 + Falang i18n + jQuery 1.x + MooTools. Migration target = our new stack.

---

## 3. Compliance Frameworks (SOC §2)

Portal must comply with:
- **PPPA Bil 1/2025** — Pengurusan Portal/Laman Web Agensi Sektor Awam (replaces PKPA 2/2015)
- **RAKKSSA** — Rangka Kerja Keselamatan Siber Sektor Awam
- **ISO/IEC 27001** — info security management
- **SPLaSK** (JDN) — Sistem Pemantauan Laman Web; tagging mandatory
- **OWASP** — web application security standards
- **PDPA** — personal data protection (Chatbot scope)
- **SPA Bil 4/2024** — Penilaian Tahap Keselamatan Rangkaian dan Sistem ICT Sektor Awam

Domain: must use `*.gov.my` (PPPA §3.1.1).

---

## 4. PPPA Design Rules (§3.2 — mandatory)

- Statement "Portal/Laman Web Rasmi" on homepage.
- **Jata Negara** (federal agency) on homepage — JKPTG = federal, so use Jata. Source: `reference/JATA-NEGARA.png` + `LOGO JKPTG PNG.png` + scraped `LOGO-Jata.png`.
- Service-priority IA — core services prominent.
- Uniform fonts and sizes across all pages.
- ≤3 colors total. White background recommended.
- Icons uniform, ≤1KB each.
- Pop-ups only when justified.
- **Search** on every page.
- **Four mandatory links** in top-right corner of homepage with uniform icons:
  1. Soalan Lazim (FAQ)
  2. Hubungi Kami
  3. Aduan dan Maklum Balas
  4. Peta Laman
- "Hubungi Kami" must include address + official phone only.
- Recommend referring to https://standard.digital.gov.my (GA-IDS Government Digital Service Standard).

---

## 5. PPPA Functional Rules (§3.3 — mandatory)

- **Bilingual:** Bahasa Melayu + English. (Vendor not responsible for translation per SOC.)
- FAQ section.
- **Chatbot strongly recommended (AI-enabled).** SOC Item 2 makes it MANDATORY for JKPTG.
- Support links (help, troubleshooting, SOP, manuals, video tutorials).
- Feedback form with auto-generated reference number.
- User-satisfaction survey (post-transaction trigger).
- Hot topics / tag cloud.
- Audience-segmented entry (warganegara, kerajaan, penyelidik, wartawan, pelajar).
- Announcements zone — show "Tiada Makluman Terkini" when empty.
- Email/RSS push for announcements, news, tenders, vacancies.
- **Advanced search** with prediction.
- Smart personalization (welcome by name, last-visit, email/SMS notifications by topic).
- **WCAG accessibility** (W3C):
  - color blindness — bg toggle, contrast, color labels
  - low vision — audio alts
  - hearing-impaired — text/graphic alts for audio
  - elderly — font size + face toggles
- Electronic archive for content >1 year (downloadable).
- External links — MyGovernment + Open Data Portal **mandatory**; sister agencies + related systems as needed.
- Sitemap / index.
- **Mobile responsive** with QR code to mobile version.
- Customer Charter, Disclaimer, Privacy & Security Policy, Copyright notice — all mandatory.
- Multi-tech / multi-device / multi-OS support.
- Identiti Digital Nasional (IDN) integration (where login/auth applies).

---

## 6. PPPA Content Rules (§3.4 — mandatory)

Required pages/sections:
- Korporat: Visi, Misi, Fungsi, Carta Organisasi, Perutusan Ketua Jabatan
- Mengenai Kami (intro, admin info, location, facilities)
- Hubungi Kami (phone, fax, address, email, webmaster) — emails as **static text, not mailto**
- Direktori kakitangan (by function)
- Maklumat Ketua Pegawai Digital (CDO) — profile + news + activity
- Maklumat Terkini: pengumuman, berita, aktiviti, keratan akhbar, tender/sebut harga, jawatan kosong (with **auto-expiry**)
- Downloadable: documents, video/audio, publications
- Maklumat perkhidmatan teras
- e-Permohonan / e-Pembayaran / e-Aduan (online services end-to-end)
- Channel info per service (counter, kiosk, online, SMS, mobile app) with icons
- Service performance metrics
- e-Penyertaan: e-Information, e-Consultation, e-Decision (kept ≥6 months)
- Open data sets (machine-readable)
- Media sosial (linked, with disclaimer)

Forbidden content:
- Third-party advertising (exception: contracted service partners — see PPPA §3.4.3)
- Sensitive topics (agama, politik, perkauman)
- Content unrelated to agency core/support services
- Statements harming government image

---

## 7. PPPA Security Rules (§4.6 — mandatory)

- HTTPS via **GPKI** server cert (preferred) or licensed local CA.
- Information classification — only official + open data on public portal. Restricted info → CGSO clearance.
- WAF (web application firewall).
- IPS / firewall on infra layer.
- Anti-malware / ransomware real-time detection.
- Patch management — OS, DB, CMS core, all plugins kept current.
- **Backup + Disaster Recovery Plan (DRP)** — develop, maintain, test periodically.
- Periodic security testing (vulnerability assessment).
- Audit logs + ICT forensics retention.
- Incident reporting → agency CSIRT / NACSA NC4.

---

## 8. SOC Functional Mandates (Items 1–6)

### Item 1 — Portal redev
- ≥3 mockup variants (landing, home, inner, mobile). LESSONS rule: cap at 3 — aligns.
- Plugins (or equivalents): Security, Photo Slider, Caching, SEO, **Auto Backup**, Image Optimization, Cleaning Tools, Visitor Analytics, Broken Link Checker, Social Media Integration, **Flipping Book**.
- Statistics — daily/monthly/annual; popular pages, visitor counts.
- Analytics tools — Google Analytics + Search Console Insights (vendor proposes).
- Speed targets:
  - Page render <5 seconds per click
  - Click depth ≤3 to reach any info
  - Redirect chain ≤5 per request
- SiteMap, Chatbot, Dashboard, Auto Backup, SEO, Image Opt, Cleaning Tools, **Audit Trail integratable with Centralized Log**, Intranet Directory API integration.
- Plugin policy: latest CMS-supported, actively maintained, no perf/security regressions, paid licenses absorbed by vendor.

### Item 2 — AI Chatbot (mandatory)
- NLP, BM + EN.
- Quick reply buttons, chat bubble, mobile-friendly.
- Quota: **100,000 chat sessions + 1,000,000 tokens** per month × 12 months.
- Notify admin before quota exhaustion.
- **Fallback to normal (rule-based) chatbot** when AI quota depleted.
- FAQ-driven answers (admin-editable, no code).
- Sources: Portal Rasmi JKPTG + Portal MyLAND + Portal Intranet.
- Encryption + HTTPS + access control + PDPA.
- Analytics: question stats, success rate, trends.
- GUI flow builder with conditional flows per use case.

### Item 3 — Data migration
- Migration plan → govt approval before execution.
- Zero unplanned downtime on existing portal during cutover.
- Migration validation tests + report.

### Item 4 — Server / infra
- 3 environments: **Pusat Data JKPTG (Staging) + PDSA Teras + PDSA Perisai** (production + DRC).
- Web server + DB + OS install + config, periodic sync between staging/prod/DRC.
- SSL cert install.

### Item 5 — Testing & Pentauliahan
- UAT, FAT (1 month post go-live), Stress Test (third party).
- Test scripts handover.
- Hardening per SPA (Security Posture Assessment) result.
- Test reports + acceptance sign-off.

### Item 6 — Training + ToT
- Tech admin: 5 pax × 2 days
- System admin: 5 pax × 1 day
- User (kandungan): 30 pax × 2 days
- Location: JKPTG. Vendor pays food/material.
- Source code handover (final, working).

---

## 9. SOC SLA + Penalties (Item 7)

| Severity | Best | Max | Response |
|----------|------|-----|----------|
| L1 Highly Critical (portal/server down for all) | 1–4 hr | 1 day | ≤30 min |
| L2 Critical (down for some users / direct user complaint) | 1 day | 3 days | ≤30 min |
| L3 Low (impact, workaround possible) | 1–7 days | 14 days | — |

- Late-delivery penalty: max 10% of contract value.
- Resolution overrun: **RM100/day** post-deadline.
- Penalty formula: `Jumlah Penalti = V(T+D) / T × 5%` where V=value, T=agreed period, D=days late.
- Late preventive maintenance (PM) or corrective maintenance (CM) past 24h triggers penalty.
- Govt may engage third party at vendor cost on continued failure.

---

## 10. Team Requirements (SOC §4.4)

- Project Manager — ≥3 years experience.
- ≥3 developers, trained in portal development.
- Skills: Linux + open-source backend ops, CMS engine dev, web UI design.
- Certifications acceptable: Project Management (e.g. PMP), PHP/System Analyst, DBA, system development.
- CV + cert proof in Lampiran 3.

---

## 11. Deliverables (SOC §4.6)

| Phase | Document |
|-------|----------|
| Requirements | URS, SDS, SRS |
| Testing | UAT, PAT, FAT reports |
| Data | Data Management + Migration reports |
| Infra | Server Management report |
| Training | Technical + User training reports |
| Manuals | Admin, User, Technical |
| Source | All source code, working condition |

---

## 12. Project Phases (Lampiran 1 §3.xi)

1. User-Requirement Study + Design
2. Build (functional)
3. AI Chatbot build
4. Data migration
5. Server management (install + configure)
6. Testing (UAT + PAT + FAT + Chatbot data feed + stress)
7. Training
8. Go Live + Pentauliahan
9. Warranty + Tech Support (12 mo)

---

## 13. Maintenance (Lampiran 1 §6)

- Preventive Maintenance (PM): ≥2× during 12-mo warranty
  - System health check (vulnerability scan)
  - DB health check (storage/memory)
  - Data + log housekeeping
  - Patch + release delivery (no extra cost)
  - Config backup + restore drills
- Corrective Maintenance (CM): per SLA, fix + stabilize, hardware/infra rework if needed.
- Auto-backup throughout warranty.
- Vulnerability remediation per discovery.

---

## 14. Decisions Locked Here

1. Stack: per `STACK-ADR.md` — Laravel + Filament + Livewire+Blade + MySQL. Frame as "open-source PHP CMS" in tender response. Validate in `/plan-eng-review` Stage 4.
2. AI Chatbot: external service (e.g. OpenAI / Anthropic / Azure OpenAI) brokered via Laravel — keep cost predictable to fit 100k chats / 1M tokens / month quota. Fallback rule-based chatbot built in Laravel.
3. Search: PPPA mandates advanced search with prediction. Use Meilisearch / Typesense, not raw MySQL LIKE.
4. SPLaSK tagging: integrate during build, not retrofit.
5. WCAG: target WCAG 2.1 AA minimum.
6. Three environments per Item 4: staging at JKPTG DC, production at PDSA Teras, DRC at PDSA Perisai. Sync schedule TBD with infra team.

---

## 15. Open Questions for Stage 1 Brainstorm

- Confirm whether SOC §4 §2 ("CMS open source") is interpreted strictly. If yes → fall back to WordPress/Drupal. If flexibly → Laravel+Filament wins. Push to procurement Q&A if available.
- AI chatbot vendor selection (cost projection over 12 mo at quota cap).
- Intranet Directory API — does intranet expose REST? What auth?
- Centralized Log system at JKPTG — what protocol (syslog / Splunk / Elastic)?
- SPA testing — internal JKPTG team conducts; what window?
- Stress test vendor — chosen by govt; coordination handoff timing.
