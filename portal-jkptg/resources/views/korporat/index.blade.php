@extends('layouts.public')

@section('title', __('messages.nav.korporat') . ' | ' . __('messages.site_name'))

@section('content')
<section class="relative text-white py-16 md:py-24 overflow-hidden">
    <div class="absolute inset-0"
         style="background-image: linear-gradient(180deg, rgba(15,30,51,0.55) 0%, rgba(15,30,51,0.88) 100%), url('https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=1920&q=80'); background-size: cover; background-position: center;" aria-hidden="true"></div>
    <div class="relative container-page">
        <div class="flex items-center gap-2 text-jata-yellow text-sm uppercase tracking-wider mb-2">
            <x-heroicon-o-building-office-2 class="w-4 h-4" />
            <span>{{ __('messages.nav.korporat') }}</span>
        </div>
        <h1 class="font-display text-3xl md:text-5xl font-bold mb-3">{{ __('messages.korporat.heading') }}</h1>
        <p class="text-white/85 max-w-2xl">{{ __('messages.korporat.help') }}</p>
    </div>
</section>

<x-breadcrumb :items="[['label' => __('messages.nav.korporat')]]" />

<section class="py-10">
    <div class="container-page">
        @if($pages->isEmpty())
            <x-state.empty :title="__('messages.states.empty.title')" tone="warning" />
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($pages as $page)
                    <a href="{{ route('korporat.show', $page->slug) }}" class="reveal-on-scroll group rounded-lg border bg-white p-5 hover:border-primary hover-lift" style="--reveal-delay:{{ $loop->index * 40 }}ms">
                        <div class="w-10 h-10 rounded bg-primary-pale text-primary flex items-center justify-center mb-3">
                            <x-heroicon-o-document-text class="w-5 h-5" />
                        </div>
                        <h3 class="font-semibold text-primary group-hover:underline mb-1">{{ $page->title }}</h3>
                        <p class="text-sm text-gray-600 line-clamp-2">{{ strip_tags($page->meta_description ?? '') }}</p>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</section>
@endsection
