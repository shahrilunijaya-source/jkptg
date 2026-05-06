@extends('layouts.public')

@section('title', __('messages.korporat.pengurusan_tertinggi') . ' | ' . __('messages.site_name'))

@section('content')
<x-statement-band code="04" :label="__('messages.nav.korporat')" :title="__('messages.korporat.pengurusan_tertinggi')" :subtitle="__('messages.korporat.pengurusan_subtitle')" />

<x-breadcrumb :items="[
    ['label' => __('messages.nav.korporat'), 'url' => route('korporat.index')],
    ['label' => __('messages.korporat.pengurusan_tertinggi')]
]" />

<section class="py-12">
    <div class="container-page">
        @if($leaders->isEmpty())
            <x-state.empty :title="__('messages.states.empty.title')" tone="warning" />
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($leaders as $leader)
                    @php
                        // Pull photo URL from body HTML (first <img src="...">) for card thumbnail.
                        $bodyHtml = $leader->getTranslation('body', app()->getLocale(), false) ?: '';
                        preg_match('/<img[^>]+src="([^"]+)"/', $bodyHtml, $m);
                        $photo = $m[1] ?? null;
                    @endphp
                    <a href="{{ route('korporat.show', $leader->slug) }}"
                       class="group rounded-lg border bg-white overflow-hidden hover:shadow-lg hover:border-primary transition flex flex-col">
                        @if($photo)
                            <div class="aspect-[3/4] bg-gray-100 overflow-hidden">
                                <img src="{{ $photo }}" alt="{{ $leader->title }}"
                                     class="w-full h-full object-cover object-top group-hover:scale-105 transition-transform duration-300"
                                     loading="lazy">
                            </div>
                        @else
                            <div class="aspect-[3/4] bg-primary-pale flex items-center justify-center">
                                <x-heroicon-o-user class="w-16 h-16 text-primary/40" />
                            </div>
                        @endif
                        <div class="p-4 flex-1 flex flex-col">
                            <h3 class="font-semibold text-primary group-hover:underline text-sm leading-snug mb-2">
                                {{ $leader->title }}
                            </h3>
                            <p class="text-xs text-gray-600 line-clamp-3 mt-auto">
                                {{ strip_tags($leader->meta_description ?? '') }}
                            </p>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</section>
@endsection
