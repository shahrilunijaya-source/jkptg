@extends('layouts.public')

@section('title', __('messages.utility.hubungi') . ' | ' . __('messages.site_name'))

@section('content')
<x-breadcrumb :items="[['label' => __('messages.utility.hubungi')]]" />

<section class="bg-gradient-to-br from-primary to-primary-mute text-white py-12">
    <div class="container-page flex items-center justify-between gap-6">
        <div>
            <div class="flex items-center gap-2 text-jata-yellow text-sm uppercase tracking-wider mb-2">
                <x-heroicon-o-phone class="w-4 h-4" />
                <span>{{ __('messages.utility.hubungi') }}</span>
            </div>
            <h1 class="font-display text-3xl md:text-5xl font-bold mb-3">{{ __('messages.hubungi.heading') }}</h1>
            <p class="text-white/85 max-w-2xl">{{ __('messages.hubungi.help') }}</p>
        </div>
        <img src="{{ asset('images/logo-jkptg.png') }}" alt="JKPTG"
             class="hidden md:block h-32 lg:h-40 w-auto object-contain flex-shrink-0 drop-shadow-lg">
    </div>
</section>

@if($hq)
<section class="py-12">
    <div class="container-page">
        <h2 class="font-display text-2xl font-bold text-primary mb-1">{{ __('messages.hubungi.hq') }}</h2>
        <p class="text-gray-600 text-sm mb-6">{{ $hq->name }}</p>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white border rounded-lg p-6 space-y-4">
                <div class="flex items-start gap-3">
                    <x-heroicon-o-map-pin class="w-5 h-5 text-primary flex-shrink-0 mt-0.5" />
                    <address class="not-italic text-sm text-gray-700 leading-relaxed">{{ $hq->address }}</address>
                </div>
                @if($hq->phone)
                    <div class="flex items-start gap-3">
                        <x-heroicon-o-phone class="w-5 h-5 text-primary flex-shrink-0 mt-0.5" />
                        <div class="text-sm">
                            <div class="font-semibold">{{ $hq->phone }}</div>
                            @if($hq->fax)<div class="text-gray-500 text-xs">Faks: {{ $hq->fax }}</div>@endif
                        </div>
                    </div>
                @endif
                @if($hq->email)
                    <div class="flex items-start gap-3">
                        <x-heroicon-o-envelope class="w-5 h-5 text-primary flex-shrink-0 mt-0.5" />
                        <a href="mailto:{{ $hq->email }}" class="text-primary hover:underline text-sm">{{ $hq->email }}</a>
                    </div>
                @endif
                @if($hq->opening_hours)
                    <div class="flex items-start gap-3">
                        <x-heroicon-o-clock class="w-5 h-5 text-primary flex-shrink-0 mt-0.5" />
                        <p class="text-sm text-gray-700">{{ $hq->opening_hours }}</p>
                    </div>
                @endif
            </div>

            <div class="rounded-lg border overflow-hidden bg-white" style="min-height: 320px">
                @if($hq->lat && $hq->lng)
                    <iframe
                        src="https://www.google.com/maps?q={{ $hq->lat }},{{ $hq->lng }}&z=16&hl={{ app()->getLocale() }}&output=embed"
                        title="{{ __('messages.hubungi.map_label') }}"
                        aria-label="{{ __('messages.hubungi.map_label') }}"
                        style="width: 100%; height: 320px; border: 0;"
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        allowfullscreen></iframe>
                @else
                    <x-state.empty icon="heroicon-o-map" :title="__('messages.hubungi.no_map')" tone="warning" />
                @endif
            </div>
        </div>
    </div>
</section>
@endif

@if($branches->count())
<section class="bg-gray-50 py-12">
    <div class="container-page">
        <h2 class="font-display text-2xl font-bold text-primary mb-1">{{ __('messages.hubungi.branches') }}</h2>
        <p class="text-gray-600 text-sm mb-6">{{ trans_choice('messages.hubungi.branch_count', $branches->count(), ['count' => $branches->count()]) }}</p>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($branches as $b)
                <article class="bg-white border rounded-lg p-5 hover:shadow transition">
                    <div class="flex items-center gap-2 text-xs text-jata-red font-semibold uppercase tracking-wider mb-2">
                        <x-heroicon-o-map-pin class="w-3.5 h-3.5" />
                        <span>{{ $b->state }}</span>
                    </div>
                    <h3 class="font-display font-bold text-primary mb-2">{{ $b->name }}</h3>
                    <address class="not-italic text-sm text-gray-700 mb-3 leading-relaxed">{{ $b->address }}</address>
                    <dl class="text-sm space-y-1 text-gray-700">
                        @if($b->phone)
                            <div class="flex items-center gap-2"><x-heroicon-o-phone class="w-3.5 h-3.5 text-primary" />{{ $b->phone }}</div>
                        @endif
                        @if($b->email)
                            <div class="flex items-center gap-2"><x-heroicon-o-envelope class="w-3.5 h-3.5 text-primary" /><a href="mailto:{{ $b->email }}" class="text-primary hover:underline">{{ $b->email }}</a></div>
                        @endif
                    </dl>
                </article>
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection

