# Portal-JKPTG — Build Instructions

**Project:** Jabatan Ketua Pengarah Tanah dan Galian Persekutuan govt portal
**Design profile:** Navy (govt formal)
**Default model:** Sonnet. Use Opus for `/plan-eng-review` + `/cso`.
**Stack:** LOCKED. See `STACK-ADR.md`. Do not re-debate.

---

## Start Here (every new session)

1. Read `STACK-ADR.md` — stack decision (no re-pitch)
2. Read `LESSONS.md` — iteration budgets + JPPH pain rules
3. Read `FILAMENT-PATTERNS.md` — admin gotchas
4. Read `reference/PPPA-Bil-1-2025.pdf` — mandatory govt portal rules
5. Read `reference/LAMPIRAN_A_*.docx` — scope + purpose (JKPTG-specific)
6. Read `reference/LAMPIRAN_T_*.docx` — feature checklist (JKPTG-specific)
7. Read `reference/SOC*.xlsx` — compliance matrix (JKPTG-specific)
8. Read `SCRAPE-KIT.md` — comprehensive existing-portal capture spec
9. Check current stage in `STATE.md` (create if missing)
10. Resume from current stage

---

## Workflow Stages

### Stage 0 — Inputs & Constraints
| Step | Action |
|------|--------|
| 0a | Read PPPA PDF — confirm rules unchanged from template digest |
| 0b | Read LAMPIRAN A — scope, audience, purpose |
| 0c | Read LAMPIRAN T — feature checklist |
| 0d | Read SOC xlsx — compliance matrix |
| 0e | Pull JKPTG logo from `www.jkptg.gov.my` (SVG/PNG) → `assets/` |
| 0f | Confirm MyGovUEA / GA-IDS design system applicability |
| 0g | Output: `CONSTRAINTS.md` (rules + must-haves + tech stack) |

### Stage 0.5 — Comprehensive Portal Scrape (NEW vs JPPH)
| Step | Action |
|------|--------|
| 0.5a | Read `SCRAPE-KIT.md` — full capture spec |
| 0.5b | Run scrape: pages + PDFs + images + forms + screenshots + audits |
| 0.5c | Output structured to `reference/scrape/` (see SCRAPE-KIT.md tree) |
| 0.5d | Run gap analysis: PPPA violations + a11y + perf + content vs LAMPIRAN T |
| 0.5e | Bundle tender artifacts: `SITE-AUDIT.pdf`, `CONTENT-INVENTORY.xlsx`, `MIGRATION-PLAN.md`, `RISK-REGISTER.md` |
| 0.5f | These artifacts = tender evaluation killer. Do not skip. |

### Stage 1 — Scope
| Step | Skill |
|------|-------|
| 1a | `superpowers:brainstorming` — pin user, wedge, edge cases |
| 1b | **Lock IA here** — homepage sections, megamenu structure, login flow. No redesigns Stage 6+. |
| 1c | Write spec to `SPEC.md` |

### Stage 2 — Reference Design
| Step | Skill |
|------|-------|
| 2a | `/extract-design https://www.jkptg.gov.my` — tokens, colors, fonts |
| 2b | `/extract-design <2-3 reference govt portals>` — compare patterns |

### Stage 3 — Design System
| Step | Skill |
|------|-------|
| 3a | `/design-consultation` — build from extract + PPPA |
| 3b | `/awesome-design-md` — pull pattern reference if needed |
| 3c | Output: `DESIGN.md` (locked tokens + components) |

### Stage 4 — Plan
| Step | Skill |
|------|-------|
| 4a | `claude-mem:make-plan` — phased plan |
| 4b | `/plan-ceo-review` — challenge scope |
| 4c | `/plan-design-review` — score design dimensions |
| 4d | `/plan-eng-review` — lock architecture |

### Stage 5 — Visual Variants
| Step | Skill |
|------|-------|
| 5a | `/design-shotgun` — **max 3 variants per page** (see LESSONS.md) |
| 5b | Pick winner. No v4. |

### Stage 6 — HTML Mockup
| Step | Skill |
|------|-------|
| 6a | `/design-html` — production HTML/CSS |
| 6b | Approve mockup before code |

### Stage 6.5 — Auth + Admin Scaffold (NEW vs JPPH)
| Step | Skill |
|------|-------|
| 6.5a | Install Filament, set up panel provider |
| 6.5b | Implement `FilamentUser` interface on User model FIRST (not later) |
| 6.5c | Seed admin user, verify `/admin` login works |
| 6.5d | See `FILAMENT-PATTERNS.md` for full checklist |

### Stage 7 — Build
| Step | Skill |
|------|-------|
| 7a | `claude-mem:do` OR `superpowers:executing-plans` — phased build |
| 7b | TDD via `superpowers:test-driven-development` |
| 7c | Public pages = Livewire + Blade. Admin = Filament resources. |

### Stage 8 — QA
| Step | Skill |
|------|-------|
| 8a | `/qa` — test + fix loop |
| 8b | `/design-review` — visual polish |
| 8c | **PPPA compliance pass** — match SOC xlsx row by row |
| 8d | `/cso` — security audit (govt portal required) |

### Stage 9 — Performance
| Step | Skill |
|------|-------|
| 9a | `/benchmark` — Web Vitals baseline |
| 9b | `/health` — code quality score |

### Stage 10 — Ship
| Step | Skill |
|------|-------|
| 10a | `/review` — pre-landing PR review |
| 10b | `/ship` — PR creation |
| 10c | `/land-and-deploy` — merge + deploy |
| 10d | `/canary` — post-deploy monitor |

---

## Rules

- **Read before edit.** Always read file before modifying.
- **Logo:** source SVG from official site, not screenshot.
- **PPPA non-negotiable.** Every stage check against PDF.
- **Tender link:** loop TenderAI (Yusra) for ePerolehan + lampiran handling.
- **Update `STATE.md`** after each stage so next session resumes cleanly.
- **No stack re-debate.** See `STACK-ADR.md`.
- **Iteration budgets enforced.** See `LESSONS.md`.

---

## Reference Files

| File | Purpose |
|------|---------|
| `reference/PPPA-Bil-1-2025.pdf` | Govt portal mandatory rules (template-shipped) |
| `reference/LAMPIRAN_A_*.docx` | Project scope (JKPTG-specific, drop in) |
| `reference/LAMPIRAN_T_*.docx` | Tech specs checklist (JKPTG-specific, drop in) |
| `reference/SOC*.xlsx` | Compliance matrix (JKPTG-specific, drop in) |

---

## Output Files (created during workflow)

| File | Stage | Purpose |
|------|-------|---------|
| `CONSTRAINTS.md` | 0 | Locked rules + must-haves |
| `SPEC.md` | 1 | Scope + user/wedge + IA |
| `DESIGN.md` | 3 | Design system |
| `PLAN.md` | 4 | Phased build plan |
| `STATE.md` | continuous | Current stage + progress |
