@extends('layouts.public')

@section('title', __('messages.nav.sumber') . ' | ' . __('messages.site_name'))

@section('content')
<x-statement-band icon="archive-box" :label="__('messages.nav.sumber')" :title="__('messages.sumber.heading')" :subtitle="__('messages.sumber.help')" />

<x-breadcrumb :items="[['label' => __('messages.nav.sumber')]]" />

<section class="py-12">
    <div class="container-page">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach([
                ['icon' => 'photo', 'label' => __('messages.sumber.galeri'), 'desc' => __('messages.sumber.galeri_desc'), 'href' => route('sumber.galeri')],
                ['icon' => 'chart-bar', 'label' => __('messages.sumber.data_terbuka'), 'desc' => __('messages.sumber.data_desc'), 'href' => route('sumber.data-terbuka')],
                ['icon' => 'presentation-chart-line', 'label' => __('messages.sumber.pelan_strategik'), 'desc' => __('messages.sumber.pelan_desc'), 'href' => route('sumber.pelan-strategik')],
                ['icon' => 'book-open', 'label' => __('messages.sumber.penerbitan'), 'desc' => __('messages.sumber.penerbitan_desc'), 'href' => route('sumber.penerbitan')],
                ['icon' => 'sparkles', 'label' => __('messages.sumber.infografik'), 'desc' => __('messages.sumber.infografik_desc'), 'href' => route('sumber.infografik')],
                ['icon' => 'archive-box', 'label' => __('messages.sumber.arkib'), 'desc' => __('messages.sumber.arkib_desc'), 'href' => route('sumber.arkib')],
            ] as $item)
                <a href="{{ $item['href'] }}" class="group rounded-lg border bg-white p-5 hover:shadow hover:border-primary transition">
                    <div class="w-10 h-10 rounded bg-primary-pale text-primary flex items-center justify-center mb-3">
                        <x-dynamic-component :component="'heroicon-o-' . $item['icon']" class="w-5 h-5" />
                    </div>
                    <h3 class="font-semibold text-primary group-hover:underline mb-1">{{ $item['label'] }}</h3>
                    <p class="text-sm text-gray-600">{{ $item['desc'] }}</p>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endsection
