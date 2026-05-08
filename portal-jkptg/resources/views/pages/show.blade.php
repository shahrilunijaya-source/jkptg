@extends('layouts.public')

@section('title', $page->title . ' | ' . __('messages.site_name'))
@section('description', strip_tags($page->meta_description ?? ''))

@section('content')
<section class="bg-gradient-to-br from-primary to-primary-mute text-white py-12">
    <div class="container-page">
        @if(!empty($section))
            <div class="flex items-center gap-2 text-jata-yellow text-sm uppercase tracking-wider mb-2">
                <x-heroicon-o-building-office-2 class="w-4 h-4" />
                <span>{{ __('messages.nav.' . $section) }}</span>
            </div>
        @endif
        <h1 class="font-display text-3xl md:text-5xl font-bold mb-3">{{ $page->title }}</h1>
        @if($page->meta_description)
            <p class="text-white/85 max-w-2xl">{{ strip_tags($page->meta_description) }}</p>
        @endif
    </div>
</section>

@php
    $breadcrumbs = [];
    if (!empty($section) && \Illuminate\Support\Facades\Route::has($section . '.index')) {
        $breadcrumbs[] = ['label' => __('messages.nav.' . $section), 'url' => route($section . '.index')];
    }
    $breadcrumbs[] = ['label' => $page->title];
@endphp
<x-breadcrumb :items="$breadcrumbs" />

<section class="py-12">
    <div class="container-page max-w-4xl">
        <article class="prose prose-slate max-w-none">
            {!! $page->body !!}
        </article>

        <div class="mt-10 pt-6 border-t flex flex-wrap items-center justify-between gap-4 text-xs text-gray-500">
            <div>{{ __('messages.footer.kemaskini') }}: {{ $page->updated_at->isoFormat('D MMM Y') }}</div>
            <div class="flex items-center gap-3 no-print">
                <a href="{{ route('page.pdf', $page->slug) }}" class="text-primary hover:underline flex items-center gap-1 font-semibold">
                    <x-heroicon-o-arrow-down-tray class="w-4 h-4" />
                    PDF
                </a>
                <button type="button" onclick="window.print()" class="text-primary hover:underline flex items-center gap-1">
                    <x-heroicon-o-printer class="w-4 h-4" />
                    {{ app()->getLocale() === 'ms' ? 'Cetak' : 'Print' }}
                </button>
            </div>
        </div>
    </div>
</section>
@endsection
