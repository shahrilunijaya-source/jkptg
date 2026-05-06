@extends('layouts.public')

@section('title', __('messages.hubungi.hq') . ' | ' . __('messages.site_name'))

@push('head')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
      integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
      crossorigin="" />
@endpush

@section('content')
<x-statement-band :label="__('messages.hubungi.hq')" :title="$hq->name" :subtitle="__('messages.hubungi.hq_help')" />

<x-breadcrumb :items="[
    ['label' => __('messages.utility.hubungi'), 'href' => route('hubungi.index')],
    ['label' => __('messages.hubungi.hq')],
]" />

<section class="py-12">
    <div class="container-page grid grid-cols-1 lg:grid-cols-2 gap-6">
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

        <div class="rounded-lg border overflow-hidden bg-white" style="min-height: 360px">
            @if($hq->lat && $hq->lng)
                <div id="map" style="width: 100%; height: 360px;" aria-label="{{ __('messages.hubungi.map_label') }}"></div>
            @else
                <x-state.empty icon="heroicon-o-map" :title="__('messages.hubungi.no_map')" tone="warning" />
            @endif
        </div>
    </div>
</section>
@endsection

@if($hq->lat && $hq->lng)
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
