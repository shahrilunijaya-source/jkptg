<?php

namespace App\Providers\Filament;

use App\Http\Middleware\AutoLoginAdmin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use App\Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\SpatieLaravelTranslatablePlugin;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\MaxWidth;
use Filament\View\PanelsRenderHook;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\HtmlString;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->brandName('JKPTG')
            ->brandLogo(asset('images/logo-jkptg.png'))
            ->brandLogoHeight('2.2rem')
            ->colors([
                'primary' => Color::hex('#006837'),
                'warning' => Color::hex('#FFCC00'),
            ])
            ->sidebarCollapsibleOnDesktop()
            ->maxContentWidth(MaxWidth::ScreenTwoExtraLarge)
            ->plugin(
                SpatieLaravelTranslatablePlugin::make()->defaultLocales(['ms', 'en'])
            )
            ->navigationGroups([
                NavigationGroup::make('Kandungan'),
                NavigationGroup::make('Perkhidmatan'),
                NavigationGroup::make('Hubungi'),
                NavigationGroup::make('Chatbot'),
                NavigationGroup::make('Pentadbiran'),
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                \App\Filament\Widgets\StatsOverview::class,
                \App\Filament\Widgets\LlmCostMeter::class,
                \App\Filament\Widgets\VisitorChart::class,
                \App\Filament\Widgets\RecentActivity::class,
            ])
            ->renderHook(
                PanelsRenderHook::SIDEBAR_FOOTER,
                fn () => view('filament.hooks.sidebar-footer'),
            )
            ->renderHook(
                PanelsRenderHook::TOPBAR_START,
                fn () => new HtmlString('<span id="jk-page-title" class="jk-page-title"></span>'),
            )
            ->renderHook(
                PanelsRenderHook::TOPBAR_END,
                fn () => new HtmlString($this->topbarActions()),
            )
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn () => new HtmlString($this->panelHeadStyles()),
            )
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AutoLoginAdmin::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }

    protected function topbarActions(): string
    {
        return <<<'HTML'
        <div class="jk-topbar-actions">
            <button type="button" class="jk-bell" title="Notifikasi">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 2a6 6 0 0 0-6 6c0 1.887-.454 3.665-1.257 5.234a.75.75 0 0 0 .515 1.083 32.91 32.91 0 0 0 3.256.508 3.5 3.5 0 0 0 6.972 0 32.92 32.92 0 0 0 3.256-.508.75.75 0 0 0 .515-1.083A11.448 11.448 0 0 1 16 8a6 6 0 0 0-6-6ZM8.05 14.943a33.54 33.54 0 0 0 3.9 0 2 2 0 0 1-3.9 0Z" clip-rule="evenodd"/>
                </svg>
            </button>
            <a href="/admin/pages/create" class="jk-cta-btn">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" width="14" height="14"><path d="M10.75 4.75a.75.75 0 0 0-1.5 0v4.5h-4.5a.75.75 0 0 0 0 1.5h4.5v4.5a.75.75 0 0 0 1.5 0v-4.5h4.5a.75.75 0 0 0 0-1.5h-4.5v-4.5Z"/></svg>
                Cipta Kandungan
            </a>
        </div>
        HTML;
    }

    protected function panelHeadStyles(): string
    {
        return <<<'HTML'
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
        <style>
        /* === Portal JKPTG Admin — myLRMP portal style (white sidebar) === */
        * { font-family: 'Inter', ui-sans-serif, system-ui, sans-serif !important; }

        /* Body */
        .fi-body { background-color: #f9fafb !important; }
        .fi-main-ctn { padding: 24px 28px !important; }

        /* Topbar */
        .fi-topbar { background-color: #ffffff !important; box-shadow: none !important; border-bottom: 1px solid #e5e7eb !important; }
        .fi-topbar .fi-sidebar-collapse-btn,
        .fi-topbar .fi-sidebar-open-btn { color: #6b7280 !important; }
        .fi-topbar .fi-sidebar-collapse-btn:hover,
        .fi-topbar .fi-sidebar-open-btn:hover { background: #f3f4f6 !important; color: #166534 !important; }

        /* Hide default topbar user menu — user info is in sidebar footer */
        .fi-topbar .fi-user-menu { display: none !important; }

        /* Breadcrumb — show only current page as prominent title (like myLRMP) */
        .fi-breadcrumbs ol > li:not(:last-child) { display: none !important; }
        .fi-breadcrumbs ol > li:last-child span,
        .fi-breadcrumbs ol > li:last-child a {
            font-size: 0.9375rem !important;
            font-weight: 600 !important;
            color: #111827 !important;
            text-decoration: none !important;
        }

        /* Sidebar — white */
        .fi-sidebar, .fi-sidebar-nav, .fi-main-sidebar {
            background-color: #ffffff !important;
            border-right: 1px solid #e5e7eb !important;
        }
        .fi-sidebar-header { background-color: #ffffff !important; border-bottom: 1px solid #e5e7eb !important; }
        .fi-body-has-topbar .fi-sidebar-header { background-color: #ffffff !important; }

        /* Sidebar scrollbar */
        .fi-sidebar::-webkit-scrollbar { width: 4px; }
        .fi-sidebar::-webkit-scrollbar-track { background: transparent; }
        .fi-sidebar::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 2px; }

        /* Group labels */
        .fi-sidebar-group-label {
            color: #9ca3af !important;
            font-size: 0.6875rem !important;
            font-weight: 600 !important;
            letter-spacing: 0.07em !important;
            text-transform: uppercase !important;
        }
        .fi-sidebar-group-btn .fi-icon,
        .fi-sidebar-group-dropdown-trigger-btn .fi-icon { color: #9ca3af !important; }
        .fi-sidebar-group-dropdown-trigger-btn:hover { background-color: #f3f4f6 !important; }
        .fi-sidebar-group-dropdown-trigger-btn:hover .fi-icon { color: #374151 !important; }

        /* Nav items — inactive */
        .fi-sidebar-item-label { color: #374151 !important; font-size: 0.875rem !important; font-weight: 500 !important; }
        .fi-sidebar-item-btn > .fi-icon { color: #9ca3af !important; }
        .fi-sidebar-item-btn { border-radius: 6px !important; transition: background 0.1s !important; }

        /* Hover */
        .fi-sidebar-item.fi-sidebar-item-has-url > .fi-sidebar-item-btn:hover,
        .fi-sidebar-item.fi-sidebar-item-has-url > .fi-sidebar-item-btn:focus-visible { background-color: #f3f4f6 !important; }
        .fi-sidebar-item.fi-sidebar-item-has-url > .fi-sidebar-item-btn:hover .fi-sidebar-item-label { color: #111827 !important; }
        .fi-sidebar-item.fi-sidebar-item-has-url > .fi-sidebar-item-btn:hover > .fi-icon { color: #6b7280 !important; }

        /* Active — light green bg, dark green text */
        .fi-sidebar-item.fi-active > .fi-sidebar-item-btn { background-color: #f0fdf4 !important; border-radius: 6px !important; }
        .fi-sidebar-item.fi-active > .fi-sidebar-item-btn .fi-sidebar-item-label { color: #166534 !important; font-weight: 600 !important; }
        .fi-sidebar-item.fi-active > .fi-sidebar-item-btn .fi-icon { color: #16a34a !important; }

        /* Sidebar footer */
        .fi-sidebar-footer { border-top: 1px solid #e5e7eb !important; }
        .fi-sidebar-open-sidebar-btn, .fi-sidebar-close-sidebar-btn,
        .fi-sidebar-open-collapse-sidebar-btn, .fi-sidebar-close-collapse-sidebar-btn { color: #6b7280 !important; }

        /* Custom user footer */
        .fi-sidebar-user-footer { border-top: 1px solid #e5e7eb; padding: 10px 12px; display: flex; align-items: center; gap: 8px; flex-shrink: 0; }
        .fi-sf-user-link { display: flex; align-items: center; gap: 8px; flex: 1; min-width: 0; text-decoration: none; border-radius: 6px; padding: 4px 6px; margin: -4px -6px; transition: background 0.1s; }
        .fi-sf-user-link:hover { background: #f3f4f6; }
        .fi-sf-avatar { width: 32px; height: 32px; border-radius: 50%; background: #166534; color: #ffffff; font-size: 11.5px; font-weight: 700; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .fi-sf-info { flex: 1; min-width: 0; }
        .fi-sf-name { font-size: 12.5px; font-weight: 600; color: #111827; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; line-height: 1.3; }
        .fi-sf-role { font-size: 10.5px; color: #6b7280; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; margin-top: 1px; }
        .fi-sf-logout { background: none; border: none; cursor: pointer; color: #9ca3af; padding: 5px; border-radius: 6px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; transition: color 0.1s, background 0.1s; }
        .fi-sf-logout:hover { color: #dc2626; background: #fef2f2; }

        /* Page header */
        .fi-header-heading { font-size: 1.125rem !important; font-weight: 700 !important; letter-spacing: -0.02em !important; color: #111827 !important; line-height: 1.2 !important; }
        .fi-header-subheading { font-size: 0.75rem !important; color: #6b7280 !important; margin-top: 2px !important; }
        .fi-breadcrumbs ol li a { color: #006837 !important; font-size: 0.8125rem !important; font-weight: 500 !important; }
        .fi-breadcrumbs ol li a:hover { color: #004d28 !important; }

        /* Stat cards — white with subtle shadow */
        .fi-wi-stats-overview-stat-card,
        .fi-section:not(.fi-section-not-contained):not(.fi-aside) {
            border-radius: 10px !important;
            background-color: #ffffff !important;
            border: 1px solid #e5e7eb !important;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06) !important;
        }
        .fi-section-header { padding: 14px 20px !important; border-bottom: 1px solid #e5e7eb !important; }
        .fi-section-header-heading { font-size: 0.875rem !important; font-weight: 600 !important; color: #111827 !important; }
        .fi-section-header-description { font-size: 0.8125rem !important; color: #6b7280 !important; }
        .fi-section-content { padding: 20px !important; }
        .fi-section-content-ctn { padding: 0 !important; }

        /* Tables */
        .fi-ta-ctn { border-radius: 10px !important; overflow: hidden !important; background-color: #ffffff !important; border: 1px solid #e5e7eb !important; box-shadow: 0 1px 3px rgba(0,0,0,0.06) !important; }
        .fi-ta-filters-ctn, .fi-ta-header { padding: 12px 16px !important; border-bottom: 1px solid #e5e7eb !important; background: #ffffff !important; }
        .fi-ta-header-cell { background-color: #f9fafb !important; font-size: 10px !important; font-weight: 700 !important; letter-spacing: 0.07em !important; text-transform: uppercase !important; color: #9ca3af !important; padding: 9px 16px !important; border-bottom: 1px solid #e5e7eb !important; white-space: nowrap !important; }
        .fi-ta-actions-header-cell, .fi-ta-empty-header-cell { background-color: #f9fafb !important; border-bottom: 1px solid #e5e7eb !important; padding: 9px 16px !important; }
        .fi-ta-header-cell-sort-btn { color: #9ca3af !important; }
        .fi-ta-header-cell-sorted .fi-ta-header-cell-sort-btn { color: #006837 !important; }
        .fi-ta-cell { padding: 12px 16px !important; color: #374151 !important; font-size: 0.875rem !important; border-bottom: 1px solid #f3f4f6 !important; vertical-align: middle !important; }
        .fi-ta-selection-cell { padding: 12px !important; border-bottom: 1px solid #f3f4f6 !important; vertical-align: middle !important; }
        .fi-ta-row:last-child .fi-ta-cell, .fi-ta-row:last-child .fi-ta-selection-cell { border-bottom: none !important; }
        .fi-ta-row:hover .fi-ta-cell, .fi-ta-row:hover .fi-ta-selection-cell { background-color: #fafafa !important; }
        .fi-ta-pagination-ctn, .fi-ta-pagination { padding: 10px 16px !important; border-top: 1px solid #e5e7eb !important; background: #f9fafb !important; }
        .fi-pagination-item-btn.fi-active, .fi-pagination-item-btn[aria-current="page"] { background-color: #006837 !important; border-color: #006837 !important; color: #ffffff !important; }

        /* Badges — dot style like myLRMP */
        .fi-badge { display: inline-flex !important; align-items: center !important; gap: 5px !important; font-size: 11.5px !important; font-weight: 500 !important; padding: 3px 10px 3px 8px !important; border-radius: 6px !important; border: none !important; }
        .fi-badge::before { content: '' !important; width: 5px !important; height: 5px !important; border-radius: 50% !important; flex-shrink: 0 !important; display: inline-block !important; }
        .fi-badge.fi-color-success { background-color: #f0fdf4 !important; color: #15803d !important; }
        .fi-badge.fi-color-success::before { background-color: #16a34a !important; }
        .fi-badge.fi-color-warning { background-color: #fffbeb !important; color: #d97706 !important; }
        .fi-badge.fi-color-warning::before { background-color: #d97706 !important; }
        .fi-badge.fi-color-danger { background-color: #fef2f2 !important; color: #dc2626 !important; }
        .fi-badge.fi-color-danger::before { background-color: #dc2626 !important; }
        .fi-badge.fi-color-info { background-color: #eff6ff !important; color: #2563eb !important; }
        .fi-badge.fi-color-info::before { background-color: #2563eb !important; }
        .fi-badge.fi-color-gray, .fi-badge.fi-color-secondary { background-color: #f9fafb !important; color: #6b7280 !important; border: 1px solid #e5e7eb !important; }
        .fi-badge.fi-color-gray::before, .fi-badge.fi-color-secondary::before { background-color: #d1d5db !important; }
        .fi-badge.fi-color-primary { background-color: #f0fdf4 !important; color: #006837 !important; }
        .fi-badge.fi-color-primary::before { background-color: #006837 !important; }

        /* Form inputs */
        .fi-fo-field-wrp-label label { font-size: 0.8125rem !important; font-weight: 500 !important; color: #374151 !important; }
        .fi-input-wrp { border-radius: 8px !important; background-color: #ffffff !important; border: 1px solid #d1d5db !important; box-shadow: none !important; }
        .fi-input-wrp:not(.fi-disabled):not(:has(.fi-ac-action:focus)):focus-within { border-color: #006837 !important; box-shadow: 0 0 0 3px rgba(0,104,55,.10) !important; }
        .fi-input-wrp.fi-disabled { background-color: #f9fafb !important; opacity: 0.7 !important; }

        /* Buttons */
        .fi-btn { border-radius: 6px !important; font-weight: 500 !important; font-size: 0.875rem !important; transition: background 0.12s, transform 0.1s !important; }
        .fi-btn:hover { transform: translateY(-1px) !important; }
        .fi-btn:active { transform: translateY(0) !important; }

        /* Dropdowns */
        .fi-dropdown-panel { border-radius: 10px !important; border: 1px solid #e5e7eb !important; box-shadow: rgba(0,0,0,.12) 0px 8px 24px -4px !important; overflow: hidden !important; }
        .fi-dropdown-list-item-btn { font-size: 0.875rem !important; color: #374151 !important; padding: 8px 14px !important; }
        .fi-dropdown-list-item-btn:hover { background-color: #f9fafb !important; color: #111827 !important; }

        /* Modals */
        .fi-modal-window { border-radius: 12px !important; border: 1px solid #e5e7eb !important; box-shadow: rgba(0,0,0,.15) 0px 20px 40px -8px !important; }
        .fi-modal-header { padding: 18px 22px 16px !important; border-bottom: 1px solid #e5e7eb !important; }
        .fi-modal-heading { font-size: 1rem !important; font-weight: 600 !important; color: #111827 !important; }
        .fi-modal-content { padding: 20px 22px !important; }
        .fi-modal-footer { padding: 14px 22px !important; border-top: 1px solid #e5e7eb !important; background: #f9fafb !important; }

        /* Topbar page title (synced from active sidebar nav label) */
        .jk-page-title { font-size: 0.9375rem !important; font-weight: 600 !important; color: #111827 !important; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 320px; }

        /* Topbar actions (bell + CTA) */
        .jk-topbar-actions { display: inline-flex; align-items: center; gap: 8px; margin-right: 8px; }
        .jk-bell { display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 36px; border-radius: 999px; background: #f3f4f6; border: 1px solid #e5e7eb; color: #6b7280; cursor: pointer; transition: all 140ms; flex-shrink: 0; }
        .jk-bell:hover { background: #f0fdf4; color: #166534; border-color: #bbf7d0; }
        .jk-bell svg { width: 17px; height: 17px; }
        .jk-cta-btn { display: inline-flex; align-items: center; gap: 6px; padding: 7px 14px; background: #006837; color: #ffffff !important; font-size: 13.5px; font-weight: 600; border-radius: 7px; text-decoration: none !important; transition: background 120ms, transform 100ms; white-space: nowrap; }
        .jk-cta-btn:hover { background: #004d28; transform: translateY(-1px); }
        .jk-cta-btn:active { transform: translateY(0); }

        /* Stats grid — 4 columns on wide screens like myLRMP */
        .fi-wi-stats-overview-stats-ctn { grid-template-columns: repeat(4, minmax(0, 1fr)) !important; gap: 16px !important; }
        @media (max-width: 1280px) { .fi-wi-stats-overview-stats-ctn { grid-template-columns: repeat(2, minmax(0, 1fr)) !important; } }
        @media (max-width: 640px)  { .fi-wi-stats-overview-stats-ctn { grid-template-columns: repeat(1, minmax(0, 1fr)) !important; } }

        /* Compact widget + section spacing */
        .fi-wi { margin-bottom: 0 !important; }
        .fi-dashboard-widgets-ctn { gap: 16px !important; }

        /* Stat cards — colored top border (color class is on child, use :has()) */
        .fi-wi-stats-overview-stat {
            border-top: 3px solid #e5e7eb !important;
            border-radius: 10px !important;
            background: #ffffff !important;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06) !important;
        }
        .fi-wi-stats-overview-stat:has(.fi-color-primary)  { border-top-color: #006837 !important; }
        .fi-wi-stats-overview-stat:has(.fi-color-success)  { border-top-color: #16a34a !important; }
        .fi-wi-stats-overview-stat:has(.fi-color-warning)  { border-top-color: #d97706 !important; }
        .fi-wi-stats-overview-stat:has(.fi-color-danger)   { border-top-color: #dc2626 !important; }
        .fi-wi-stats-overview-stat:has(.fi-color-info)     { border-top-color: #2563eb !important; }
        /* Labels */
        .fi-wi-stats-overview-stat-label { font-size: 11px !important; font-weight: 700 !important; letter-spacing: 0.05em !important; text-transform: uppercase !important; color: #9ca3af !important; }
        /* Values — colored per stat */
        .fi-wi-stats-overview-stat-value { font-size: 2rem !important; font-weight: 700 !important; line-height: 1.1 !important; color: #111827 !important; }
        .fi-wi-stats-overview-stat:has(.fi-color-warning) .fi-wi-stats-overview-stat-value { color: #d97706 !important; }
        .fi-wi-stats-overview-stat:has(.fi-color-danger)  .fi-wi-stats-overview-stat-value { color: #dc2626 !important; }
        .fi-wi-stats-overview-stat:has(.fi-color-success) .fi-wi-stats-overview-stat-value { color: #16a34a !important; }
        .fi-wi-stats-overview-stat:has(.fi-color-primary) .fi-wi-stats-overview-stat-value { color: #006837 !important; }
        .fi-wi-stats-overview-stat:has(.fi-color-info)    .fi-wi-stats-overview-stat-value { color: #2563eb !important; }
        /* Description */
        .fi-wi-stats-overview-stat-description { font-size: 12px !important; color: #6b7280 !important; margin-top: 4px !important; }

        /* Infolist */
        .fi-in-entry-label-ctn label, .fi-in-entry-label { font-size: 10px !important; font-weight: 700 !important; letter-spacing: 0.07em !important; text-transform: uppercase !important; color: #9ca3af !important; margin-bottom: 4px !important; }
        .fi-in-text-entry-content { font-size: 0.9375rem !important; color: #111827 !important; line-height: 1.5 !important; }
        </style>
        <script>
        function jkSyncTitle() {
            var el = document.getElementById('jk-page-title');
            if (!el) return;
            var active = document.querySelector('.fi-sidebar-item.fi-active .fi-sidebar-item-label');
            if (active) { el.textContent = active.textContent.trim(); return; }
            var bc = document.querySelector('.fi-breadcrumbs ol > li:last-child');
            if (bc) { el.textContent = bc.textContent.trim(); }
        }
        document.addEventListener('DOMContentLoaded', jkSyncTitle);
        document.addEventListener('livewire:navigated', function() { setTimeout(jkSyncTitle, 50); });
        </script>
        HTML;
    }
}
