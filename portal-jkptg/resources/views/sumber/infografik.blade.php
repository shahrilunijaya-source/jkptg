@extends('layouts.public')

@section('title', __('messages.sumber.infografik') . ' | ' . __('messages.site_name'))

@section('content')
<x-statement-band icon="sparkles" :label="__('messages.nav.sumber')" :title="__('messages.sumber.infografik')" :subtitle="__('messages.sumber.infografik_desc')" />

<x-breadcrumb :items="[
    ['label' => __('messages.nav.sumber'), 'href' => route('sumber.index')],
    ['label' => __('messages.sumber.infografik')],
]" />

<section class="py-12">
    <div class="container-page grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach($items as [$title, $key])
            <article class="group bg-white border rounded-lg overflow-hidden hover:shadow-lg transition">
                <div class="h-48 bg-gradient-to-br from-primary to-primary-light flex items-center justify-center text-white">
                    <x-heroicon-o-sparkles class="w-16 h-16 opacity-60" />
                </div>
                <div class="p-4">
                    <h3 class="font-display font-bold text-primary mb-1 group-hover:underline">{{ $title }}</h3>
                    <p class="text-xs text-gray-500 italic">{{ __('messages.sumber.data_coming_soon') }}</p>
                </div>
            </article>
        @endforeach
    </div>
</section>
@endsection
