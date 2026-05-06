@extends('layouts.public')

@section('title', __('messages.nav.perkhidmatan') . ' | ' . __('messages.site_name'))

@php
    $catIcons = [
        'tanah' => 'map',
        'pusaka' => 'building-library',
        'pajakan' => 'document-text',
        'penyerahan' => 'arrow-uturn-left',
        'lesen' => 'document-magnifying-glass',
        'strata' => 'building-office-2',
    ];
    $serviceIconPool = ['document-text','map','home-modern','banknotes','document-magnifying-glass','scale','building-library','clipboard-document-list'];
@endphp

@section('content')
<x-statement-band
    icon="document-text"
    :label="__('messages.nav.perkhidmatan')"
    :title="__('messages.megamenu.tagline')"
    :subtitle="__('messages.persona.services_help')" />

<x-breadcrumb :items="[['label' => __('messages.nav.perkhidmatan')]]" />

<section class="bg-canvas-mute border-b border-slate-200">
    <div class="container-page py-12 md:py-16">
        @if($services->isEmpty())
            <x-state.empty icon="heroicon-o-archive-box-x-mark" :title="__('messages.states.empty.title')" tone="warning" />
        @else
            @php $globalIndex = 0; @endphp
            @foreach($categories as $cat)
                @php $catServices = $services->where('category', $cat); @endphp
                <div class="mb-14 last:mb-0">
                    <div class="flex items-end justify-between mb-6 flex-wrap gap-3">
                        <div class="flex items-center gap-3">
                            <span class="icon-medallion w-9 h-9">
                                <x-dynamic-component :component="'heroicon-o-' . ($catIcons[$cat] ?? 'document-text')" class="w-4 h-4" />
                            </span>
                            <div>
                                <span class="eyebrow-muted">{{ app()->getLocale() === 'ms' ? 'Kategori' : 'Category' }}</span>
                                <h2 class="text-[22px] md:text-[24px] font-bold text-canvas-ink leading-tight tracking-tight mt-1 capitalize">{{ $cat }}</h2>
                            </div>
                        </div>
                        <span class="text-[13px] text-slate-500">{{ $catServices->count() }} {{ app()->getLocale() === 'ms' ? 'perkhidmatan' : 'services' }}</span>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                        @foreach($catServices as $service)
                            @php $iconName = $serviceIconPool[$globalIndex % count($serviceIconPool)]; $globalIndex++; @endphp
                            <a href="{{ route('service.show', $service->slug) }}"
                               class="civic-card flex flex-col group">
                                <span class="icon-medallion mb-4">
                                    <x-dynamic-component :component="'heroicon-o-' . $iconName" class="w-5 h-5" />
                                </span>
                                <h3 class="text-[16px] font-bold text-canvas-ink leading-snug mb-2 group-hover:text-primary transition-colors">{{ $service->name }}</h3>
                                <p class="text-[13.5px] text-slate-600 leading-relaxed line-clamp-3 mb-4 flex-1">{{ $service->summary }}</p>
                                <div class="flex items-center justify-between text-[13px] mt-auto pt-3 border-t border-slate-100">
                                    @if($service->processing_days)
                                        <span class="text-slate-500 flex items-center gap-1">
                                            <x-heroicon-o-clock class="w-3.5 h-3.5" aria-hidden="true" />
                                            {{ $service->processing_days }} {{ app()->getLocale() === 'ms' ? 'hari' : 'days' }}
                                        </span>
                                    @else
                                        <span></span>
                                    @endif
                                    <span class="font-semibold text-primary inline-flex items-center gap-1">
                                        {{ app()->getLocale() === 'ms' ? 'Lihat' : 'View' }}
                                        <x-heroicon-o-arrow-right class="w-3.5 h-3.5 group-hover:translate-x-0.5 transition-transform" />
                                    </span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</section>
@endsection
