<header class="border-b border-slate-200 bg-white sticky top-0 z-30 no-print"
        x-data="{ megaOpen: false, mobileOpen: false }"
        @keydown.escape.window="megaOpen = false; mobileOpen = false">
    <div class="container-page flex items-center justify-between gap-6 py-3.5">
        {{-- Wordmark + JATA --}}
        <a href="{{ route('home') }}" class="flex items-center gap-3 group" aria-label="{{ __('messages.site_name') }}">
            <img src="{{ asset('images/jata-negara.png') }}" alt="Jata Negara" class="h-12 w-auto object-contain flex-shrink-0">
            <div class="hidden md:block min-w-0 leading-tight">
                <div class="text-[10px] uppercase tracking-[0.18em] text-slate-500 font-semibold">
                    {{ __('messages.utility.official_short') }}
                </div>
                <div class="font-bold text-primary text-base lg:text-[17px] tracking-tight mt-0.5">
                    {{ __('messages.site_description') }}
                </div>
                <div class="text-[11px] text-slate-500 mt-0.5">
                    {{ __('messages.ministry_name') }}
                </div>
            </div>
            <img src="{{ asset('images/logo-jkptg.png') }}" alt="JKPTG" class="h-11 w-auto object-contain flex-shrink-0 hidden lg:block ml-3 border-l border-slate-200 pl-3">
        </a>

        {{-- Primary nav --}}
        <nav aria-label="{{ __('messages.nav.utama') }}" class="hidden lg:flex items-center gap-1 text-[14px] font-semibold">
            @php
                $navItems = [
                    ['label' => __('messages.nav.utama'), 'href' => route('home'), 'mega' => false],
                    ['label' => __('messages.nav.perkhidmatan'), 'href' => route('service.index'), 'mega' => true],
                    ['label' => __('messages.nav.panduan'), 'href' => route('panduan.index'), 'mega' => false],
                    ['label' => __('messages.nav.korporat'), 'href' => route('korporat.index'), 'mega' => false],
                    ['label' => __('messages.nav.sumber'), 'href' => route('sumber.index'), 'mega' => false],
                ];
            @endphp

            @foreach($navItems as $item)
                @if($item['mega'])
                    <button type="button"
                            @click.stop="megaOpen = !megaOpen"
                            :aria-expanded="megaOpen ? 'true' : 'false'"
                            aria-haspopup="true"
                            aria-controls="megamenu-panel"
                            class="relative px-3 py-2 transition-colors duration-150 flex items-center gap-1"
                            :class="megaOpen ? 'text-primary' : 'text-slate-700 hover:text-primary'">
                            {{ $item['label'] }}
                            <x-heroicon-o-chevron-down class="w-3.5 h-3.5" aria-hidden="true" />
                        <span class="absolute left-3 right-3 -bottom-px h-0.5 bg-primary transition-opacity duration-150"
                              :class="megaOpen ? 'opacity-100' : 'opacity-0'"></span>
                    </button>
                @else
                    <a href="{{ $item['href'] }}"
                       class="relative px-3 py-2 text-slate-700 hover:text-primary transition-colors duration-150">
                        {{ $item['label'] }}
                        <span class="absolute left-3 right-3 -bottom-px h-0.5 bg-primary opacity-0 hover:opacity-30 transition-opacity duration-150"></span>
                    </a>
                @endif
            @endforeach
        </nav>

        {{-- Search --}}
        <form action="{{ route('search.index') }}" method="get" class="hidden lg:block relative" role="search">
            <label for="header-search" class="sr-only">{{ __('messages.search.input_label') }}</label>
            <x-heroicon-o-magnifying-glass class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none" aria-hidden="true" />
            <input id="header-search"
                   type="search"
                   name="q"
                   minlength="2"
                   required
                   placeholder="{{ __('messages.search.placeholder') }}"
                   class="w-52 xl:w-60 border border-slate-300 bg-white pl-9 pr-3 py-2 text-sm text-slate-800 placeholder:text-slate-400 rounded-sm focus:outline-none focus:border-primary focus:ring-0 transition-colors duration-150">
        </form>

        {{-- Mobile toggle --}}
        <button type="button"
                @click="mobileOpen = !mobileOpen"
                :aria-expanded="mobileOpen ? 'true' : 'false'"
                class="lg:hidden p-2 -mr-2 text-slate-700"
                :aria-label="mobileOpen ? '{{ __('messages.nav.close_menu') }}' : '{{ __('messages.nav.open_menu') }}'">
            <x-heroicon-o-bars-3 x-show="!mobileOpen" class="w-6 h-6" />
            <x-heroicon-o-x-mark x-show="mobileOpen" x-cloak class="w-6 h-6" />
        </button>
    </div>

    {{-- Mobile drawer --}}
    <div x-show="mobileOpen" x-cloak class="lg:hidden border-t border-slate-200 bg-white">
        <nav class="container-page py-4 flex flex-col gap-1">
            <form action="{{ route('search.index') }}" method="get" class="relative mb-3" role="search">
                <label for="mobile-search" class="sr-only">{{ __('messages.search.input_label') }}</label>
                <x-heroicon-o-magnifying-glass class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" aria-hidden="true" />
                <input id="mobile-search" type="search" name="q" minlength="2" required
                       placeholder="{{ __('messages.search.placeholder') }}"
                       class="w-full border border-slate-300 bg-white pl-9 pr-3 py-2 text-sm rounded-sm focus:outline-none focus:border-primary">
            </form>
            @foreach($navItems as $item)
                <a href="{{ $item['href'] }}" class="px-3 py-2.5 text-[15px] font-semibold text-slate-700 hover:bg-primary-50 hover:text-primary transition-colors duration-150 rounded-sm">{{ $item['label'] }}</a>
            @endforeach
        </nav>
    </div>

    @include('partials.megamenu')
</header>
