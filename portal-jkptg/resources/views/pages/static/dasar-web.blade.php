@extends('layouts.public')

@section('title', __('messages.peta_laman.dasar_web') . ' | ' . __('messages.site_name'))

@section('content')
<section class="bg-gradient-to-br from-primary to-primary-mute text-white py-12">
    <div class="container-page">
        <div class="flex items-center gap-2 text-jata-yellow text-sm uppercase tracking-wider mb-2">
            <x-heroicon-o-document-text class="w-4 h-4" />
            <span>{{ __('messages.static.legal') }}</span>
        </div>
        <h1 class="font-display text-3xl md:text-5xl font-bold mb-3">{{ __('messages.static.dasar_web.heading') }}</h1>
    </div>
</section>

<x-breadcrumb :items="[['label' => __('messages.peta_laman.dasar_web')]]" />

<section class="py-12">
    <div class="container-page max-w-3xl prose prose-sm md:prose-base text-gray-800">
        <p>{{ __('messages.static.dasar_web.intro') }}</p>
        <h2 class="text-primary">{{ __('messages.static.dasar_web.h_browser') }}</h2>
        <p>{{ __('messages.static.dasar_web.p_browser') }}</p>
        <h2 class="text-primary">{{ __('messages.static.dasar_web.h_a11y') }}</h2>
        <p>{{ __('messages.static.dasar_web.p_a11y') }}</p>
        <h2 class="text-primary">{{ __('messages.static.dasar_web.h_lang') }}</h2>
        <p>{{ __('messages.static.dasar_web.p_lang') }}</p>
        <h2 class="text-primary">{{ __('messages.static.dasar_web.h_uptime') }}</h2>
        <p>{{ __('messages.static.dasar_web.p_uptime') }}</p>
        <p class="text-sm text-gray-500 mt-8">{{ __('messages.static.last_review', ['date' => '2026-01-01']) }}</p>
    </div>
</section>
@endsection
