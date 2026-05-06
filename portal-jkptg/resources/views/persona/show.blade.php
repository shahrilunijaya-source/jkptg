@extends('layouts.public')

@php
    $key = str_replace('-', '_', $personaSlug);
    $title = __("messages.persona.{$key}.title");
    $summary = __("messages.persona.{$key}.summary");
@endphp

@section('title', $title . ' | ' . __('messages.site_name'))

@section('content')

<nav aria-label="Breadcrumb" class="bg-gray-50 border-b">
    <ol class="container-page py-3 text-sm flex flex-wrap gap-x-2 text-gray-600">
        <li><a href="{{ route('home') }}" class="hover:text-primary">{{ __('messages.nav.utama') }}</a></li>
        <li aria-hidden="true">&rsaquo;</li>
        <li><span class="text-primary font-medium">{{ $title }}</span></li>
    </ol>
</nav>

{{-- Hero --}}
<x-statement-band :label="$title" :title="__('messages.persona.welcome')" :subtitle="$summary">
    <form class="w-full max-w-2xl flex bg-white rounded-md overflow-hidden border border-slate-300" role="search" action="#" method="get">
        <label for="persona-search" class="sr-only">{{ __('messages.persona.search_label') }}</label>
        <input type="search" id="persona-search" name="q"
               placeholder="{{ __('messages.persona.search_placeholder') }}"
               class="flex-1 px-4 py-3 text-gray-800 outline-none border-0 focus:ring-0">
        <button type="submit" class="bg-primary px-5 text-white hover:bg-primary-light flex items-center gap-2">
            <x-heroicon-o-magnifying-glass class="w-4 h-4" />
            <span>{{ __('messages.persona.search_button') }}</span>
        </button>
    </form>
</x-statement-band>

{{-- Body: 2-col main + sidebar --}}
<section class="py-12">
    <div class="container-page grid grid-cols-1 lg:grid-cols-[1fr_280px] gap-10">

        <div>
            {{-- Service grid --}}
            <h2 class="font-display text-2xl font-bold text-primary mb-1">{{ __('messages.persona.services_for_you') }}</h2>
            <p class="text-gray-600 text-sm mb-6">{{ __('messages.persona.services_help') }}</p>
            @if($services->isEmpty())
                <x-state.empty icon="heroicon-o-archive-box-x-mark" :title="__('messages.states.empty.title')" tone="warning" />
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-12">
                    @foreach($services as $service)
                        <a href="#" class="group rounded-lg border bg-white p-5 hover:shadow hover:border-primary transition">
                            <div class="w-10 h-10 rounded bg-primary-pale text-primary flex items-center justify-center mb-3">
                                <x-heroicon-o-document-text class="w-5 h-5" />
                            </div>
                            <h3 class="font-semibold text-primary group-hover:underline mb-1">{{ $service->name }}</h3>
                            <p class="text-sm text-gray-600">{{ $service->summary }}</p>
                            @if($service->processing_days)
                                <p class="text-xs text-gray-500 mt-2 flex items-center gap-1">
                                    <x-heroicon-o-clock class="w-3.5 h-3.5" />
                                    {{ $service->processing_days }} {{ app()->getLocale() === 'ms' ? 'hari proses' : 'days processing' }}
                                </p>
                            @endif
                        </a>
                    @endforeach
                </div>
            @endif

            {{-- News --}}
            <h2 class="font-display text-2xl font-bold text-primary mb-1">{{ __('messages.persona.news_title') }}</h2>
            <p class="text-gray-600 text-sm mb-6">{{ __('messages.persona.news_help') }}</p>
            @if($news->isEmpty())
                <x-state.empty icon="heroicon-o-newspaper" :title="__('messages.states.empty.news')" tone="warning" />
            @else
                <ul class="divide-y rounded-lg border bg-white">
                    @foreach($news as $n)
                        <li class="p-4 flex flex-wrap items-center gap-3 hover:bg-gray-50 transition">
                            <div class="text-xs text-gray-500 w-24">{{ $n->published_at?->isoFormat('D MMM Y') }}</div>
                            <a href="#" class="font-semibold text-primary hover:underline flex-1">{{ $n->title }}</a>
                            @if($n->important)
                                <span class="text-xs px-2 py-0.5 bg-amber-100 text-amber-800 rounded">{{ app()->getLocale() === 'ms' ? 'Penting' : 'Important' }}</span>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        {{-- Sidebar --}}
        <aside class="space-y-6 lg:sticky lg:top-24 lg:self-start" aria-label="{{ __('messages.persona.sidebar_label') }}">
            <div class="bg-primary-pale rounded-lg p-5">
                <h3 class="font-display font-semibold text-primary mb-3 flex items-center gap-2">
                    <x-heroicon-o-bolt class="w-4 h-4" />
                    {{ __('messages.footer.pautan_pantas') }}
                </h3>
                <ul class="text-sm space-y-2">
                    <li><a href="#" class="text-primary hover:underline flex items-center gap-2"><x-heroicon-o-document-text class="w-4 h-4" />{{ __('messages.nav.panduan') }}</a></li>
                    <li><a href="#" class="text-primary hover:underline flex items-center gap-2"><x-heroicon-o-magnifying-glass class="w-4 h-4" />{{ __('messages.persona.status_apply') }}</a></li>
                    <li><a href="#" class="text-primary hover:underline flex items-center gap-2"><x-heroicon-o-question-mark-circle class="w-4 h-4" />{{ __('messages.utility.soalan_lazim') }}</a></li>
                    <li><a href="#" class="text-primary hover:underline flex items-center gap-2"><x-heroicon-o-phone class="w-4 h-4" />{{ __('messages.utility.hubungi') }}</a></li>
                    <li><a href="#" class="text-primary hover:underline flex items-center gap-2"><x-heroicon-o-megaphone class="w-4 h-4" />{{ __('messages.utility.aduan') }}</a></li>
                </ul>
            </div>
            <div class="bg-white border rounded-lg p-5">
                <h3 class="font-display font-semibold text-primary mb-2 flex items-center gap-2">
                    <x-heroicon-o-phone class="w-4 h-4" />
                    {{ __('messages.persona.quick_contact') }}
                </h3>
                <p class="text-sm text-gray-600 mb-3">{{ __('messages.persona.office_hours') }}</p>
                <div class="text-2xl font-display font-bold text-primary">{{ \App\Models\Setting::get('site.phone', '03-8000 8000') }}</div>
                <div class="text-xs text-gray-500">{{ \App\Models\Setting::get('site.email', 'webmaster@jkptg.gov.my') }}</div>
            </div>
            <div class="bg-amber-50 border border-amber-200 rounded-lg p-5">
                <div class="flex items-center gap-2 text-amber-900 font-semibold mb-1">
                    <x-heroicon-o-exclamation-triangle class="w-4 h-4" />
                    {{ __('messages.persona.scam_warning_title') }}
                </div>
                <p class="text-sm text-amber-900">{{ __('messages.persona.scam_warning') }}</p>
            </div>
        </aside>

    </div>
</section>

@endsection
