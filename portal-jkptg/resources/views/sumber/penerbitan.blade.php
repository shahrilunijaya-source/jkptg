@extends('layouts.public')

@section('title', __('messages.sumber.penerbitan') . ' | ' . __('messages.site_name'))

@section('content')
<section class="bg-gradient-to-br from-primary to-primary-mute text-white py-12">
    <div class="container-page">
        <div class="flex items-center gap-2 text-jata-yellow text-sm uppercase tracking-wider mb-2">
            <x-heroicon-o-book-open class="w-4 h-4" />
            <span>{{ __('messages.sumber.penerbitan') }}</span>
        </div>
        <h1 class="font-display text-3xl md:text-5xl font-bold mb-3">{{ __('messages.sumber.penerbitan') }}</h1>
        <p class="text-white/85 max-w-2xl">{{ __('messages.sumber.penerbitan_desc') }}</p>
    </div>
</section>

<x-breadcrumb :items="[
    ['label' => __('messages.nav.sumber'), 'href' => route('sumber.index')],
    ['label' => __('messages.sumber.penerbitan')],
]" />

<section class="py-12">
    <div class="container-page">
        @if($page)
            <article class="prose prose-sm md:prose-base max-w-3xl text-gray-800 mb-10">
                {!! $page->body !!}
            </article>
        @endif

        @if($news->count())
            <h2 class="font-display text-xl font-bold text-primary mb-4">{{ __('messages.sumber.recent_publications') }}</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($news as $item)
                    <article class="reveal-on-scroll bg-white border rounded-lg p-5 hover-lift" style="--reveal-delay:{{ $loop->index * 40 }}ms">
                        <div class="text-xs text-jata-red font-semibold uppercase tracking-wider mb-2">{{ $item->published_at?->isoFormat('D MMM Y') }}</div>
                        <h3 class="font-display font-bold text-primary mb-2 line-clamp-2">{{ $item->title }}</h3>
                        <p class="text-sm text-gray-600 line-clamp-3">{{ strip_tags($item->summary ?? '') }}</p>
                    </article>
                @endforeach
            </div>
        @else
            <x-state.empty icon="heroicon-o-newspaper" :title="__('messages.states.empty.news')" tone="info" />
        @endif
    </div>
</section>
@endsection
