@extends('layouts.public')

@section('title', __('messages.nav.perkhidmatan') . ' | ' . __('messages.site_name'))

@section('content')
{{-- Statement band --}}
<section class="border-b border-slate-200 bg-white">
    <div class="container-page py-12 md:py-16">
        <div class="mono-cap text-slate-500 mb-4">02 · {{ __('messages.nav.perkhidmatan') }}</div>
        <h1 class="text-[36px] md:text-[48px] font-bold text-slate-900 leading-[1.05] tracking-tight mb-4 max-w-3xl">
            {{ __('messages.megamenu.tagline') }}
        </h1>
        <p class="text-[16px] text-slate-600 leading-relaxed max-w-2xl">{{ __('messages.persona.services_help') }}</p>
    </div>
</section>

<x-breadcrumb :items="[['label' => __('messages.nav.perkhidmatan')]]" />

<section class="bg-slate-50 border-b border-slate-200">
    <div class="container-page py-12 md:py-16">
        @if($services->isEmpty())
            <x-state.empty icon="heroicon-o-archive-box-x-mark" :title="__('messages.states.empty.title')" tone="warning" />
        @else
            @php $globalIndex = 1; @endphp
            @foreach($categories as $cat)
                @php $catServices = $services->where('category', $cat); @endphp
                <div class="mb-14 last:mb-0">
                    <div class="flex items-baseline justify-between mb-5 pb-3 border-b border-slate-200">
                        <h2 class="mono-cap text-slate-900 text-[12px]">
                            <span class="text-slate-400 mr-2">CAT-{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                            {{ ucfirst($cat) }}
                        </h2>
                        <span class="mono-meta">{{ $catServices->count() }} {{ app()->getLocale() === 'ms' ? 'item' : 'items' }}</span>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-px bg-slate-200 border border-slate-200">
                        @foreach($catServices as $service)
                            <a href="{{ route('service.show', $service->slug) }}"
                               class="group bg-white p-5 transition-colors duration-150 hover:bg-slate-50 flex flex-col gap-2.5 min-h-[164px]">
                                <div class="flex items-center justify-between">
                                    <span class="mono-cap text-slate-400">SVC-{{ str_pad($globalIndex, 3, '0', STR_PAD_LEFT) }}</span>
                                    @if($service->processing_days)
                                        <span class="mono-meta tabular-nums text-slate-500">{{ $service->processing_days }}{{ app()->getLocale() === 'ms' ? 'h' : 'd' }}</span>
                                    @endif
                                </div>
                                <h3 class="font-semibold text-slate-900 group-hover:text-primary text-[15px] leading-snug transition-colors">{{ $service->name }}</h3>
                                <p class="text-[13px] text-slate-500 line-clamp-2 leading-snug">{{ $service->summary }}</p>
                                <div class="mt-auto pt-2 flex items-center justify-end">
                                    <span class="text-slate-300 group-hover:text-primary group-hover:translate-x-0.5 transition" aria-hidden="true">→</span>
                                </div>
                            </a>
                            @php $globalIndex++; @endphp
                        @endforeach
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</section>
@endsection
