@extends('layouts.public')

@section('title', __('messages.footer.hak_cipta') . ' | ' . __('messages.site_name'))

@section('content')
<x-breadcrumb :items="[['label' => __('messages.footer.hak_cipta')]]" />

<section class="bg-gradient-to-br from-primary to-primary-mute text-white py-12">
    <div class="container-page">
        <div class="flex items-center gap-2 text-jata-yellow text-sm uppercase tracking-wider mb-2">
            <x-heroicon-o-document-text class="w-4 h-4" />
            <span>{{ __('messages.static.legal') }}</span>
        </div>
        <h1 class="font-display text-3xl md:text-5xl font-bold mb-3">{{ __('messages.static.hak_cipta.heading') }}</h1>
    </div>
</section>

<section class="py-12">
    <div class="container-page max-w-3xl prose prose-sm md:prose-base text-gray-800">
        <p>{{ __('messages.static.hak_cipta.intro') }}</p>
        <h2 class="text-primary">{{ __('messages.static.hak_cipta.h_owner') }}</h2>
        <p>{{ __('messages.static.hak_cipta.p_owner') }}</p>
        <h2 class="text-primary">{{ __('messages.static.hak_cipta.h_use') }}</h2>
        <p>{{ __('messages.static.hak_cipta.p_use') }}</p>
        <h2 class="text-primary">{{ __('messages.static.hak_cipta.h_third') }}</h2>
        <p>{{ __('messages.static.hak_cipta.p_third') }}</p>
        <p class="text-sm text-gray-500 mt-8">{{ __('messages.static.last_review', ['date' => '2026-01-01']) }}</p>
    </div>
</section>
@endsection
