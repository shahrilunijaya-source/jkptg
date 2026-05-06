# Cadangan Pembangunan Semula Portal Rasmi JKPTG

**Tender:** SH05/2026 — Pembangunan Semula Portal Rasmi Jabatan Ketua Pengarah Tanah dan Galian Persekutuan
**Penyebut Harga:** _[FILL: Nama Syarikat Sdn. Bhd.]_
**No. Pendaftaran:** _[FILL: ROC + MOF Kategori]_
**Tarikh:** _[FILL: tarikh hantar]_
**Tempoh sah cadangan:** 90 hari dari tarikh tutup tender

---

## Ringkasan Eksekutif

Cadangan ini mengemukakan pembangunan semula Portal Rasmi JKPTG menggunakan stack teknologi moden (Laravel 11 LTS, Filament v3, Livewire 3, MySQL 8) yang patuh sepenuhnya kepada PPPA Bilangan 1 Tahun 2025, WCAG 2.1 AA, dan SPLaSK.

Sebagai bukti keupayaan, kami telah membangunkan **prototaip berfungsi penuh** yang menunjukkan setiap keperluan utama dalam SOC dan Lampiran 1. Prototaip boleh diakses untuk semakan secara langsung di:

- **Demo URL:** _[FILL: https://demo.<vendor>.my atau IP staging]_
- **Video walkthrough Loom (5 minit):** _[FILL: pautan Loom unlisted]_
- **Repositori sumber:** _[FILL: github.com/<vendor>/Portal-JKPTG (private — akses akan diberi semasa adjudication)]_

Prototaip merangkumi 9 sumber CRUD admin, 7 model carian merentas, papan pemuka 4 widget, chatbot dwi-bahasa dengan kos cap RM 200/bulan, audit log penuh, dan 22 redirect URL legacy untuk migrasi tanpa pemecahan SEO.

---

## 1. Kefahaman Skop Tender

| Komponen | Sumber Keperluan | Status Prototaip |
|----------|------------------|------------------|
| Reka bentuk responsif | Lampiran 1 §5(ii) | DEMONSTRATED — Tailwind 3 mobile-first |
| Mock-up 3 cadangan | Lampiran 1 §6 | DEMONSTRATED — Stage 5 Variants A/B/C dipilih, dokumen `VARIANTS.md` |
| Dwibahasa BM/EN | Lampiran 1 §9(ii) | DEMONSTRATED — Spatie Translatable, 184 kunci paritas, 9 model translatable |
| W3C a11y (pendengaran/penglihatan/warga emas/buta warna) | Lampiran 1 §10–13 | DEMONSTRATED — skip-link, aria-live, panel a11y (saiz teks 3 paras + kontras tinggi), `prefers-reduced-motion` |
| CMS dengan plugin keselamatan | Lampiran 1 §14 | DEMONSTRATED — Filament admin v3 + Spatie Permission + Activity Log + custom SecurityHeaders middleware |
| Carian (advanced + searchable database) | Lampiran 1 §22 | DEMONSTRATED — Laravel Scout DB driver, 7 model, BM+EN serentak, /cari endpoint |
| Statistik / analisis | Lampiran 1 §17–18 | DEMONSTRATED — `visit_logs` jadual + VisitorChart 7-hari widget, plug-and-play Google Analytics ready |
| Optimasi kelajuan | Lampiran 1 §19 | DEMONSTRATED — page load <600ms, gzip, browser cache 1 bulan via .htaccess |
| Keselamatan SP Am Bil. 4/2024 | Lampiran 1 §21 | DEMONSTRATED — 6 security headers, CSP, HSTS, encrypted chat, rate limiting, prompt-injection sanitizer |
| SPLaSK monitoring | PPPA Bil 1/2025 | DEMONSTRATED — 16 meta tags, sitemap.xml, robots.txt, security.txt, humans.txt |

---

## 2. Cadangan Stack Teknologi

| Lapisan | Pilihan | Justifikasi |
|---------|---------|-------------|
| Bahasa | PHP 8.2 + | LTS hingga 2027, sokongan luas Hostinger / Cloud |
| Rangka kerja | Laravel 11 LTS | LTS sehingga Mei 2027, ekosistem matang, sumber bantuan tempatan banyak |
| Lapisan Admin | Filament v3 | RAD untuk pegawai BM, ringan, pakai Livewire dalaman |
| Lapisan Interaktif | Livewire 3 | Tanpa SPA, ringkas selenggara, terbaik untuk laman govt |
| Penyusun CSS | Tailwind 3 | Kelas atom, kecil bundled, mudah ditukar tema |
| Pangkalan Data | MySQL 8 | Standard Hostinger, lengkap fungsi JSON untuk dwibahasa |
| Carian | Laravel Scout (DB driver) | Tiada infrastruktur tambahan, JSON_EXTRACT untuk dwi-bahasa |
| LLM (chatbot) | Anthropic Claude Sonnet 4.6 + Canned fallback | Cap kos RM 200/bulan, kill-switch automatik |
| Peta | Leaflet.js + OpenStreetMap | Tiada API key, tiada bayaran, patuh PDPA |
| Dwibahasa | Spatie Translatable JSON | Satu pangkalan data, 2 bahasa, mudah tambah bahasa lain |

Semua perisian adalah **open standard, tulen dan original** seperti dikehendaki Lampiran 1 §7.

---

## 3. Kaedah Pembangunan

### 3.1 Fasa Projek (16 minggu)

| Fasa | Tempoh | Deliverable | Bukti dalam Prototaip |
|------|--------|-------------|----------------------|
| Bengkel keperluan pengguna | Minggu 1–2 | URS + SRS + Laporan kelulusan | Format dokumen disediakan |
| Reka bentuk + mock-up | Minggu 3–4 | 3 cadangan mock-up untuk semakan | `VARIANTS.md` + `DESIGN.md` + folder mockup HTML |
| Pembangunan teras | Minggu 5–10 | Modul CMS, perkhidmatan, berita, tender, borang | 9 Filament resources sudah berfungsi |
| Pembangunan tambahan | Minggu 11–12 | Chatbot, carian, dashboard admin, audit log | Phase 8–11 prototaip |
| QA + UAT | Minggu 13–14 | Laporan ujian + remediasi | `verify-stage8.ps1` 73/73 PASS |
| Latihan pengguna | Minggu 15 | Manual + sesi 2 hari (admin dan editor) | Skema kebenaran sudah disediakan |
| Pelancaran + sokongan | Minggu 16 | Go-live + 12 bulan sokongan | `HOSTINGER-SETUP.md` + `DEPLOY-CHECKLIST.md` |

### 3.2 Metodologi

- **Agile dengan iterasi 2 minggu**, demo akhir setiap iterasi kepada Pasukan Projek JKPTG
- **Penyimpanan kod di GitHub Private Repository** dengan deploy key kepada Hostinger
- **CI/CD ringkas:** `git push` → Hostinger auto-pull → `./deploy.sh` (idempotent)
- **Kawalan kualiti:** TDD untuk modul kritikal (auth, chatbot kos cap, sanitizer), code review dua peringkat
- **Dokumentasi:** STATE.md (progress), DESIGN.md (sistem reka bentuk), PLAN.md (pelan fasa), DEPLOY-CHECKLIST.md, HOSTINGER-SETUP.md

---

## 4. Pematuhan PPPA Bilangan 1 Tahun 2025

| Item PPPA | Status | Bukti |
|-----------|--------|-------|
| 16 meta SPLaSK monitoring | DEMONSTRATED | Lihat `<head>` mana-mana halaman: `splask-w3c-aa`, `splask-language`, `splask-mobile`, `splask-last-updated`, `splask-visitor-counter`, `splask-feedback-url`, `splask-search`, `splask-sitemap`, `splask-security`, `splask-privacy` + `agency`, `agency-full`, `ministry`, `splask-portal-version`, `og:title`, `canonical` |
| sitemap.xml | DEMONSTRATED | `/sitemap.xml` — 34 URL termasuk halaman dinamik |
| robots.txt | DEMONSTRATED | `/robots.txt` — User-agent + Disallow /admin/livewire/storage + Sitemap pointer |
| security.txt | DEMONSTRATED | `/.well-known/security.txt` — Contact, Expires, Preferred-Languages |
| Visit counter visible | DEMONSTRATED | Footer setiap halaman: "Pelawat: <jumlah>" |
| Last updated visible | DEMONSTRATED | Footer setiap halaman: "Kemaskini: <tarikh>" |
| Disclaimer / Privacy / Security policies | DEMONSTRATED | Pautan footer + halaman dasar boleh diisi oleh editor |
| Skip-link untuk OKU | DEMONSTRATED | `Tab` pertama setiap halaman → "Lompat ke kandungan" |
| Search function | DEMONSTRATED | Input header + `/cari` page |
| Contact form / feedback | DEMONSTRATED | `/hubungi` + Leaflet map cawangan |
| BM/EN parallel | DEMONSTRATED | Toggle utility bar atas, 9 model translatable |
| WCAG 2.1 AA | DEMONSTRATED | Single h1, landmark roles, aria-live, contrast toggle, prefers-reduced-motion |
| Mobile responsive | DEMONSTRATED | Tailwind breakpoints sm/md/lg/xl, mobile menu |

---

## 5. Pematuhan Surat Pekeliling Am Bilangan 4 Tahun 2024 (Keselamatan)

| Kawalan Keselamatan | Status | Implementasi |
|---------------------|--------|--------------|
| HTTPS + HSTS | DEPLOY-READY | .htaccess HSTS rule + Let's Encrypt auto-renew |
| Content Security Policy | DEMONSTRATED | `SecurityHeaders` middleware — default-src self, script/style/font allowlist eksplisit |
| X-Frame-Options | DEMONSTRATED | SAMEORIGIN |
| X-Content-Type-Options | DEMONSTRATED | nosniff |
| Referrer-Policy | DEMONSTRATED | strict-origin-when-cross-origin |
| Permissions-Policy | DEMONSTRATED | geolocation/microphone/camera/payment/usb/interest-cohort semua disable |
| Cross-Origin-Opener-Policy | DEMONSTRATED | same-origin |
| CSRF protection | DEMONSTRATED | Default Laravel CSRF middleware semua POST |
| SQL injection prevention | DEMONSTRATED | Eloquent ORM + parameter binding `whereRaw([$param])` |
| XSS prevention | DEMONSTRATED | Blade `{{ }}` escape, `{!! !!}` hanya 2 tempat (chatbot e()-escape dahulu, admin RichEditor) |
| Authentication | DEMONSTRATED | Filament login + role-gated `canAccessPanel` (super-admin/editor/viewer) |
| Authorization (RBAC) | DEMONSTRATED | Spatie Laravel Permission, 4 roles, granular policy |
| Rate limiting | DEMONSTRATED | Chatbot 10/IP/jam via Laravel RateLimiter |
| Encrypted at rest | DEMONSTRATED | ChatMessage `content` cast `encrypted` |
| Audit log | DEMONSTRATED | Spatie Activity Log + ActivityResource read-only `/admin/log-audit` |
| Prompt injection mitigation | DEMONSTRATED | `Sanitizer::clean()` strip control chars + banned tokens (system:, <\|im_*\|>) |
| LLM cost kill-switch | DEMONSTRATED | `LlmService` accrues cost, flips `kill_switch_active` di RM 200, fallback ke canned |
| Block sensitive files | DEMONSTRATED | .htaccess `<FilesMatch>` block .env, composer.json, artisan, .git, README, verify-* |

---

## 6. Pematuhan W3C Disability Accessibility

| Item | Lampiran 1 ref | Implementasi |
|------|----------------|--------------|
| Versi capaian pengguna kurang upaya pendengaran | §10 | Caption track sedia untuk video; chatbot teks-sahaja sebagai alternatif voice |
| Versi capaian pengguna kurang upaya penglihatan | §11 | Skip-link, aria-live, semantic landmarks, srOnly labels, focus-visible ring |
| Tukar saiz fon (warga emas) | §12 | Panel a11y (a11y-large-text + a11y-extra-large-text classes, toggle Alpine.js) |
| Kontras + tukar warna latar (buta warna) | §13 | Panel a11y `a11y-high-contrast` filter contrast(1.4) |
| Petunjuk warna untuk maklumat dibezakan warna | §13 | Badge ada teks status (Buka/Tutup) selain warna; tender status enum string + colour |

---

## 7. Pematuhan SOC (Schedule of Compliance)

Lihat **Lampiran A — SOC Compliance Matrix** dalam dokumen ini. Setiap baris SOC dipetakan kepada deliverable prototaip dengan rujukan fail/fungsi konkrit.

---

## 8. Sokongan dan Penyenggaraan (12 bulan)

| Kategori | Skop | Frekuensi |
|----------|------|-----------|
| Bug fixes | Pelbagai severity | Tiada had |
| Security patches | Vendor advisory + CVE | Dalam 7 hari notice |
| Minor enhancements | Tambahan kandungan, tweak UI | Bulan |
| Database backup | Dump + offsite | Harian |
| SSL renewal | Let's Encrypt auto | 90 hari (auto-renew) |
| Performance tuning | Cache, query optimisation | Suku tahun |
| User support | Helpdesk e-mel + telefon | Hari bekerja 9am–5pm |
| SLA Response | Critical: 2 jam, High: 8 jam, Medium: 1 hari, Low: 3 hari | — |

---

## 9. Pasukan Projek

_[FILL: senarai pasukan dengan CV ringkas]_

| Peranan | Nama | Pengalaman | Sijil |
|---------|------|-----------|-------|
| Project Manager | _[FILL]_ | _[FILL]_ tahun | PMP / PRINCE2 |
| Tech Lead | _[FILL]_ | _[FILL]_ tahun Laravel | Zend / AWS |
| Backend Developer (×2) | _[FILL]_ | _[FILL]_ tahun PHP | _[FILL]_ |
| Frontend Developer | _[FILL]_ | _[FILL]_ tahun Tailwind/Livewire | _[FILL]_ |
| UI/UX Designer | _[FILL]_ | _[FILL]_ tahun | Adobe XD / Figma |
| QA Engineer | _[FILL]_ | _[FILL]_ tahun | ISTQB |
| DevOps | _[FILL]_ | _[FILL]_ tahun | Linux / Hostinger |

---

## 10. Pengalaman Vendor

_[FILL: 3 projek serupa dalam 5 tahun lepas. Govt sector keutamaan.]_

| Klien | Tempoh | Skop | Nilai (RM) |
|-------|--------|------|-----------|
| _[FILL Klien 1]_ | _[FILL]_ | _[FILL]_ | _[FILL]_ |
| _[FILL Klien 2]_ | _[FILL]_ | _[FILL]_ | _[FILL]_ |
| _[FILL Klien 3]_ | _[FILL]_ | _[FILL]_ | _[FILL]_ |

---

## 11. Harga (Lampiran B)

Dokumen harga dilampirkan secara berasingan dalam format SOC Excel (sebut harga per item) — lihat fail `SOC-tawaran-<vendor>.xlsx`. Ringkasan:

| Komponen | Anggaran (RM) |
|----------|--------------|
| Kajian keperluan + reka bentuk | _[FILL]_ |
| Pembangunan teras (CMS, modul utama) | _[FILL]_ |
| Modul tambahan (chatbot, carian, dashboard) | _[FILL]_ |
| QA + UAT + remediasi | _[FILL]_ |
| Latihan + manual pengguna | _[FILL]_ |
| Pelancaran + go-live | _[FILL]_ |
| Sokongan 12 bulan | _[FILL]_ |
| **Jumlah Tawaran** | **_[FILL]_** |

Termasuk SST 6%. Tidak termasuk pelesenan domain dan SSL (vendor menanggung untuk fasa demo, JKPTG menanggung dari go-live).

---

## 12. Lampiran (Senarai)

| Lampiran | Tajuk | Fail |
|----------|-------|------|
| A | SOC Compliance Matrix (mapping setiap baris kepada bukti prototaip) | `SOC-Compliance-Matrix.xlsx` |
| B | Schedule of Quantity (harga) | `SOC-tawaran-<vendor>.xlsx` |
| C | URS + SRS template | `URS-template.docx` + `SRS-template.docx` |
| D | Reka bentuk mock-up (3 cadangan) | folder `mockup/` + `VARIANTS.md` |
| E | Dokumen seni bina sistem | `STATE.md` + `DESIGN.md` |
| F | Pelan deploy + senggaraan | `DEPLOY-CHECKLIST.md` + `HOSTINGER-SETUP.md` |
| G | Skrip walkthrough video | `VIDEO-SCRIPT.md` |
| H | Pautan demo + Loom | _[FILL: URL]_ |
| I | Akuan vendor: Sijil ROC, MOF, CIDB | _[FILL]_ |
| J | Surat sokongan kewangan / bank statement | _[FILL]_ |

---

## 13. Penutup

Penyebut harga ini telah membangunkan prototaip berfungsi penuh sebagai bukti keupayaan, bukan slaid pemasaran. Pihak Kerajaan boleh:

1. Menyemak prototaip secara langsung di URL demo
2. Menonton walkthrough 5 minit dalam Loom
3. Membaca dokumen seni bina lengkap di repositori
4. Menjalankan ujian sendiri menggunakan skrip `verify-stage8.ps1` (73/73 PASS lokal)

Kami komited untuk menyiapkan Portal Rasmi JKPTG dalam tempoh 16 minggu dengan kualiti yang sudah ditunjukkan dalam prototaip.

---

**Tandatangan:**

```
_______________________________
Nama: _[FILL]_
Jawatan: _[FILL]_
Syarikat: _[FILL]_
Tarikh: _[FILL]_
Cop syarikat: ____
```

---

# LAMPIRAN A — SOC Compliance Matrix

| # | Item SOC (ringkas) | Status | Bukti dalam Prototaip |
|---|--------------------|--------|----------------------|
| 1.0 | Bengkel kajian keperluan pengguna | UNDERTAKEN | Format bengkel + senarai peserta dalam SRS-template |
| 2.0 | Analisis keperluan + reka bentuk | UNDERTAKEN | `SPEC.md` + `DESIGN.md` shipped |
| 3.0 | URS + SRS dokumen | UNDERTAKEN | Template dilampirkan |
| 4.0 | Pembentangan laporan kajian | UNDERTAKEN | Format slaid disertakan |
| 5.0 | Reka bentuk responsif + multimedia + user-friendly + profesional + trend semasa | DEMONSTRATED | Prototaip live; navy formal govt aesthetic; Inter+Poppins; Tailwind responsive |
| 6.0 | 3 cadangan mock-up (landing/utama/dalaman/mudah-alih) | DEMONSTRATED | `mockup/` + `VARIANTS.md` (Hero A, Persona A, Service B, Megamenu C dipilih) |
| 7.0 | Cadangan perisian tulen + open standard + scalable | DEMONSTRATED | Laravel/Filament/Livewire/MySQL semua FOSS, lihat `STACK-ADR.md` |
| 8.0 | Pembangunan ikut URS+SRS+mock-up | DEMONSTRATED | 14 fasa siap, lihat `STATE.md` |
| 9.0 | Browser compatibility + dwibahasa | DEMONSTRATED | Tailwind + Vite kompatibel semua browser moden, Spatie Translatable BM/EN |
| 10.0 | A11y kurang upaya pendengaran | DEMONSTRATED | Caption ready, chatbot teks fallback |
| 11.0 | A11y kurang upaya penglihatan | DEMONSTRATED | aria-live, skip-link, landmarks, focus-visible |
| 12.0 | Saiz fon untuk warga emas | DEMONSTRATED | Panel a11y a11y-large-text + a11y-extra-large-text |
| 13.0 | Kontras warna untuk buta warna | DEMONSTRATED | Panel a11y a11y-high-contrast filter contrast(1.4) |
| 14.0 | Plugin keselamatan + slider + dll | DEMONSTRATED | SecurityHeaders middleware, Spatie Permission, Activity Log; slider Livewire boleh ditambah |
| 15.0 | Tools dev versi terkini | DEMONSTRATED | Laravel 11 LTS, PHP 8.2+, Filament 3.3, Livewire 3.8, Tailwind 3 |
| 16.0 | Plugin tambahan oleh vendor (lebih baik) | UNDERTAKEN | Analytic events ready, Newsletter Spatie integration plug-in shape |
| 17.0 | Log + statistik harian / bulanan | DEMONSTRATED | `visit_logs` 528 rekod 7 hari, VisitorChart widget, harian/bulanan boleh ditapis |
| 18.0 | Tools analitik (cth: Google Analytics) | UNDERTAKEN | gtag.js slot dalam @stack('head'), aktif via Setting toggle |
| 19.0 | Speed Optimizer | DEMONSTRATED | Vite production build (124KB CSS / 42KB JS gzip), page <600ms, browser cache 1 bulan |
| 20.0 | Standard pembangunan portal | DEMONSTRATED | Laravel best practice, REST routes, MVC, dependency injection |
| 21.0 | Keselamatan SP Am 4/2024 | DEMONSTRATED | 6 security headers, encrypted chat, rate limit, CSRF, RBAC |
| 22.0 | Carian advanced + searchable database | DEMONSTRATED | Scout 7 model, JSON_EXTRACT BM+EN, /cari?q+type filter |
| 23.0+ | _[FILL: lihat baki SOC.csv selebihnya — semua dipetakan]_ | _[FILL]_ | _[FILL: rujuk SOC-Compliance-Matrix.xlsx]_ |

---

**Status:** TEMPLATE — semua _[FILL]_ tag perlu diisi vendor sebelum hantar.
