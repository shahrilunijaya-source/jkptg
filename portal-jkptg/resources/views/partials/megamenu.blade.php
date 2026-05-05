@php
  $featured = \App\Models\Service::where('active', true)->orderBy('sort')->limit(3)->get();
  $allServices = \App\Models\Service::where('active', true)->orderBy('sort')->get();
  $categories = $allServices->pluck('category')->filter()->unique()->values();
@endphp
<div id="megamenu-panel"
     x-show="megaOpen"
     x-cloak
     x-transition:enter="transition ease-out duration-150"
     x-transition:enter-start="opacity-0 -translate-y-1"
     x-transition:enter-end="opacity-100 translate-y-0"
     @click.outside="megaOpen = false"
     class="absolute left-0 right-0 bg-white border-t border-b shadow-xl z-40">
    <div class="container-page py-8">
        <div class="flex items-start justify-between mb-6">
            <div>
                <h2 class="font-display text-2xl font-bold text-primary">{{ __('messages.nav.perkhidmatan') }} JKPTG</h2>
                <p class="text-gray-600 text-sm mt-1">{{ __('messages.megamenu.tagline') }}</p>
            </div>
            <button type="button" @click="megaOpen = false" class="p-1 hover:bg-gray-100 rounded" aria-label="{{ __('messages.nav.close_menu') }}">
                <x-heroicon-o-x-mark class="w-5 h-5" />
            </button>
        </div>

        <div class="text-sm uppercase font-semibold text-gray-500 tracking-wider mb-3">{{ __('messages.megamenu.most_popular') }}</div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            @foreach($featured as $service)
                <a href="#" class="group rounded-lg border hover:border-primary hover:shadow-md transition overflow-hidden">
                    <div class="h-32 bg-gradient-to-br from-primary to-primary-light flex items-center justify-center text-white">
                        <x-heroicon-o-document-text class="w-12 h-12" />
                    </div>
                    <div class="p-4">
                        <div class="font-semibold text-primary group-hover:underline">{{ $service->name }}</div>
                        <p class="text-xs text-gray-600 mt-1 line-clamp-2">{{ $service->summary }}</p>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="flex flex-wrap items-center gap-x-6 gap-y-2 text-sm border-t pt-4">
            <span class="text-gray-500">{{ __('messages.megamenu.categories') }}:</span>
            @foreach($categories as $cat)
                <a href="#" class="text-primary hover:underline capitalize">{{ $cat }}</a>
            @endforeach
            <a href="#" class="ml-auto font-semibold text-primary hover:underline flex items-center gap-1">
                {{ __('messages.megamenu.all_services') }}
                <x-heroicon-o-arrow-right class="w-4 h-4" />
            </a>
        </div>
    </div>
</div>
