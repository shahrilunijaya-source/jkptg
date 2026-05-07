@extends('layouts.public')

@section('title', __('messages.sumber.galeri') . ' | ' . __('messages.site_name'))

@section('content')
<section class="bg-gradient-to-br from-primary to-primary-mute text-white py-12">
    <div class="container-page">
        <div class="flex items-center gap-2 text-jata-yellow text-sm uppercase tracking-wider mb-2">
            <x-heroicon-o-photo class="w-4 h-4" />
            <span>{{ __('messages.sumber.galeri') }}</span>
        </div>
        <h1 class="font-display text-3xl md:text-5xl font-bold mb-3">{{ __('messages.sumber.galeri') }}</h1>
        <p class="text-white/85 max-w-2xl">{{ __('messages.sumber.galeri_desc') }}</p>
    </div>
</section>

<x-breadcrumb :items="[
    ['label' => __('messages.nav.sumber'), 'href' => route('sumber.index')],
    ['label' => __('messages.sumber.galeri')],
]" />

<section class="py-6 border-b">
    <div class="container-page flex flex-wrap gap-2">
        @foreach($allowed as $t)
            <a href="{{ route('sumber.galeri', $t) }}"
               class="px-4 py-1.5 rounded-full text-sm font-semibold border transition {{ $type === $t ? 'bg-primary text-white border-primary' : 'bg-white text-primary border-gray-300 hover:border-primary' }}">
                {{ __('messages.sumber.galeri_' . $t) }}
            </a>
        @endforeach
    </div>
</section>

<section class="py-12">
    <div class="container-page">
        <x-state.empty
            icon="heroicon-o-photo"
            :title="__('messages.sumber.galeri_coming_soon_title')"
            :message="__('messages.sumber.galeri_coming_soon_body')"
            tone="info" />
    </div>
</section>
@endsection
