@extends('layouts.public')

@section('title', __('messages.nav.perkhidmatan') . ' | ' . __('messages.site_name'))

@php
$categoryImages = [
    'tanah'   => 'https://images.unsplash.com/photo-1531819318554-84abdf082937?auto=format&fit=crop&w=800&q=80',
    'pajakan' => 'https://images.unsplash.com/photo-1680243032601-6b1ca903bc2a?auto=format&fit=crop&w=800&q=80',
    'lesen'   => 'https://images.unsplash.com/photo-1695169152266-d9ac86fab9c5?auto=format&fit=crop&w=800&q=80',
    'strata'  => 'https://images.unsplash.com/photo-1611924779080-d20389c1f56c?auto=format&fit=crop&w=800&q=80',
];
@endphp

@section('content')
<section class="bg-gradient-to-br from-primary to-primary-mute text-white py-14 md:py-20">
    <div class="container-page">
        <div class="flex items-center gap-2 text-jata-yellow text-sm uppercase tracking-wider mb-2">
            <x-heroicon-o-document-text class="w-4 h-4" />
            <span>{{ __('messages.nav.perkhidmatan') }}</span>
        </div>
        <h1 class="font-display text-3xl md:text-5xl font-bold mb-3">{{ __('messages.megamenu.tagline') }}</h1>
        <p class="text-white/85 max-w-2xl">{{ __('messages.persona.services_help') }}</p>
    </div>
</section>

<x-breadcrumb :items="[['label' => __('messages.nav.perkhidmatan')]]" />

<section class="py-10">
    <div class="container-page">
        @if($services->isEmpty())
            <x-state.empty icon="heroicon-o-archive-box-x-mark" :title="__('messages.states.empty.title')" tone="warning" />
        @else
            @foreach($categories as $cat)
                @php $catServices = $services->where('category', $cat); @endphp
                <h2 class="font-display text-xl font-bold text-primary mb-4 capitalize flex items-center gap-2">
                    <span class="w-1.5 h-6 bg-jata-yellow rounded"></span>
                    {{ ucfirst($cat) }}
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
                    @foreach($catServices as $service)
                        @php $cardImg = $categoryImages[$service->category] ?? null; @endphp
                        <a href="{{ route('service.show', $service->slug) }}"
                           class="group reveal-on-scroll rounded-lg border bg-white overflow-hidden hover:border-primary hover-lift"
                           style="--reveal-delay:{{ $loop->index * 40 }}ms">
                            @if($cardImg)
                                <div class="h-36 bg-cover bg-center transition-transform duration-500 group-hover:scale-105"
                                     style="background-image: url('{{ $cardImg }}')"></div>
                            @endif
                            <div class="p-5">
                                <h3 class="font-semibold text-primary group-hover:underline mb-1">{{ $service->name }}</h3>
                                <p class="text-sm text-gray-600 line-clamp-2">{{ $service->summary }}</p>
                                @if($service->processing_days)
                                    <p class="text-xs text-gray-500 mt-3 flex items-center gap-1">
                                        <x-heroicon-o-clock class="w-3.5 h-3.5" />
                                        {{ $service->processing_days }} {{ app()->getLocale() === 'ms' ? 'hari' : 'days' }}
                                    </p>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>
            @endforeach
        @endif
    </div>
</section>
@endsection
