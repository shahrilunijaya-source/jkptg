<div class="bg-primary-900 text-white text-xs no-print">
    <div class="container-page flex flex-wrap items-center justify-between py-2 gap-3">
        {{-- Ministry chain --}}
        <div class="hidden md:flex items-center gap-2 text-white/70" aria-label="{{ __('messages.utility.aria_chain') }}">
            <span class="text-[11px]">{{ app()->getLocale() === 'ms' ? 'Kerajaan Malaysia' : 'Government of Malaysia' }}</span>
            <span class="text-white/40" aria-hidden="true">›</span>
            <span class="text-[11px]">NRES</span>
            <span class="text-white/40" aria-hidden="true">›</span>
            <span class="text-white text-[11px] font-semibold">JKPTG</span>
        </div>

        <nav aria-label="{{ __('messages.utility.aria_main_links') }}" class="flex flex-wrap items-center gap-x-4 gap-y-1 ml-auto text-[12px]">
            <a href="{{ route('faq.index') }}" class="text-white/85 hover:text-white flex items-center gap-1.5">
                <x-heroicon-o-question-mark-circle class="w-3.5 h-3.5" aria-hidden="true" />
                <span>{{ __('messages.utility.soalan_lazim') }}</span>
            </a>
            <a href="{{ route('hubungi.index') }}" class="text-white/85 hover:text-white flex items-center gap-1.5">
                <x-heroicon-o-envelope class="w-3.5 h-3.5" aria-hidden="true" />
                <span>{{ __('messages.utility.hubungi') }}</span>
            </a>
            <a href="{{ route('hubungi.aduan') }}" class="text-white/85 hover:text-white flex items-center gap-1.5">
                <x-heroicon-o-chat-bubble-left-right class="w-3.5 h-3.5" aria-hidden="true" />
                <span>{{ __('messages.utility.aduan') }}</span>
            </a>
            <a href="{{ route('peta-laman') }}" class="text-white/85 hover:text-white flex items-center gap-1.5">
                <x-heroicon-o-map class="w-3.5 h-3.5" aria-hidden="true" />
                <span>{{ __('messages.utility.peta_laman') }}</span>
            </a>

            {{-- BM | EN segmented control --}}
            <span class="inline-flex items-stretch border border-white/30 text-[11px] font-semibold tracking-wide" role="group" aria-label="{{ __('messages.lang.toggle_label') }}">
                <a href="{{ route('locale.switch', 'ms') }}"
                   aria-current="{{ app()->getLocale() === 'ms' ? 'true' : 'false' }}"
                   class="px-2.5 py-1 transition-colors duration-150 {{ app()->getLocale() === 'ms' ? 'bg-white text-primary-900' : 'text-white/85 hover:text-white' }}">BM</a>
                <a href="{{ route('locale.switch', 'en') }}"
                   aria-current="{{ app()->getLocale() === 'en' ? 'true' : 'false' }}"
                   class="px-2.5 py-1 border-l border-white/30 transition-colors duration-150 {{ app()->getLocale() === 'en' ? 'bg-white text-primary-900' : 'text-white/85 hover:text-white' }}">EN</a>
            </span>

            {{-- Log Masuk: bronze CTA, civic accent --}}
            @auth
                <a href="{{ url('/admin') }}"
                   class="inline-flex items-center gap-1.5 bg-bronze hover:bg-bronze-dark text-white font-semibold px-3 py-1 text-[11px] rounded-sm transition-colors duration-150">
                    <x-heroicon-o-arrow-right-on-rectangle class="w-3.5 h-3.5" aria-hidden="true" />
                    <span>{{ __('messages.nav.log_masuk') }}</span>
                </a>
            @else
                <a href="{{ url('/admin/login') }}"
                   class="inline-flex items-center gap-1.5 bg-bronze hover:bg-bronze-dark text-white font-semibold px-3 py-1 text-[11px] rounded-sm transition-colors duration-150">
                    <x-heroicon-o-arrow-right-on-rectangle class="w-3.5 h-3.5" aria-hidden="true" />
                    <span>{{ __('messages.nav.log_masuk') }}</span>
                </a>
            @endauth
        </nav>
    </div>
</div>
