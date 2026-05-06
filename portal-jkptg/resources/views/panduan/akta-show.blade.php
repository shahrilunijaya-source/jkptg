@extends('layouts.public')

@section('title', $act['title'] . ' | ' . __('messages.site_name'))

@section('content')
<section class="bg-gradient-to-br from-primary to-primary-mute text-white py-12 no-print">
    <div class="container-page">
        <div class="flex items-center gap-2 text-jata-yellow text-sm uppercase tracking-wider mb-2">
            <x-heroicon-o-scale class="w-4 h-4" />
            <span>{{ __('messages.panduan.akta_title') }}</span>
        </div>
        <h1 class="font-display text-2xl md:text-4xl font-bold mb-2">{{ $act['title'] }}</h1>
        <div class="text-white/80 text-sm">{{ __('messages.panduan.akta_year') }}: {{ $act['year'] }}</div>
    </div>
</section>

<x-breadcrumb :items="[
    ['label' => __('messages.nav.panduan'), 'href' => route('panduan.index')],
    ['label' => __('messages.panduan.akta_title'), 'href' => route('panduan.akta')],
    ['label' => $act['title']],
]" />

<section class="py-12 print:py-4">
    <div class="container-page max-w-3xl">
        <div class="hidden print:block mb-6">
            <h1 class="font-display text-2xl font-bold text-primary">{{ $act['title'] }}</h1>
        </div>

        <div class="flex justify-end mb-6 no-print gap-2">
            <a href="{{ route('panduan.akta.pdf', $act['slug']) }}" class="text-sm font-semibold bg-primary text-white px-4 py-2 rounded hover:bg-primary-dark transition flex items-center gap-1">
                <x-heroicon-o-arrow-down-tray class="w-4 h-4" />
                PDF
            </a>
            <button type="button" onclick="window.print()" class="text-sm font-semibold text-primary border border-primary px-4 py-2 rounded hover:bg-primary hover:text-white transition flex items-center gap-1">
                <x-heroicon-o-printer class="w-4 h-4" />
                {{ __('messages.service.print') }}
            </button>
        </div>

        <article class="prose prose-sm md:prose-base max-w-none text-gray-800">
            <h2 class="text-primary">{{ __('messages.panduan.akta_h_overview') }}</h2>
            <p>{{ __('messages.panduan.akta_p_overview', ['title' => $act['title']]) }}</p>

            <h2 class="text-primary">{{ __('messages.panduan.akta_h_scope') }}</h2>
            <p>{{ __('messages.panduan.akta_p_scope') }}</p>

            <h2 class="text-primary">{{ __('messages.panduan.akta_h_admin') }}</h2>
            <p>{{ __('messages.panduan.akta_p_admin') }}</p>

            <h2 class="text-primary">{{ __('messages.panduan.akta_h_full') }}</h2>
            <p>{!! __('messages.panduan.akta_p_full', ['url' => 'https://www.agc.gov.my/']) !!}</p>
        </article>

        <div class="mt-10 rounded-lg border border-jata-yellow/40 bg-jata-yellow/10 p-5 no-print">
            <h2 class="font-semibold text-primary mb-2">{{ __('messages.static.legal') }}</h2>
            <p class="text-sm text-gray-700">{{ __('messages.panduan.akta_disclaimer') }}</p>
        </div>
    </div>
</section>
@endsection
