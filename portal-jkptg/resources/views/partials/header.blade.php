<header class="border-b bg-white sticky top-0 z-30 shadow-sm no-print" x-data="{ megaOpen: false, mobileOpen: false }" @keydown.escape.window="megaOpen = false; mobileOpen = false">
    <div class="container-page flex items-center justify-between py-3">
        <a href="{{ route('home') }}" class="flex items-center gap-3" aria-label="{{ __('messages.site_name') }}">
            <img src="{{ asset('images/jata-negara.png') }}" alt="Jata Negara" class="w-12 h-12 object-contain flex-shrink-0">
            <img src="{{ asset('images/logo-jkptg.png') }}" alt="JKPTG" class="h-12 w-auto object-contain hidden sm:block">
            <div class="sm:hidden">
                <div class="font-display font-bold text-primary leading-tight">JKPTG</div>
            </div>
        </a>

        <nav aria-label="{{ __('messages.nav.utama') }}" class="hidden lg:flex items-center gap-1 text-sm font-medium">
            <a href="{{ route('home') }}" class="px-3 py-2 rounded hover:bg-primary-pale">{{ __('messages.nav.utama') }}</a>
            <button type="button"
                    @click.stop="megaOpen = !megaOpen"
                    :aria-expanded="megaOpen ? 'true' : 'false'"
                    aria-haspopup="true"
                    aria-controls="megamenu-panel"
                    class="px-3 py-2 rounded hover:bg-primary-pale flex items-center gap-1"
                    :class="{ 'bg-primary-pale text-primary': megaOpen }">
                {{ __('messages.nav.perkhidmatan') }}
                <x-heroicon-o-chevron-down class="w-4 h-4" />
            </button>
            <a href="{{ route('panduan.index') }}" class="px-3 py-2 rounded hover:bg-primary-pale">{{ __('messages.nav.panduan') }}</a>
            <a href="{{ route('korporat.index') }}" class="px-3 py-2 rounded hover:bg-primary-pale">{{ __('messages.nav.korporat') }}</a>
            <a href="{{ route('sumber.index') }}" class="px-3 py-2 rounded hover:bg-primary-pale">{{ __('messages.nav.sumber') }}</a>
            <a href="{{ route('hubungi.index') }}" class="px-3 py-2 rounded hover:bg-primary-pale">{{ __('messages.nav.hubungi') }}</a>
            <form action="{{ route('search.index') }}" method="get" class="ml-2 relative" role="search">
                <label for="header-search" class="sr-only">{{ __('messages.search.input_label') }}</label>
                <input id="header-search" type="search" name="q" minlength="2" required
                       placeholder="{{ __('messages.search.placeholder') }}"
                       class="w-44 xl:w-56 border border-gray-300 rounded-full pl-9 pr-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                <x-heroicon-o-magnifying-glass class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" aria-hidden="true" />
            </form>
            @auth
                <a href="{{ url('/admin') }}" class="ml-2 btn-primary !px-4 !py-2">{{ __('messages.nav.log_masuk') }}</a>
            @else
                <a href="{{ url('/admin/login') }}" class="ml-2 btn-primary !px-4 !py-2">{{ __('messages.nav.log_masuk') }}</a>
            @endauth
        </nav>

        <button type="button"
                @click="mobileOpen = !mobileOpen"
                :aria-expanded="mobileOpen ? 'true' : 'false'"
                class="lg:hidden p-2"
                :aria-label="mobileOpen ? '{{ __('messages.nav.close_menu') }}' : '{{ __('messages.nav.open_menu') }}'">
            <x-heroicon-o-bars-3 x-show="!mobileOpen" class="w-6 h-6" />
            <x-heroicon-o-x-mark x-show="mobileOpen" x-cloak class="w-6 h-6" />
        </button>
    </div>

    <div x-show="mobileOpen" x-cloak class="lg:hidden border-t bg-white shadow-md">
        <nav class="container-page py-3 flex flex-col gap-1 text-sm font-medium">
            <form action="{{ route('search.index') }}" method="get" class="relative mb-2" role="search">
                <label for="mobile-search" class="sr-only">{{ __('messages.search.input_label') }}</label>
                <input id="mobile-search" type="search" name="q" minlength="2" required
                       placeholder="{{ __('messages.search.placeholder') }}"
                       class="w-full border border-gray-300 rounded-full pl-9 pr-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                <x-heroicon-o-magnifying-glass class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" aria-hidden="true" />
            </form>
            <a href="{{ route('home') }}" class="px-3 py-2 rounded hover:bg-primary-pale">{{ __('messages.nav.utama') }}</a>
            <a href="{{ route('service.index') }}" class="px-3 py-2 rounded hover:bg-primary-pale">{{ __('messages.nav.perkhidmatan') }}</a>
            <a href="{{ route('panduan.index') }}" class="px-3 py-2 rounded hover:bg-primary-pale">{{ __('messages.nav.panduan') }}</a>
            <a href="{{ route('korporat.index') }}" class="px-3 py-2 rounded hover:bg-primary-pale">{{ __('messages.nav.korporat') }}</a>
            <a href="{{ route('sumber.index') }}" class="px-3 py-2 rounded hover:bg-primary-pale">{{ __('messages.nav.sumber') }}</a>
            <a href="{{ route('hubungi.index') }}" class="px-3 py-2 rounded hover:bg-primary-pale">{{ __('messages.nav.hubungi') }}</a>
            <a href="{{ url('/admin/login') }}" class="btn-primary mt-2">{{ __('messages.nav.log_masuk') }}</a>
        </nav>
    </div>

    @include('partials.megamenu')
</header>
