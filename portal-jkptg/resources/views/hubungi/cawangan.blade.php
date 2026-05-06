@extends('layouts.public')

@section('title', __('messages.hubungi.branches') . ' | ' . __('messages.site_name'))

@section('content')
<x-breadcrumb :items="[
    ['label' => __('messages.utility.hubungi'), 'href' => route('hubungi.index')],
    ['label' => __('messages.hubungi.branches')],
]" />

<section class="bg-gradient-to-br from-primary to-primary-mute text-white py-12">
    <div class="container-page">
        <div class="flex items-center gap-2 text-jata-yellow text-sm uppercase tracking-wider mb-2">
            <x-heroicon-o-map-pin class="w-4 h-4" />
            <span>{{ __('messages.utility.hubungi') }}</span>
        </div>
        <h1 class="font-display text-3xl md:text-5xl font-bold mb-3">{{ __('messages.hubungi.branches') }}</h1>
        <p class="text-white/85 max-w-2xl">{{ trans_choice('messages.hubungi.branch_count', $branches->count(), ['count' => $branches->count()]) }}</p>
    </div>
</section>

<section class="py-12">
    <div class="container-page">
        @if($branches->count())
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($branches as $b)
                    <article class="bg-white border rounded-lg p-5 hover:shadow transition">
                        <div class="flex items-center gap-2 text-xs text-jata-red font-semibold uppercase tracking-wider mb-2">
                            <x-heroicon-o-map-pin class="w-3.5 h-3.5" />
                            <span>{{ $b->state }}</span>
                        </div>
                        <h2 class="font-display font-bold text-primary mb-2">
                            <a href="{{ route('hubungi.cawangan.show', $b->slug) }}" class="hover:underline">{{ $b->name }}</a>
                        </h2>
                        <address class="not-italic text-sm text-gray-700 mb-3 leading-relaxed">{{ $b->address }}</address>
                        <dl class="text-sm space-y-1 text-gray-700">
                            @if($b->phone)
                                <div class="flex items-center gap-2"><x-heroicon-o-phone class="w-3.5 h-3.5 text-primary" />{{ $b->phone }}</div>
                            @endif
                            @if($b->email)
                                <div class="flex items-center gap-2"><x-heroicon-o-envelope class="w-3.5 h-3.5 text-primary" /><a href="mailto:{{ $b->email }}" class="text-primary hover:underline">{{ $b->email }}</a></div>
                            @endif
                        </dl>
                        <a href="{{ route('hubungi.cawangan.show', $b->slug) }}" class="mt-4 inline-flex items-center gap-1 text-sm font-semibold text-primary hover:underline">
                            {{ __('messages.hubungi.view_branch') }}
                            <x-heroicon-o-arrow-right class="w-3.5 h-3.5" />
                        </a>
                    </article>
                @endforeach
            </div>
        @else
            <x-state.empty icon="heroicon-o-building-office-2" :title="__('messages.states.empty.title')" />
        @endif
    </div>
</section>
@endsection
