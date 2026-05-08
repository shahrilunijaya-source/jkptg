@php
  $featured = \App\Models\Service::where('active', true)->orderBy('sort')->limit(3)->get();
  $allServices = \App\Models\Service::where('active', true)->orderBy('sort')->get();
  $categories = $allServices->pluck('category')->filter()->unique()->values();
  $megaCardImages = [
      'tanah'   => 'https://images.unsplash.com/photo-1531819318554-84abdf082937?auto=format&fit=crop&w=800&q=80',
      'pajakan' => 'https://images.unsplash.com/photo-1680243032601-6b1ca903bc2a?auto=format&fit=crop&w=800&q=80',
      'lesen'   => 'https://images.unsplash.com/photo-1695169152266-d9ac86fab9c5?auto=format&fit=crop&w=800&q=80',
      'strata'  => 'https://images.unsplash.com/photo-1611924779080-d20389c1f56c?auto=format&fit=crop&w=800&q=80',
  ];
  $megaSlugImages = [
      'pusaka-bukan-islam' => 'https://images.unsplash.com/photo-1589829545856-d10d557cf95f?auto=format&fit=crop&w=800&q=80',
  ];
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
                <a href="{{ route('service.show', $service->slug) }}" class="group rounded-lg border hover:border-primary hover:shadow-md transition overflow-hidden">
                    @php $cardImg = $megaSlugImages[$service->slug] ?? $megaCardImages[$service->category] ?? null; @endphp
                    <div class="h-32 relative overflow-hidden">
                        @if($cardImg)
                            <div class="absolute inset-0 bg-cover bg-center transition-transform duration-500 group-hover:scale-105"
                                 style="background-image: url('{{ $cardImg }}')"></div>
                            <div class="absolute inset-0 bg-primary/60"></div>
                        @else
                            <div class="absolute inset-0 bg-gradient-to-br from-primary to-primary-light"></div>
                        @endif
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
                <a href="{{ route('service.index', ['kategori' => $cat]) }}" class="text-primary hover:underline capitalize">{{ $cat }}</a>
            @endforeach
            <a href="{{ route('service.index') }}" class="ml-auto font-semibold text-primary hover:underline flex items-center gap-1">
                {{ __('messages.megamenu.all_services') }}
                <x-heroicon-o-arrow-right class="w-4 h-4" />
            </a>
        </div>
    </div>
</div>
