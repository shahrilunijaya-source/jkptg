@extends('layouts.public')

@section('title', __('messages.sumber.arkib') . ' | ' . __('messages.site_name'))

@section('content')
<x-breadcrumb :items="[
    ['label' => __('messages.nav.sumber'), 'href' => route('sumber.index')],
    ['label' => __('messages.sumber.arkib')],
]" />

<section class="bg-gradient-to-br from-primary to-primary-mute text-white py-12">
    <div class="container-page">
        <div class="flex items-center gap-2 text-jata-yellow text-sm uppercase tracking-wider mb-2">
            <x-heroicon-o-archive-box class="w-4 h-4" />
            <span>{{ __('messages.sumber.arkib') }}</span>
        </div>
        <h1 class="font-display text-3xl md:text-5xl font-bold mb-3">{{ __('messages.sumber.arkib') }}</h1>
        <p class="text-white/85 max-w-2xl">{{ __('messages.sumber.arkib_desc') }}</p>
    </div>
</section>

<section class="py-6 border-b">
    <div class="container-page flex flex-wrap gap-2">
        @foreach($allowed as $t)
            <a href="{{ route('sumber.arkib', $t) }}"
               class="px-4 py-1.5 rounded-full text-sm font-semibold border transition {{ $type === $t ? 'bg-primary text-white border-primary' : 'bg-white text-primary border-gray-300 hover:border-primary' }}">
                {{ __('messages.sumber.arkib_' . $t) }}
            </a>
        @endforeach
    </div>
</section>

<section class="py-12">
    <div class="container-page">
        @if($items->count())
            <ul class="divide-y bg-white border rounded-lg">
                @foreach($items as $item)
                    <li class="p-4 hover:bg-gray-50 flex flex-col md:flex-row md:items-center gap-2 md:gap-4">
                        <div class="text-xs text-jata-red font-semibold uppercase tracking-wider md:w-32 md:flex-shrink-0">
                            {{ optional($item->published_at ?? $item->created_at)->isoFormat('D MMM Y') }}
                        </div>
                        <h3 class="font-semibold text-primary flex-1">{{ $item->title }}</h3>
                        @if($item->summary ?? null)
                            <p class="text-xs text-gray-500 line-clamp-1 hidden lg:block lg:max-w-xs">{{ strip_tags($item->summary) }}</p>
                        @endif
                    </li>
                @endforeach
            </ul>
        @else
            <x-state.empty icon="heroicon-o-archive-box-x-mark" :title="__('messages.states.empty.title')" tone="info" />
        @endif
    </div>
</section>
@endsection
