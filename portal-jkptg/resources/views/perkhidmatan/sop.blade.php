@extends('layouts.public')

@section('title', __('messages.service.sop_title', ['name' => $service->name]))

@section('content')
<x-breadcrumb :items="[
    ['label' => __('messages.nav.perkhidmatan'), 'href' => route('service.index')],
    ['label' => $service->name, 'href' => route('service.show', $service->slug)],
    ['label' => __('messages.service.sop_breadcrumb')],
]" />

<section class="bg-gradient-to-br from-primary to-primary-mute text-white py-12 no-print">
    <div class="container-page">
        <div class="flex items-center gap-2 text-jata-yellow text-sm uppercase tracking-wider mb-2">
            <x-heroicon-o-clipboard-document-list class="w-4 h-4" />
            <span>{{ __('messages.service.sop_breadcrumb') }}</span>
        </div>
        <h1 class="font-display text-3xl md:text-5xl font-bold mb-3">{{ $service->name }}</h1>
        <p class="text-white/85 max-w-2xl">{{ __('messages.service.sop_intro') }}</p>
    </div>
</section>

<section class="py-12 print:py-4">
    <div class="container-page max-w-3xl">
        <div class="hidden print:block mb-6">
            <h1 class="font-display text-2xl font-bold text-primary">{{ __('messages.service.sop_print_title') }}: {{ $service->name }}</h1>
        </div>

        <div class="flex items-center justify-between mb-6 no-print gap-3 flex-wrap">
            <p class="text-sm text-gray-600">{{ __('messages.service.processing_time') }}: <strong>{{ $service->processing_days }} {{ __('messages.service.days') }}</strong></p>
            <div class="flex gap-2">
                <a href="{{ route('service.sop.pdf', $service->slug) }}" class="text-sm font-semibold bg-primary text-white px-4 py-2 rounded hover:bg-primary-dark transition flex items-center gap-1">
                    <x-heroicon-o-arrow-down-tray class="w-4 h-4" />
                    PDF
                </a>
                <button type="button" onclick="window.print()" class="text-sm font-semibold text-primary border border-primary px-4 py-2 rounded hover:bg-primary hover:text-white transition flex items-center gap-1">
                    <x-heroicon-o-printer class="w-4 h-4" />
                    {{ __('messages.service.print') }}
                </button>
            </div>
        </div>

        <ol class="space-y-4">
            @foreach(($service->process_steps ?? []) as $i => $step)
                <li class="bg-white border rounded-lg p-5 flex gap-4">
                    <div class="w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center font-bold flex-shrink-0">{{ $i + 1 }}</div>
                    <div>
                        <h2 class="font-display font-bold text-primary mb-1">{{ $step }}</h2>
                        <p class="text-sm text-gray-600">{{ __('messages.service.sop_step_help') }}</p>
                    </div>
                </li>
            @endforeach
        </ol>

        @if(!($service->process_steps ?? []))
            <x-state.empty icon="heroicon-o-clipboard-document-list" :title="__('messages.states.empty.service_no_sop')" tone="warning" />
        @endif

        <div class="mt-10 rounded-lg border border-jata-yellow/40 bg-jata-yellow/10 p-5 no-print">
            <h2 class="font-semibold text-primary mb-2">{{ __('messages.service.sop_disclaimer_title') }}</h2>
            <p class="text-sm text-gray-700">{{ __('messages.service.sop_disclaimer_body') }}</p>
        </div>
    </div>
</section>
@endsection
