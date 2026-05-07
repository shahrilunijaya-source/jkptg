@extends('layouts.public')

@section('title', __('messages.utility.hubungi') . ' | ' . __('messages.site_name'))

@push('head')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
      integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
      crossorigin="" />
@endpush

@section('content')
<section class="bg-gradient-to-br from-primary to-primary-mute text-white py-12">
    <div class="container-page">
        <div class="flex items-center gap-2 text-jata-yellow text-sm uppercase tracking-wider mb-2">
            <x-heroicon-o-phone class="w-4 h-4" />
            <span>{{ __('messages.utility.hubungi') }}</span>
        </div>
        <h1 class="font-display text-3xl md:text-5xl font-bold mb-3">{{ __('messages.hubungi.heading') }}</h1>
        <p class="text-white/85 max-w-2xl">{{ __('messages.hubungi.help') }}</p>
    </div>
</section>

<x-breadcrumb :items="[['label' => __('messages.utility.hubungi')]]" />

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
                    <div id="map" style="width: 100%; height: 320px;" aria-label="{{ __('messages.hubungi.map_label') }}"></div>
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

@if($hq && $hq->lat && $hq->lng)
@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin=""></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const map = L.map('map').setView([{{ $hq->lat }}, {{ $hq->lng }}], 16);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
            maxZoom: 19,
        }).addTo(map);
        L.marker([{{ $hq->lat }}, {{ $hq->lng }}])
            .addTo(map)
            .bindPopup(@json($hq->name . ' - ' . $hq->address))
            .openPopup();
    });
</script>
@endpush
@endif
