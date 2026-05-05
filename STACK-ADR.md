# Architecture Decision Record — Stack

**Status:** LOCKED 2026-05-05
**Decision-maker:** Shahril
**Re-debate policy:** Do NOT propose alternatives unless user explicitly says "rethink stack".

## Decision

| Layer | Choice |
|-------|--------|
| Backend framework | **Laravel** (latest LTS) |
| Admin panel | **Filament** (`/admin` only) |
| Public-facing pages | **Livewire + Blade** (custom design freedom) |
| CSS | **Tailwind** |
| DB | **MySQL/MariaDB** (govt hosting compatible) |
| Auth | **Filament default + `FilamentUser` interface** |
| Hosting | **Hostinger** (auto-pull via webhook from GitHub) |

## Why locked

- **Filament admin:** Shahril likes default login UI + internal admin pages. Pre-built resources save weeks. Curve already paid on Portal-JPPH.
- **Livewire+Blade public:** Filament too opinionated for public pages. JPPH burned time fighting Filament for homepage. Livewire = full control + reactive PHP without SPA complexity.
- **Hybrid is correct:** admin CRUD is solved-problem (use Filament), public design is differentiator (use raw Livewire).

## What NOT to propose on future portals

- ❌ Backpack for Laravel (different opinions, restart curve)
- ❌ Laravel Nova (paid, no clear win over Filament for this use case)
- ❌ Roll-own Livewire admin (most work, no payoff)
- ❌ Inertia + Vue/React SPA (drops Livewire reactive model entirely, restart curve)
- ❌ Filament for public pages (already tried, painful)

## When to revisit

Only if:
1. User explicitly says "rethink stack" / "rethink admin"
2. Filament drops Laravel support
3. Govt mandates specific stack via PPPA update
