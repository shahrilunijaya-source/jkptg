@extends('layouts.public')

@section('title', $page->title . ' | ' . __('messages.site_name'))
@section('description', strip_tags($page->meta_description ?? ''))

@section('content')
<x-breadcrumb :items="[['label' => $page->title]]" />

<section class="container-page py-12 max-w-4xl">
    <h1 class="font-display text-3xl md:text-4xl font-bold text-primary mb-6">{{ $page->title }}</h1>
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
</section>
@endsection
