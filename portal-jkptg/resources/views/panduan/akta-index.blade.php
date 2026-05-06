@extends('layouts.public')

@section('title', __('messages.panduan.akta_title') . ' | ' . __('messages.site_name'))

@section('content')
<x-statement-band :label="__('messages.nav.panduan')" :title="__('messages.panduan.akta_heading')" :subtitle="__('messages.panduan.akta_help')" />

<x-breadcrumb :items="[
    ['label' => __('messages.nav.panduan'), 'href' => route('panduan.index')],
    ['label' => __('messages.panduan.akta_title')],
]" />

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
