@extends('layouts.public')

@section('title', __('messages.service.carta_alir_title', ['name' => $service->name]))

@section('content')
<section class="bg-gradient-to-br from-primary to-primary-mute text-white py-12 no-print">
    <div class="container-page">
        <div class="flex items-center gap-2 text-jata-yellow text-sm uppercase tracking-wider mb-2">
            <x-heroicon-o-arrow-trending-down class="w-4 h-4" />
            <span>{{ __('messages.service.carta_alir_breadcrumb') }}</span>
        </div>
        <h1 class="font-display text-3xl md:text-5xl font-bold mb-3">{{ $service->name }}</h1>
        <p class="text-white/85 max-w-2xl">{{ __('messages.service.carta_alir_intro') }}</p>
    </div>
</section>

<x-breadcrumb :items="[
    ['label' => __('messages.nav.perkhidmatan'), 'href' => route('service.index')],
    ['label' => $service->name, 'href' => route('service.show', $service->slug)],
    ['label' => __('messages.service.carta_alir_breadcrumb')],
]" />

<section class="py-12">
    <div class="container-page max-w-2xl">
        <div class="flex justify-end mb-4 no-print gap-2">
            <a href="{{ route('service.carta-alir.pdf', $service->slug) }}" class="text-sm font-semibold bg-primary text-white px-4 py-2 rounded hover:bg-primary-dark transition flex items-center gap-1">
                <x-heroicon-o-arrow-down-tray class="w-4 h-4" />
                PDF
            </a>
            <button type="button" onclick="window.print()" class="text-sm font-semibold text-primary border border-primary px-4 py-2 rounded hover:bg-primary hover:text-white transition flex items-center gap-1">
                <x-heroicon-o-printer class="w-4 h-4" />
                {{ __('messages.service.print') }}
            </button>
        </div>

        @php $steps = $service->process_steps ?? []; @endphp

        <div class="flex flex-col items-stretch gap-0">
            <div class="bg-jata-yellow text-primary font-bold rounded-lg p-4 text-center shadow">
                {{ __('messages.service.carta_alir_start') }}
            </div>
            @foreach($steps as $i => $step)
                <div class="flex justify-center text-primary">
                    <x-heroicon-o-arrow-down class="w-6 h-6 my-1" />
                </div>
                <div class="bg-white border-2 border-primary rounded-lg p-4 shadow text-center">
                    <div class="text-xs text-jata-red font-semibold uppercase tracking-wider mb-1">{{ __('messages.service.step') }} {{ $i + 1 }}</div>
                    <div class="font-semibold text-primary">{{ $step }}</div>
                </div>
            @endforeach
            <div class="flex justify-center text-primary">
                <x-heroicon-o-arrow-down class="w-6 h-6 my-1" />
            </div>
            <div class="bg-primary text-white font-bold rounded-lg p-4 text-center shadow">
                {{ __('messages.service.carta_alir_end') }}
            </div>
        </div>

        @if(empty($steps))
            <x-state.empty icon="heroicon-o-arrow-trending-down" :title="__('messages.states.empty.service_no_sop')" tone="warning" />
        @endif
    </div>
</section>
@endsection
