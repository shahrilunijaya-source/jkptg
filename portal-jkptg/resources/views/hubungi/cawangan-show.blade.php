@extends('layouts.public')

@section('title', $branch->name . ' | ' . __('messages.site_name'))

@push('head')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
      integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
      crossorigin="" />
@endpush

@section('content')
<x-statement-band :label="$branch->state" :title="$branch->name" compact />

<x-breadcrumb :items="[
    ['label' => __('messages.utility.hubungi'), 'href' => route('hubungi.index')],
    ['label' => __('messages.hubungi.branches'), 'href' => route('hubungi.cawangan')],
    ['label' => $branch->name],
]" />

<section class="py-12">
    <div class="container-page grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white border rounded-lg p-6 space-y-4">
            <div class="flex items-start gap-3">
                <x-heroicon-o-map-pin class="w-5 h-5 text-primary flex-shrink-0 mt-0.5" />
                <address class="not-italic text-sm text-gray-700 leading-relaxed">{{ $branch->address }}</address>
            </div>
            @if($branch->phone)
                <div class="flex items-start gap-3">
                    <x-heroicon-o-phone class="w-5 h-5 text-primary flex-shrink-0 mt-0.5" />
                    <div class="text-sm">
                        <div class="font-semibold">{{ $branch->phone }}</div>
                        @if($branch->fax)<div class="text-gray-500 text-xs">Faks: {{ $branch->fax }}</div>@endif
                    </div>
                </div>
            @endif
            @if($branch->email)
                <div class="flex items-start gap-3">
                    <x-heroicon-o-envelope class="w-5 h-5 text-primary flex-shrink-0 mt-0.5" />
                    <a href="mailto:{{ $branch->email }}" class="text-primary hover:underline text-sm">{{ $branch->email }}</a>
                </div>
            @endif
            @if($branch->opening_hours)
                <div class="flex items-start gap-3">
                    <x-heroicon-o-clock class="w-5 h-5 text-primary flex-shrink-0 mt-0.5" />
                    <p class="text-sm text-gray-700">{{ $branch->opening_hours }}</p>
                </div>
            @endif
        </div>

        <div class="rounded-lg border overflow-hidden bg-white" style="min-height: 360px">
            @if($branch->lat && $branch->lng)
                <div id="map" style="width: 100%; height: 360px;" aria-label="{{ __('messages.hubungi.map_label') }}"></div>
            @else
                <x-state.empty icon="heroicon-o-map" :title="__('messages.hubungi.no_map')" tone="warning" />
            @endif
        </div>
    </div>
</section>

<section class="bg-gray-50 py-8">
    <div class="container-page">
        <a href="{{ route('hubungi.cawangan') }}" class="inline-flex items-center gap-1 text-sm font-semibold text-primary hover:underline">
            <x-heroicon-o-arrow-left class="w-3.5 h-3.5" />
            {{ __('messages.hubungi.back_to_branches') }}
        </a>
    </div>
</section>
@endsection

@if($branch->lat && $branch->lng)
@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin=""></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const map = L.map('map').setView([{{ $branch->lat }}, {{ $branch->lng }}], 14);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
            maxZoom: 19,
        }).addTo(map);
        L.marker([{{ $branch->lat }}, {{ $branch->lng }}])
            .addTo(map)
            .bindPopup(@json($branch->name))
            .openPopup();
    });
</script>
@endpush
@endif
