@extends('layouts.public')

@section('title', __('messages.nav.korporat') . ' | ' . __('messages.site_name'))

@section('content')
<x-statement-band :label="__('messages.nav.korporat')" :title="__('messages.korporat.heading')" :subtitle="__('messages.korporat.help')" />

<x-breadcrumb :items="[['label' => __('messages.nav.korporat')]]" />

<section class="py-12">
    <div class="container-page">
        @if($pages->isEmpty())
            <x-state.empty :title="__('messages.states.empty.title')" tone="warning" />
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($pages as $page)
                    <a href="{{ route('korporat.show', $page->slug) }}" class="group rounded-lg border bg-white p-5 hover:shadow hover:border-primary transition">
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
