@extends('layouts.public')

@section('title', __('messages.utility.hubungi') . ' | ' . __('messages.site_name'))

@push('head')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
      integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
      crossorigin="" />
@endpush

@section('content')
{{-- Statement band --}}
<section class="border-b border-slate-200 bg-white">
    <div class="container-page py-12 md:py-16">
        <div class="mono-cap text-slate-500 mb-4">06 · {{ __('messages.utility.hubungi') }}</div>
        <h1 class="text-[36px] md:text-[48px] font-bold text-slate-900 leading-[1.05] tracking-tight mb-4 max-w-3xl">
            {{ __('messages.hubungi.heading') }}
        </h1>
        <p class="text-[16px] text-slate-600 leading-relaxed max-w-2xl">{{ __('messages.hubungi.help') }}</p>
        <div class="mt-6 flex flex-wrap gap-3">
            <a href="{{ route('hubungi.aduan') }}" class="btn-primary">
                <span>{{ __('messages.utility.aduan') }}</span>
                <span aria-hidden="true">→</span>
            </a>
            @if($branches->count())
                <a href="#cawangan" class="btn-secondary">
                    {{ __('messages.hubungi.branches') }} ({{ $branches->count() }})
                </a>
            @endif
        </div>
    </div>
</section>

<x-breadcrumb :items="[['label' => __('messages.utility.hubungi')]]" />

@if($hq)
<section class="bg-slate-50 border-b border-slate-200">
    <div class="container-page py-12 md:py-16">
        <div class="flex items-baseline justify-between mb-6 pb-3 border-b border-slate-200">
            <h2 class="mono-cap text-slate-900 text-[12px]">
                <span class="text-slate-400 mr-2">HQ-01</span>
                {{ __('messages.hubungi.hq') }}
            </h2>
            <span class="mono-meta">{{ $hq->name }}</span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-px bg-slate-200 border border-slate-200">
            {{-- Address + contact data sheet --}}
            <div class="lg:col-span-5 bg-white p-7">
                <dl class="space-y-5 text-[14px]">
                    <div>
                        <dt class="mono-cap text-slate-500 mb-1.5">{{ app()->getLocale() === 'ms' ? 'Alamat' : 'Address' }}</dt>
                        <dd><address class="not-italic text-slate-700 leading-relaxed">{{ $hq->address }}</address></dd>
                    </div>
                    @if($hq->phone)
                        <div>
                            <dt class="mono-cap text-slate-500 mb-1.5">{{ app()->getLocale() === 'ms' ? 'Telefon' : 'Phone' }}</dt>
                            <dd class="font-mono tabular-nums text-slate-900">{{ $hq->phone }}</dd>
                            @if($hq->fax)<dd class="font-mono tabular-nums text-slate-500 mt-0.5">FAKS · {{ $hq->fax }}</dd>@endif
                        </div>
                    @endif
                    @if($hq->email)
                        <div>
                            <dt class="mono-cap text-slate-500 mb-1.5">{{ app()->getLocale() === 'ms' ? 'Emel' : 'Email' }}</dt>
                            <dd><a href="mailto:{{ $hq->email }}" class="font-mono text-primary hover:underline">{{ $hq->email }}</a></dd>
                        </div>
                    @endif
                    @if($hq->opening_hours)
                        <div>
                            <dt class="mono-cap text-slate-500 mb-1.5">{{ app()->getLocale() === 'ms' ? 'Waktu Operasi' : 'Operating Hours' }}</dt>
                            <dd class="text-slate-700 leading-snug">{{ $hq->opening_hours }}</dd>
                        </div>
                    @endif
                </dl>
            </div>

            {{-- Map --}}
            <div class="lg:col-span-7 bg-white">
                @if($hq->lat && $hq->lng)
                    <div id="map" class="w-full h-[400px] lg:h-[480px]" aria-label="{{ __('messages.hubungi.map_label') }}"></div>
                @else
                    <x-state.empty icon="heroicon-o-map" :title="__('messages.hubungi.no_map')" tone="warning" />
                @endif
            </div>
        </div>
    </div>
</section>
@endif

@if($branches->count())
<section id="cawangan" class="bg-white border-b border-slate-200">
    <div class="container-page py-12 md:py-16">
        <div class="flex items-baseline justify-between mb-6 pb-3 border-b border-slate-200">
            <h2 class="mono-cap text-slate-900 text-[12px]">
                <span class="text-slate-400 mr-2">CAW</span>
                {{ __('messages.hubungi.branches') }}
            </h2>
            <span class="mono-meta">{{ trans_choice('messages.hubungi.branch_count', $branches->count(), ['count' => $branches->count()]) }}</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-px bg-slate-200 border border-slate-200">
            @foreach($branches as $b)
                <article class="bg-white p-5 hover:bg-slate-50 transition-colors duration-150">
                    <div class="flex items-baseline justify-between mb-3">
                        <span class="mono-cap text-slate-400">CAW-{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                        <span class="mono-cap text-slate-700">{{ strtoupper($b->state) }}</span>
                    </div>
                    <h3 class="font-semibold text-slate-900 text-[15px] leading-snug mb-2">{{ $b->name }}</h3>
                    <address class="not-italic text-[13px] text-slate-600 mb-3 leading-relaxed line-clamp-3">{{ $b->address }}</address>
                    <dl class="text-[13px] space-y-1 text-slate-700">
                        @if($b->phone)
                            <div class="flex gap-2">
                                <dt class="mono-cap text-slate-400 w-10 shrink-0 pt-0.5">TEL</dt>
                                <dd class="font-mono tabular-nums">{{ $b->phone }}</dd>
                            </div>
                        @endif
                        @if($b->email)
                            <div class="flex gap-2">
                                <dt class="mono-cap text-slate-400 w-10 shrink-0 pt-0.5">EMEL</dt>
                                <dd><a href="mailto:{{ $b->email }}" class="font-mono text-primary hover:underline truncate inline-block max-w-full">{{ $b->email }}</a></dd>
                            </div>
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
