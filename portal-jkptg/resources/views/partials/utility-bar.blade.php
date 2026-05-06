<div class="bg-primary text-white text-xs no-print">
    <div class="container-page flex flex-wrap items-center justify-end py-2 gap-3">
        <nav aria-label="{{ __('messages.utility.aria_main_links') }}" class="flex flex-wrap gap-4 items-center">
            <a href="{{ route('faq.index') }}" class="hover:underline flex items-center gap-1.5">
                <x-heroicon-o-question-mark-circle class="w-4 h-4" />
                <span>{{ __('messages.utility.soalan_lazim') }}</span>
            </a>
            <a href="{{ route('hubungi.index') }}" class="hover:underline flex items-center gap-1.5">
                <x-heroicon-o-envelope class="w-4 h-4" />
                <span>{{ __('messages.utility.hubungi') }}</span>
            </a>
            <a href="{{ route('hubungi.aduan') }}" class="hover:underline flex items-center gap-1.5">
                <x-heroicon-o-chat-bubble-left-right class="w-4 h-4" />
                <span>{{ __('messages.utility.aduan') }}</span>
            </a>
            <a href="{{ route('peta-laman') }}" class="hover:underline flex items-center gap-1.5">
                <x-heroicon-o-map class="w-4 h-4" />
                <span>{{ __('messages.utility.peta_laman') }}</span>
            </a>

            {{-- BM / EN pill --}}
            <span class="inline-flex items-center border border-white/40 rounded-md overflow-hidden text-[11px] font-semibold">
                <a href="{{ route('locale.switch', 'ms') }}"
                   class="px-2 py-1 {{ app()->getLocale() === 'ms' ? 'bg-white/15' : 'hover:bg-white/10' }}"
                   title="{{ __('messages.lang.switch_to_ms') }}">MS</a>
                <span class="text-white/40" aria-hidden="true">/</span>
                <a href="{{ route('locale.switch', 'en') }}"
                   class="px-2 py-1 {{ app()->getLocale() === 'en' ? 'bg-white/15' : 'hover:bg-white/10' }}"
                   title="{{ __('messages.lang.switch_to_en') }}">EN</a>
            </span>

            {{-- Log Masuk orange pill --}}
            @auth
                <a href="{{ url('/admin') }}"
                   class="inline-flex items-center gap-1.5 bg-jata-yellow text-primary font-semibold rounded-md px-3 py-1.5 hover:bg-yellow-300 transition">
                    <x-heroicon-o-arrow-right-on-rectangle class="w-4 h-4" />
                    <span>{{ __('messages.nav.log_masuk') }}</span>
                </a>
            @else
                <a href="{{ url('/admin/login') }}"
                   class="inline-flex items-center gap-1.5 bg-jata-yellow text-primary font-semibold rounded-md px-3 py-1.5 hover:bg-yellow-300 transition">
                    <x-heroicon-o-arrow-right-on-rectangle class="w-4 h-4" />
                    <span>{{ __('messages.nav.log_masuk') }}</span>
                </a>
            @endauth
        </nav>
    </div>
</div>
