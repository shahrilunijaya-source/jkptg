@extends('layouts.public')

@section('title', __('messages.sumber.pelan_strategik') . ' | ' . __('messages.site_name'))

@section('content')
<x-statement-band icon="presentation-chart-line" :label="__('messages.nav.sumber')" :title="__('messages.sumber.pelan_strategik')" :subtitle="__('messages.sumber.pelan_desc')" />

<x-breadcrumb :items="[
    ['label' => __('messages.nav.sumber'), 'href' => route('sumber.index')],
    ['label' => __('messages.sumber.pelan_strategik')],
]" />

<section class="py-12">
    <div class="container-page grid grid-cols-1 md:grid-cols-3 gap-5">
        @foreach($plans as [$title, $year, $format, $size])
            <article class="bg-white border rounded-lg p-5 hover:shadow transition">
                <div class="text-xs text-jata-red font-semibold uppercase tracking-wider mb-2">{{ $year }}</div>
                <h2 class="font-display font-bold text-primary mb-3">{{ $title }}</h2>
                <div class="flex items-center gap-2 text-xs text-gray-500 mb-4">
                    <span class="inline-block px-2 py-0.5 rounded bg-primary-pale text-primary font-bold">{{ $format }}</span>
                    <span>{{ $size }}</span>
                </div>
                <span class="inline-flex items-center gap-1 text-xs text-gray-400 italic">
                    <x-heroicon-o-arrow-down-tray class="w-3.5 h-3.5" />
                    {{ __('messages.sumber.data_coming_soon') }}
                </span>
            </article>
        @endforeach
    </div>
</section>
@endsection
