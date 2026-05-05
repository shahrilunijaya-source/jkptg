# Filament Patterns — Govt Portal

Gotchas captured from Portal-JPPH. Apply at Stage 6.5 scaffold time.

## Stage 6.5 checklist

```bash
# 1. Install
composer require filament/filament

# 2. Create panel provider
php artisan filament:install --panels

# 3. Create admin user
php artisan make:filament-user
```

## User model — MUST implement FilamentUser

```php
// app/Models/User.php
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

class User extends Authenticatable implements FilamentUser
{
    public function canAccessPanel(Panel $panel): bool
    {
        // Govt portal: gate by role/email domain
        return $this->hasRole('admin') || str_ends_with($this->email, '@gov.my');
    }
}
```

**Why:** Without this, Filament denies access to all users in production. JPPH hit this late = redirect loop in prod.

## Panel provider gotchas

- `AdminPanelProvider` registered in `bootstrap/providers.php` (Laravel 11+) NOT `config/app.php`
- Panel path = `/admin` by default. Match this in nginx config + CSP.
- `->authGuard('web')` — keep default unless multi-auth

## Resource class names

- File: `app/Filament/Resources/UserResource.php`
- Class: `UserResource` (not `UserResources`, not `User`)
- Pages dir: `app/Filament/Resources/UserResource/Pages/`

JPPH had parse errors from FQCN imports — use full class paths in resource methods, not `use` aliases for Pages namespace.

## Dashboard widgets

- **Wire real data first** (see LESSONS.md rule 4)
- Stat widget: extend `BaseWidget`, return `Stat::make()` with live query
- Chart widget: extend `ChartWidget`, return real query result, NOT random data
- Auth-aware widgets: use `Auth::user()` inside `getData()`, not constructor

## Public pages — DO NOT use Filament

Filament = admin panel only. Public pages (homepage, direktori, perkhidmatan):

- Use **Livewire components** + **Blade views**
- Routes in `routes/web.php`, NOT Filament routes
- No Filament forms/tables on public pages — build with Livewire + Tailwind

## Production deploy

- `php artisan filament:optimize` after deploy
- `php artisan filament:cache-components` for Livewire perf
- CSP must allow Filament's inline styles or set `meta_themes` in panel provider

## Common errors + fixes

| Error | Cause | Fix |
|-------|-------|-----|
| 403 on `/admin` after login | Missing `FilamentUser` interface | Implement `canAccessPanel` on User |
| "Class not found" in Resource | Wrong namespace in `use` import | Use FQCN or fix namespace |
| Dashboard widget shows zeros | Placeholder data left in | Wire to real query |
| Livewire component not updating | `wire:model` mismatch | Match property name exactly |
| Mixed content warning | Asset URL using http | Set `APP_URL` to https in `.env` |
