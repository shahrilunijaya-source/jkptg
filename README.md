# Govt Portal Template

Reusable scaffold for Malaysian govt agency portals. Built from Portal-JPPH lessons.

## Use

```powershell
# 1. Clone for new agency
Copy-Item -Recurse "govt-portal-template" "Portal-{AGENCY}"

# 2. Find/replace tokens in new folder:
#    JKPTG    e.g. JKPTG
#    Jabatan Ketua Pengarah Tanah dan Galian Persekutuan    e.g. Jabatan Ketua Pengarah Tanah dan Galian
#    www.jkptg.gov.my  e.g. www.jkptg.gov.my
#    2026           e.g. 2026

# 3. Drop agency-specific tender docs into reference/:
#    - LAMPIRAN_A (project background)
#    - LAMPIRAN_T (tech specs)
#    - SOC xlsx (compliance matrix)
#
#    PPPA-Bil-1-2025.pdf already in template (govt-wide, agency-agnostic)
```

## What's locked (don't re-debate)

- **Stack:** Laravel + Filament `/admin` + Livewire+Blade public — see `STACK-ADR.md`
- **Workflow:** Stage 0→10 ladder — see `CLAUDE.md`
- **Iteration budgets:** see `LESSONS.md`
- **Filament gotchas:** see `FILAMENT-PATTERNS.md`
- **Scrape kit:** Stage 0.5 comprehensive portal capture — see `SCRAPE-KIT.md`

## What's per-agency (will differ)

- Logo, colors (within govt navy palette)
- Tender docs (LAMPIRAN A/T, SOC)
- Page IA (services, directory structure)
- Domain + branding

## Update rules

Found new pain on a portal build? Update template files HERE, not in the portal folder. Template = source of truth.
