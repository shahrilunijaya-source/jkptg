<div class="bg-primary text-white text-xs no-print">
    <div class="container-page flex flex-wrap items-center justify-between py-2 gap-2">
        <div class="hidden sm:block">{{ __('messages.utility.official_site') }}</div>
        <nav aria-label="{{ __('messages.utility.aria_main_links') }}" class="flex flex-wrap gap-3 ml-auto items-center">
            <a href="#" class="hover:underline flex items-center gap-1">
                <x-heroicon-o-question-mark-circle class="w-3.5 h-3.5" />
                <span>{{ __('messages.utility.soalan_lazim') }}</span>
            </a>
            <a href="#" class="hover:underline flex items-center gap-1">
                <x-heroicon-o-phone class="w-3.5 h-3.5" />
                <span>{{ __('messages.utility.hubungi') }}</span>
            </a>
            <a href="#" class="hover:underline flex items-center gap-1">
                <x-heroicon-o-megaphone class="w-3.5 h-3.5" />
                <span>{{ __('messages.utility.aduan') }}</span>
            </a>
            <a href="#" class="hover:underline flex items-center gap-1">
                <x-heroicon-o-map class="w-3.5 h-3.5" />
                <span>{{ __('messages.utility.peta_laman') }}</span>
            </a>
            <span class="border-l border-white/30 pl-3 flex items-center gap-2">
                <a href="{{ route('locale.switch', 'ms') }}" class="{{ app()->getLocale() === 'ms' ? 'font-semibold underline' : 'opacity-70 hover:opacity-100' }}" title="{{ __('messages.lang.switch_to_ms') }}">BM</a>
                <span aria-hidden="true">|</span>
                <a href="{{ route('locale.switch', 'en') }}" class="{{ app()->getLocale() === 'en' ? 'font-semibold underline' : 'opacity-70 hover:opacity-100' }}" title="{{ __('messages.lang.switch_to_en') }}">EN</a>
            </span>
        </nav>
    </div>
</div>
