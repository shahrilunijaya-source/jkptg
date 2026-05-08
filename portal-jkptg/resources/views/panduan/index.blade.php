@extends('layouts.public')

@section('title', __('messages.nav.panduan') . ' | ' . __('messages.site_name'))

@section('content')
<section class="relative text-white py-16 md:py-24 overflow-hidden">
    <div class="absolute inset-0"
         style="background-image: linear-gradient(180deg, rgba(15,30,51,0.55) 0%, rgba(15,30,51,0.88) 100%), url('https://images.unsplash.com/photo-1450101499163-c8848c66ca85?auto=format&fit=crop&w=1920&q=80'); background-size: cover; background-position: center;" aria-hidden="true"></div>
    <div class="relative container-page">
        <div class="flex items-center gap-2 text-jata-yellow text-sm uppercase tracking-wider mb-2">
            <x-heroicon-o-book-open class="w-4 h-4" />
            <span>{{ __('messages.nav.panduan') }}</span>
        </div>
        <h1 class="font-display text-3xl md:text-5xl font-bold mb-3">{{ __('messages.panduan.heading') }}</h1>
        <p class="text-white/85 max-w-2xl">{{ __('messages.panduan.help') }}</p>
    </div>
</section>

<x-breadcrumb :items="[['label' => __('messages.nav.panduan')]]" />

<section class="py-10">
    <div class="container-page">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <a href="{{ route('borang.index') }}" class="reveal-on-scroll group rounded-lg border bg-white p-5 hover:border-primary hover-lift" style="--reveal-delay:0ms">
                <div class="w-10 h-10 rounded bg-primary-pale text-primary flex items-center justify-center mb-3">
                    <x-heroicon-o-document-arrow-down class="w-5 h-5" />
                </div>
                <h3 class="font-semibold text-primary group-hover:underline mb-1">{{ __('messages.panduan.borang_title') }}</h3>
                <p class="text-sm text-gray-600 line-clamp-2">{{ __('messages.panduan.borang_desc') }}</p>
                <div class="text-xs text-jata-red font-semibold mt-2">{{ trans_choice('messages.borang.results', $borangCount, ['count' => $borangCount]) }}</div>
            </a>

            <a href="{{ route('service.index') }}" class="reveal-on-scroll group rounded-lg border bg-white p-5 hover:border-primary hover-lift" style="--reveal-delay:40ms">
                <div class="w-10 h-10 rounded bg-primary-pale text-primary flex items-center justify-center mb-3">
                    <x-heroicon-o-clipboard-document-list class="w-5 h-5" />
                </div>
                <h3 class="font-semibold text-primary group-hover:underline mb-1">{{ __('messages.panduan.sop_title') }}</h3>
                <p class="text-sm text-gray-600 line-clamp-2">{{ __('messages.panduan.sop_desc') }}</p>
            </a>

            <a href="{{ route('panduan.akta') }}" class="reveal-on-scroll group rounded-lg border bg-white p-5 hover:border-primary hover-lift" style="--reveal-delay:80ms">
                <div class="w-10 h-10 rounded bg-primary-pale text-primary flex items-center justify-center mb-3">
                    <x-heroicon-o-scale class="w-5 h-5" />
                </div>
                <h3 class="font-semibold text-primary group-hover:underline mb-1">{{ __('messages.panduan.guides_panduan_link') }}</h3>
                <p class="text-sm text-gray-600 line-clamp-2">{{ __('messages.panduan.guides_panduan_desc') }}</p>
            </a>

            <a href="{{ route('faq.index') }}" class="reveal-on-scroll group rounded-lg border bg-white p-5 hover:border-primary hover-lift" style="--reveal-delay:120ms">
                <div class="w-10 h-10 rounded bg-primary-pale text-primary flex items-center justify-center mb-3">
                    <x-heroicon-o-question-mark-circle class="w-5 h-5" />
                </div>
                <h3 class="font-semibold text-primary group-hover:underline mb-1">{{ __('messages.utility.soalan_lazim') }}</h3>
                <p class="text-sm text-gray-600 line-clamp-2">{{ __('messages.panduan.faq_desc') }}</p>
            </a>

            <a href="{{ route('peta-laman') }}" class="reveal-on-scroll group rounded-lg border bg-white p-5 hover:border-primary hover-lift" style="--reveal-delay:160ms">
                <div class="w-10 h-10 rounded bg-primary-pale text-primary flex items-center justify-center mb-3">
                    <x-heroicon-o-map class="w-5 h-5" />
                </div>
                <h3 class="font-semibold text-primary group-hover:underline mb-1">{{ __('messages.utility.peta_laman') }}</h3>
                <p class="text-sm text-gray-600 line-clamp-2">{{ __('messages.panduan.peta_desc') }}</p>
            </a>

            <a href="{{ route('panduan-pengguna') }}" class="reveal-on-scroll group rounded-lg border bg-white p-5 hover:border-primary hover-lift" style="--reveal-delay:200ms">
                <div class="w-10 h-10 rounded bg-primary-pale text-primary flex items-center justify-center mb-3">
                    <x-heroicon-o-academic-cap class="w-5 h-5" />
                </div>
                <h3 class="font-semibold text-primary group-hover:underline mb-1">{{ __('messages.panduan.user_title') }}</h3>
                <p class="text-sm text-gray-600 line-clamp-2">{{ __('messages.panduan.user_desc') }}</p>
            </a>

            <a href="{{ route('hubungi.aduan') }}" class="reveal-on-scroll group rounded-lg border bg-white p-5 hover:border-primary hover-lift" style="--reveal-delay:240ms">
                <div class="w-10 h-10 rounded bg-primary-pale text-primary flex items-center justify-center mb-3">
                    <x-heroicon-o-megaphone class="w-5 h-5" />
                </div>
                <h3 class="font-semibold text-primary group-hover:underline mb-1">{{ __('messages.utility.aduan') }}</h3>
                <p class="text-sm text-gray-600 line-clamp-2">{{ __('messages.panduan.aduan_desc') }}</p>
            </a>
        </div>
    </div>
</section>

@if($services->count())
<section class="bg-gray-50 py-10">
    <div class="container-page">
        <h2 class="font-display text-2xl font-bold text-primary mb-1">{{ __('messages.panduan.services_title') }}</h2>
        <p class="text-gray-600 text-sm mb-6">{{ __('messages.panduan.services_help') }}</p>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
            @foreach($services as $s)
                <a href="{{ route('service.show', $s->slug) }}" class="reveal-on-scroll bg-white border rounded p-4 hover:border-primary flex items-center gap-3 hover-lift" style="--reveal-delay:{{ $loop->index * 40 }}ms">
                    <x-heroicon-o-document-text class="w-6 h-6 text-primary flex-shrink-0" />
                    <span class="text-sm font-semibold text-primary">{{ $s->name }}</span>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection
