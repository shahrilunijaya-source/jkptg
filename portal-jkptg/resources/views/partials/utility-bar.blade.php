<div class="bg-slate-900 text-white text-xs no-print">
    <div class="container-page flex flex-wrap items-center justify-between py-2 gap-3">
        {{-- Breadcrumb: ministry chain (mono small caps) --}}
        <div class="hidden md:flex items-center gap-2 mono-cap text-white/60" aria-label="{{ __('messages.utility.aria_chain') }}">
            <span>KPKM</span>
            <span class="text-white/30" aria-hidden="true">/</span>
            <span>NRES</span>
            <span class="text-white/30" aria-hidden="true">/</span>
            <span class="text-white">JKPTG</span>
        </div>

        <nav aria-label="{{ __('messages.utility.aria_main_links') }}" class="flex flex-wrap items-center gap-x-5 gap-y-1 ml-auto">
            <a href="{{ route('faq.index') }}" class="font-mono uppercase tracking-[0.08em] text-[11px] text-white/80 hover:text-white">{{ __('messages.utility.soalan_lazim') }}</a>
            <a href="{{ route('hubungi.index') }}" class="font-mono uppercase tracking-[0.08em] text-[11px] text-white/80 hover:text-white">{{ __('messages.utility.hubungi') }}</a>
            <a href="{{ route('hubungi.aduan') }}" class="font-mono uppercase tracking-[0.08em] text-[11px] text-white/80 hover:text-white">{{ __('messages.utility.aduan') }}</a>
            <a href="{{ route('peta-laman') }}" class="font-mono uppercase tracking-[0.08em] text-[11px] text-white/80 hover:text-white">{{ __('messages.utility.peta_laman') }}</a>

            {{-- BM | EN segmented control --}}
            <span class="inline-flex items-stretch border border-white/30 font-mono text-[11px] font-medium" role="group" aria-label="{{ __('messages.lang.toggle_label') }}">
                <a href="{{ route('locale.switch', 'ms') }}"
                   aria-current="{{ app()->getLocale() === 'ms' ? 'true' : 'false' }}"
                   class="px-2.5 py-1 transition-colors duration-150 {{ app()->getLocale() === 'ms' ? 'bg-white text-slate-900' : 'text-white/80 hover:text-white' }}">BM</a>
                <a href="{{ route('locale.switch', 'en') }}"
                   aria-current="{{ app()->getLocale() === 'en' ? 'true' : 'false' }}"
                   class="px-2.5 py-1 border-l border-white/30 transition-colors duration-150 {{ app()->getLocale() === 'en' ? 'bg-white text-slate-900' : 'text-white/80 hover:text-white' }}">EN</a>
            </span>

            {{-- Log Masuk: restrained outlined ghost --}}
            @auth
                <a href="{{ url('/admin') }}"
                   class="inline-flex items-center gap-1.5 border border-white/40 hover:border-white text-white px-3 py-1 font-mono uppercase tracking-[0.08em] text-[11px] font-medium transition-colors duration-150">
                    <span>{{ __('messages.nav.log_masuk') }}</span>
                    <span aria-hidden="true">→</span>
                </a>
            @else
                <a href="{{ url('/admin/login') }}"
                   class="inline-flex items-center gap-1.5 border border-white/40 hover:border-white text-white px-3 py-1 font-mono uppercase tracking-[0.08em] text-[11px] font-medium transition-colors duration-150">
                    <span>{{ __('messages.nav.log_masuk') }}</span>
                    <span aria-hidden="true">→</span>
                </a>
            @endauth
        </nav>
    </div>
</div>
