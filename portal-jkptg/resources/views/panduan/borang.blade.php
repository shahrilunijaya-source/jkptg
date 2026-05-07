@extends('layouts.public')

@section('title', __('messages.borang.title') . ' | ' . __('messages.site_name'))

@section('content')
<section class="bg-gradient-to-br from-primary to-primary-mute text-white py-12">
    <div class="container-page">
        <div class="flex items-center gap-2 text-jata-yellow text-sm uppercase tracking-wider mb-2">
            <x-heroicon-o-document-text class="w-4 h-4" />
            <span>{{ __('messages.borang.title') }}</span>
        </div>
        <h1 class="font-display text-3xl md:text-5xl font-bold mb-3">{{ __('messages.borang.heading') }}</h1>
        <p class="text-white/85 max-w-2xl">{{ __('messages.borang.help') }}</p>
    </div>
</section>

<x-breadcrumb :items="[
    ['label' => __('messages.nav.panduan'), 'href' => '#'],
    ['label' => __('messages.borang.title')],
]" />

<section class="py-12">
    <div class="container-page grid grid-cols-1 lg:grid-cols-[260px_1fr] gap-8">

        {{-- Filter sidebar --}}
        <aside class="space-y-4">
            <form method="get" action="{{ route('borang.index') }}" class="bg-white border rounded-lg p-4 space-y-3" role="search">
                <label class="block">
                    <span class="text-xs uppercase font-semibold text-gray-500 tracking-wider">{{ __('messages.borang.search_label') }}</span>
                    <div class="mt-1 relative">
                        <input type="search" name="q" value="{{ $q }}"
                               placeholder="{{ __('messages.borang.search_placeholder') }}"
                               class="w-full pl-9 pr-3 py-2 border rounded text-sm focus:border-primary focus:ring-primary">
                        <x-heroicon-o-magnifying-glass class="absolute left-3 top-2.5 w-4 h-4 text-gray-400" />
                    </div>
                </label>

                <fieldset>
                    <legend class="text-xs uppercase font-semibold text-gray-500 tracking-wider mb-2">{{ __('messages.borang.category') }}</legend>
                    <div class="space-y-1">
                        <label class="flex items-center gap-2 text-sm">
                            <input type="radio" name="cat" value="" {{ $cat ? '' : 'checked' }} class="text-primary focus:ring-primary">
                            <span>{{ __('messages.borang.all_categories') }}</span>
                        </label>
                        @foreach($categories as $c)
                            <label class="flex items-center gap-2 text-sm">
                                <input type="radio" name="cat" value="{{ $c }}" {{ $cat === $c ? 'checked' : '' }} class="text-primary focus:ring-primary">
                                <span class="capitalize">{{ $c }}</span>
                            </label>
                        @endforeach
                    </div>
                </fieldset>

                <button type="submit" class="btn-primary w-full justify-center">
                    <x-heroicon-o-funnel class="w-4 h-4" />
                    {{ __('messages.borang.apply_filter') }}
                </button>
                @if($q || $cat)
                    <a href="{{ route('borang.index') }}" class="block text-center text-sm text-gray-600 hover:text-primary">
                        {{ __('messages.borang.clear') }}
                    </a>
                @endif
            </form>

            <div class="bg-primary-pale rounded-lg p-4 text-sm text-primary">
                <div class="font-semibold flex items-center gap-2 mb-1">
                    <x-heroicon-o-information-circle class="w-4 h-4" />
                    {{ __('messages.borang.help_title') }}
                </div>
                <p>{{ __('messages.borang.help_body') }}</p>
            </div>
        </aside>

        <div>
            <div class="flex items-center justify-between mb-4">
                <p class="text-sm text-gray-600">
                    {{ trans_choice('messages.borang.results', $forms->count(), ['count' => $forms->count()]) }}
                </p>
                @if($q)<span class="text-xs text-gray-500">{{ __('messages.borang.searching_for', ['q' => $q]) }}</span>@endif
            </div>

            @if($forms->isEmpty())
                <x-state.empty
                    icon="heroicon-o-document-magnifying-glass"
                    :title="__('messages.states.empty.title')"
                    :message="__('messages.states.empty.borang_search')"
                    :cta="__('messages.borang.clear')"
                    :href="route('borang.index')"
                    tone="info" />
            @else
                <div class="space-y-3">
                    @foreach($forms as $form)
                        <a href="#" class="flex items-center justify-between p-4 border rounded bg-white hover:border-primary hover:shadow transition group">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded bg-primary-pale text-primary flex items-center justify-center flex-shrink-0">
                                    <x-heroicon-o-document-text class="w-5 h-5" />
                                </div>
                                <div>
                                    <div class="font-semibold text-primary group-hover:underline">{{ $form->name }}</div>
                                    <div class="text-xs text-gray-500 mt-0.5 flex flex-wrap gap-x-3">
                                        <span class="capitalize px-2 py-0.5 bg-gray-100 rounded text-gray-700">{{ $form->category }}</span>
                                        <span>v{{ $form->version }}</span>
                                        <span>{{ $form->file_size_human }}</span>
                                        <span>{{ number_format($form->downloads_count) }} {{ __('messages.borang.downloads') }}</span>
                                    </div>
                                </div>
                            </div>
                            <span class="text-primary text-sm font-semibold flex items-center gap-1 flex-shrink-0">
                                {{ __('messages.service.muat_turun') }}
                                <x-heroicon-o-arrow-down-tray class="w-4 h-4" />
                            </span>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</section>
@endsection
