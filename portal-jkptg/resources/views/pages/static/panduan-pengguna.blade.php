@extends('layouts.public')

@section('title', __('messages.panduan.user_title') . ' | ' . __('messages.site_name'))

@section('content')
<x-breadcrumb :items="[['label' => __('messages.panduan.user_title')]]" />

<section class="bg-gradient-to-br from-primary to-primary-mute text-white py-12">
    <div class="container-page">
        <div class="flex items-center gap-2 text-jata-yellow text-sm uppercase tracking-wider mb-2">
            <x-heroicon-o-academic-cap class="w-4 h-4" />
            <span>{{ __('messages.nav.panduan') }}</span>
        </div>
        <h1 class="font-display text-3xl md:text-5xl font-bold mb-3">{{ __('messages.static.panduan_pengguna.heading') }}</h1>
        <p class="text-white/85 max-w-2xl">{{ __('messages.static.panduan_pengguna.help') }}</p>
    </div>
</section>

<section class="py-12">
    <div class="container-page max-w-3xl prose prose-sm md:prose-base text-gray-800">
        <h2 class="text-primary">{{ __('messages.static.panduan_pengguna.h_navigate') }}</h2>
        <p>{{ __('messages.static.panduan_pengguna.p_navigate') }}</p>
        <h2 class="text-primary">{{ __('messages.static.panduan_pengguna.h_search') }}</h2>
        <p>{{ __('messages.static.panduan_pengguna.p_search') }}</p>
        <h2 class="text-primary">{{ __('messages.static.panduan_pengguna.h_apply') }}</h2>
        <p>{{ __('messages.static.panduan_pengguna.p_apply') }}</p>
        <h2 class="text-primary">{{ __('messages.static.panduan_pengguna.h_a11y') }}</h2>
        <p>{{ __('messages.static.panduan_pengguna.p_a11y') }}</p>
        <h2 class="text-primary">{{ __('messages.static.panduan_pengguna.h_help') }}</h2>
        <p>{!! __('messages.static.panduan_pengguna.p_help', ['aduan' => route('hubungi.aduan'), 'faq' => route('faq.index')]) !!}</p>
    </div>
</section>
@endsection
