@extends('layouts.public')

@section('title', __('messages.panduan.akta_title') . ' | ' . __('messages.site_name'))

@section('content')
<x-breadcrumb :items="[
    ['label' => __('messages.nav.panduan'), 'href' => route('panduan.index')],
    ['label' => __('messages.panduan.akta_title')],
]" />

<section class="bg-gradient-to-br from-primary to-primary-mute text-white py-12">
    <div class="container-page">
        <div class="flex items-center gap-2 text-jata-yellow text-sm uppercase tracking-wider mb-2">
            <x-heroicon-o-scale class="w-4 h-4" />
            <span>{{ __('messages.nav.panduan') }}</span>
        </div>
        <h1 class="font-display text-3xl md:text-5xl font-bold mb-3">{{ __('messages.panduan.akta_heading') }}</h1>
        <p class="text-white/85 max-w-2xl">{{ __('messages.panduan.akta_help') }}</p>
    </div>
</section>

<section class="py-12">
    <div class="container-page">
        <ul class="bg-white border rounded-lg divide-y">
            @foreach($acts as $slug => $act)
                <li>
                    <a href="{{ route('panduan.akta.show', $slug) }}" class="flex items-center gap-4 p-5 hover:bg-gray-50 transition group">
                        <div class="w-12 h-12 rounded-lg bg-primary-pale text-primary flex items-center justify-center flex-shrink-0">
                            <x-heroicon-o-scale class="w-6 h-6" />
                        </div>
                        <div class="flex-1">
                            <h2 class="font-display font-bold text-primary group-hover:underline">{{ $act['title'] }}</h2>
                            <div class="text-xs text-gray-500 mt-1 flex flex-wrap gap-x-3">
                                <span>{{ __('messages.panduan.akta_year') }}: {{ $act['year'] }}</span>
                                <span class="capitalize">{{ __('messages.panduan.akta_topic') }}: {{ $act['topic'] }}</span>
                            </div>
                        </div>
                        <x-heroicon-o-chevron-right class="w-5 h-5 text-gray-400 flex-shrink-0" />
                    </a>
                </li>
            @endforeach
        </ul>
        <p class="mt-6 text-xs text-gray-500">{{ __('messages.panduan.akta_disclaimer') }}</p>
    </div>
</section>
@endsection
