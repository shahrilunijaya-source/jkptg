<header class="border-b border-slate-200 bg-white sticky top-0 z-30 no-print"
        x-data="{ megaOpen: false, mobileOpen: false }"
        @keydown.escape.window="megaOpen = false; mobileOpen = false"
        @keydown.window.prevent.meta.k="$refs.headerSearch && $refs.headerSearch.focus()"
        @keydown.window.prevent.ctrl.k="$refs.headerSearch && $refs.headerSearch.focus()">
    <div class="container-page flex items-center justify-between gap-6 py-4">
        {{-- Wordmark + JATA --}}
        <a href="{{ route('home') }}" class="flex items-center gap-3 group" aria-label="{{ __('messages.site_name') }}">
            <img src="{{ asset('images/jata-negara.png') }}" alt="Jata Negara" class="h-12 w-auto object-contain flex-shrink-0">
            <div class="hidden md:block min-w-0 leading-tight">
                <div class="mono-cap text-slate-500">
                    {{ __('messages.utility.official_short') }}
                </div>
                <div class="font-bold text-primary text-base lg:text-[17px] tracking-tight">
                    {{ __('messages.site_description') }}
                </div>
                <div class="text-[11px] text-slate-500 mt-0.5">
                    {{ __('messages.ministry_name') }}
                </div>
            </div>
            <img src="{{ asset('images/logo-jkptg.png') }}" alt="JKPTG" class="h-11 w-auto object-contain flex-shrink-0 hidden lg:block ml-3 border-l border-slate-200 pl-3">
        </a>

        {{-- Primary nav: each item carries a mono category code above its label --}}
        <nav aria-label="{{ __('messages.nav.utama') }}" class="hidden lg:flex items-end gap-1 text-sm">
            @php
                $navItems = [
                    ['code' => '01', 'label' => __('messages.nav.utama'), 'href' => route('home'), 'mega' => false],
                    ['code' => '02', 'label' => __('messages.nav.perkhidmatan'), 'href' => route('service.index'), 'mega' => true],
                    ['code' => '03', 'label' => __('messages.nav.panduan'), 'href' => route('panduan.index'), 'mega' => false],
                    ['code' => '04', 'label' => __('messages.nav.korporat'), 'href' => route('korporat.index'), 'mega' => false],
                    ['code' => '05', 'label' => __('messages.nav.sumber'), 'href' => route('sumber.index'), 'mega' => false],
                ];
            @endphp

            @foreach($navItems as $item)
                @if($item['mega'])
                    <button type="button"
                            @click.stop="megaOpen = !megaOpen"
                            :aria-expanded="megaOpen ? 'true' : 'false'"
                            aria-haspopup="true"
                            aria-controls="megamenu-panel"
                            class="group relative px-3 py-2 text-left transition-colors duration-150"
                            :class="megaOpen ? 'text-primary' : 'text-slate-900 hover:text-primary'">
                        <span class="mono-cap text-slate-400 block leading-none mb-1">{{ $item['code'] }}</span>
                        <span class="flex items-center gap-1 font-medium leading-none">
                            {{ $item['label'] }}
                            <x-heroicon-o-chevron-down class="w-3 h-3" aria-hidden="true" />
                        </span>
                        <span class="absolute left-3 right-3 -bottom-px h-0.5 bg-primary transition-opacity duration-150"
                              :class="megaOpen ? 'opacity-100' : 'opacity-0'"></span>
                    </button>
                @else
                    <a href="{{ $item['href'] }}"
                       class="group relative px-3 py-2 text-slate-900 hover:text-primary transition-colors duration-150">
                        <span class="mono-cap text-slate-400 block leading-none mb-1">{{ $item['code'] }}</span>
                        <span class="font-medium leading-none">{{ $item['label'] }}</span>
                        <span class="absolute left-3 right-3 -bottom-px h-0.5 bg-primary opacity-0 group-hover:opacity-30 transition-opacity duration-150"></span>
                    </a>
                @endif
            @endforeach
        </nav>

        {{-- Search: command-K palette style --}}
        <form action="{{ route('search.index') }}" method="get" class="hidden lg:block relative" role="search">
            <label for="header-search" class="sr-only">{{ __('messages.search.input_label') }}</label>
            <x-heroicon-o-magnifying-glass class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none" aria-hidden="true" />
            <input id="header-search"
                   x-ref="headerSearch"
                   type="search"
                   name="q"
                   minlength="2"
                   required
                   placeholder="{{ __('messages.search.placeholder') }}"
                   class="w-56 xl:w-64 border border-slate-300 bg-slate-50 pl-9 pr-12 py-2 text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none focus:border-primary focus:bg-white focus:ring-0 transition-colors duration-150">
            <span class="absolute right-2 top-1/2 -translate-y-1/2 hidden xl:inline-flex items-center gap-0.5 pointer-events-none">
                <kbd class="kbd">⌘</kbd>
                <kbd class="kbd">K</kbd>
            </span>
        </form>

        {{-- Mobile toggle --}}
        <button type="button"
                @click="mobileOpen = !mobileOpen"
                :aria-expanded="mobileOpen ? 'true' : 'false'"
                class="lg:hidden p-2 -mr-2 text-slate-900"
                :aria-label="mobileOpen ? '{{ __('messages.nav.close_menu') }}' : '{{ __('messages.nav.open_menu') }}'">
            <x-heroicon-o-bars-3 x-show="!mobileOpen" class="w-6 h-6" />
            <x-heroicon-o-x-mark x-show="mobileOpen" x-cloak class="w-6 h-6" />
        </button>
    </div>

    {{-- Mobile drawer --}}
    <div x-show="mobileOpen" x-cloak class="lg:hidden border-t border-slate-200 bg-white">
        <nav class="container-page py-4 flex flex-col gap-1 text-sm">
            <form action="{{ route('search.index') }}" method="get" class="relative mb-3" role="search">
                <label for="mobile-search" class="sr-only">{{ __('messages.search.input_label') }}</label>
                <x-heroicon-o-magnifying-glass class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" aria-hidden="true" />
                <input id="mobile-search" type="search" name="q" minlength="2" required
                       placeholder="{{ __('messages.search.placeholder') }}"
                       class="w-full border border-slate-300 bg-slate-50 pl-9 pr-3 py-2 text-sm focus:outline-none focus:border-primary focus:bg-white">
            </form>
            @foreach($navItems as $item)
                <a href="{{ $item['href'] }}" class="flex items-center gap-3 px-3 py-2.5 hover:bg-slate-50 transition-colors duration-150">
                    <span class="mono-cap text-slate-400 w-6">{{ $item['code'] }}</span>
                    <span class="font-medium text-slate-900">{{ $item['label'] }}</span>
                </a>
            @endforeach
        </nav>
    </div>

    @include('partials.megamenu')
</header>
