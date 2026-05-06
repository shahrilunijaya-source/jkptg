@extends('layouts.public')

@section('title', __('messages.nav.panduan') . ' | ' . __('messages.site_name'))

@section('content')
<x-breadcrumb :items="[['label' => __('messages.nav.panduan')]]" />

<section class="bg-gradient-to-br from-primary to-primary-mute text-white py-12">
    <div class="container-page">
        <div class="flex items-center gap-2 text-jata-yellow text-sm uppercase tracking-wider mb-2">
            <x-heroicon-o-book-open class="w-4 h-4" />
            <span>{{ __('messages.nav.panduan') }}</span>
        </div>
        <h1 class="font-display text-3xl md:text-5xl font-bold mb-3">{{ __('messages.panduan.heading') }}</h1>
        <p class="text-white/85 max-w-2xl">{{ __('messages.panduan.help') }}</p>
    </div>
</section>

<section class="py-12">
    <div class="container-page grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        <a href="{{ route('borang.index') }}" class="group block bg-white border rounded-lg p-6 hover:border-primary hover:shadow-md transition">
            <x-heroicon-o-document-arrow-down class="w-8 h-8 text-primary mb-3" />
            <h2 class="font-display font-bold text-lg mb-1 text-primary">{{ __('messages.panduan.borang_title') }}</h2>
            <p class="text-sm text-gray-600 mb-3">{{ __('messages.panduan.borang_desc') }}</p>
            <div class="text-xs text-jata-red font-semibold">{{ trans_choice('messages.borang.results', $borangCount, ['count' => $borangCount]) }}</div>
        </a>

        <a href="{{ route('service.index') }}" class="group block bg-white border rounded-lg p-6 hover:border-primary hover:shadow-md transition">
            <x-heroicon-o-clipboard-document-list class="w-8 h-8 text-primary mb-3" />
            <h2 class="font-display font-bold text-lg mb-1 text-primary">{{ __('messages.panduan.sop_title') }}</h2>
            <p class="text-sm text-gray-600">{{ __('messages.panduan.sop_desc') }}</p>
        </a>

        <a href="{{ route('faq.index') }}" class="group block bg-white border rounded-lg p-6 hover:border-primary hover:shadow-md transition">
            <x-heroicon-o-question-mark-circle class="w-8 h-8 text-primary mb-3" />
            <h2 class="font-display font-bold text-lg mb-1 text-primary">{{ __('messages.utility.soalan_lazim') }}</h2>
            <p class="text-sm text-gray-600">{{ __('messages.panduan.faq_desc') }}</p>
        </a>

        <a href="{{ route('peta-laman') }}" class="group block bg-white border rounded-lg p-6 hover:border-primary hover:shadow-md transition">
            <x-heroicon-o-map class="w-8 h-8 text-primary mb-3" />
            <h2 class="font-display font-bold text-lg mb-1 text-primary">{{ __('messages.utility.peta_laman') }}</h2>
            <p class="text-sm text-gray-600">{{ __('messages.panduan.peta_desc') }}</p>
        </a>

        <a href="{{ route('panduan-pengguna') }}" class="group block bg-white border rounded-lg p-6 hover:border-primary hover:shadow-md transition">
            <x-heroicon-o-academic-cap class="w-8 h-8 text-primary mb-3" />
            <h2 class="font-display font-bold text-lg mb-1 text-primary">{{ __('messages.panduan.user_title') }}</h2>
            <p class="text-sm text-gray-600">{{ __('messages.panduan.user_desc') }}</p>
        </a>

        <a href="{{ route('hubungi.aduan') }}" class="group block bg-white border rounded-lg p-6 hover:border-primary hover:shadow-md transition">
            <x-heroicon-o-megaphone class="w-8 h-8 text-primary mb-3" />
            <h2 class="font-display font-bold text-lg mb-1 text-primary">{{ __('messages.utility.aduan') }}</h2>
            <p class="text-sm text-gray-600">{{ __('messages.panduan.aduan_desc') }}</p>
        </a>
    </div>
</section>

@if($services->count())
<section class="bg-gray-50 py-12">
    <div class="container-page">
        <h2 class="font-display text-2xl font-bold text-primary mb-1">{{ __('messages.panduan.services_title') }}</h2>
        <p class="text-gray-600 text-sm mb-6">{{ __('messages.panduan.services_help') }}</p>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
            @foreach($services as $s)
                <a href="{{ route('service.show', $s->slug) }}" class="bg-white border rounded p-4 hover:border-primary transition flex items-center gap-3">
                    <x-heroicon-o-document-text class="w-6 h-6 text-primary flex-shrink-0" />
                    <span class="text-sm font-semibold text-primary">{{ $s->name }}</span>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection
