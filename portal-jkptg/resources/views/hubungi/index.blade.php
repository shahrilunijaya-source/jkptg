@extends('layouts.public')

@section('title', __('messages.utility.hubungi') . ' | ' . __('messages.site_name'))

@push('head')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
      integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
      crossorigin="" />
@endpush

@section('content')
<x-statement-band
    icon="phone"
    :label="__('messages.utility.hubungi')"
    :title="__('messages.hubungi.heading')"
    :subtitle="__('messages.hubungi.help')">
    <a href="{{ route('hubungi.aduan') }}" class="btn-primary">
        <x-heroicon-o-chat-bubble-left-right class="w-4 h-4" />
        <span>{{ __('messages.utility.aduan') }}</span>
    </a>
    @if($branches->count())
        <a href="#cawangan" class="btn-secondary">
            <x-heroicon-o-map-pin class="w-4 h-4" />
            <span>{{ __('messages.hubungi.branches') }} ({{ $branches->count() }})</span>
        </a>
    @endif
</x-statement-band>

<x-breadcrumb :items="[['label' => __('messages.utility.hubungi')]]" />

@if($hq)
<section class="bg-canvas-mute border-b border-slate-200">
    <div class="container-page py-12 md:py-16">
        <div class="max-w-2xl mb-8">
            <span class="eyebrow-muted">{{ __('messages.hubungi.hq') }}</span>
            <h2 class="text-[24px] md:text-[28px] font-bold text-canvas-ink leading-tight tracking-tight mt-2">{{ $hq->name }}</h2>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            {{-- Address + contact --}}
            <div class="lg:col-span-5 bg-white border border-slate-200 rounded-sm p-6 md:p-7">
                <dl class="space-y-5 text-[14px]">
                    <div class="flex items-start gap-3">
                        <span class="icon-medallion w-9 h-9 flex-shrink-0">
                            <x-heroicon-o-map-pin class="w-4 h-4" />
                        </span>
                        <div>
                            <dt class="text-[12px] uppercase tracking-[0.12em] font-semibold text-slate-500 mb-1">{{ app()->getLocale() === 'ms' ? 'Alamat' : 'Address' }}</dt>
                            <dd><address class="not-italic text-slate-700 leading-relaxed">{{ $hq->address }}</address></dd>
                        </div>
                    </div>
                    @if($hq->phone)
                        <div class="flex items-start gap-3">
                            <span class="icon-medallion w-9 h-9 flex-shrink-0">
                                <x-heroicon-o-phone class="w-4 h-4" />
                            </span>
                            <div>
                                <dt class="text-[12px] uppercase tracking-[0.12em] font-semibold text-slate-500 mb-1">{{ app()->getLocale() === 'ms' ? 'Telefon' : 'Phone' }}</dt>
                                <dd class="text-slate-800 font-semibold">{{ $hq->phone }}</dd>
                                @if($hq->fax)<dd class="text-slate-500 text-[12px] mt-0.5">{{ app()->getLocale() === 'ms' ? 'Faks' : 'Fax' }}: {{ $hq->fax }}</dd>@endif
                            </div>
                        </div>
                    @endif
                    @if($hq->email)
                        <div class="flex items-start gap-3">
                            <span class="icon-medallion w-9 h-9 flex-shrink-0">
                                <x-heroicon-o-envelope class="w-4 h-4" />
                            </span>
                            <div>
                                <dt class="text-[12px] uppercase tracking-[0.12em] font-semibold text-slate-500 mb-1">{{ app()->getLocale() === 'ms' ? 'Emel' : 'Email' }}</dt>
                                <dd><a href="mailto:{{ $hq->email }}" class="text-primary font-semibold hover:underline">{{ $hq->email }}</a></dd>
                            </div>
                        </div>
                    @endif
                    @if($hq->opening_hours)
                        <div class="flex items-start gap-3">
                            <span class="icon-medallion w-9 h-9 flex-shrink-0">
                                <x-heroicon-o-clock class="w-4 h-4" />
                            </span>
                            <div>
                                <dt class="text-[12px] uppercase tracking-[0.12em] font-semibold text-slate-500 mb-1">{{ app()->getLocale() === 'ms' ? 'Waktu Operasi' : 'Operating Hours' }}</dt>
                                <dd class="text-slate-700 leading-snug">{{ $hq->opening_hours }}</dd>
                            </div>
                        </div>
                    @endif
                </dl>
            </div>

            {{-- Map --}}
            <div class="lg:col-span-7 bg-white border border-slate-200 rounded-sm overflow-hidden">
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
        <div class="flex items-end justify-between mb-8 flex-wrap gap-3">
            <div class="max-w-2xl">
                <span class="eyebrow-muted">{{ __('messages.hubungi.branches') }}</span>
                <h2 class="text-[24px] md:text-[28px] font-bold text-canvas-ink leading-tight tracking-tight mt-2">
                    {{ app()->getLocale() === 'ms' ? 'Cawangan Negeri' : 'State Branches' }}
                </h2>
                <p class="text-[14px] text-slate-600 mt-1">{{ trans_choice('messages.hubungi.branch_count', $branches->count(), ['count' => $branches->count()]) }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($branches as $b)
                <article class="civic-card flex flex-col">
                    <div class="flex items-center justify-between mb-3">
                        <span class="icon-medallion w-9 h-9">
                            <x-heroicon-o-map-pin class="w-4 h-4" />
                        </span>
                        <span class="text-[11px] uppercase tracking-[0.14em] font-semibold text-bronze">{{ $b->state }}</span>
                    </div>
                    <h3 class="text-[16px] font-bold text-canvas-ink leading-snug mb-2">{{ $b->name }}</h3>
                    <address class="not-italic text-[13px] text-slate-600 mb-4 leading-relaxed line-clamp-3">{{ $b->address }}</address>
                    <dl class="text-[13px] space-y-1.5 text-slate-700 mt-auto pt-3 border-t border-slate-100">
                        @if($b->phone)
                            <div class="flex items-center gap-2">
                                <x-heroicon-o-phone class="w-3.5 h-3.5 text-slate-400" aria-hidden="true" />
                                <span>{{ $b->phone }}</span>
                            </div>
                        @endif
                        @if($b->email)
                            <div class="flex items-center gap-2 min-w-0">
                                <x-heroicon-o-envelope class="w-3.5 h-3.5 text-slate-400 flex-shrink-0" aria-hidden="true" />
                                <a href="mailto:{{ $b->email }}" class="text-primary hover:underline truncate">{{ $b->email }}</a>
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
