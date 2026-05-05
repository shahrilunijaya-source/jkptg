@extends('layouts.public')

@section('title', $service->name . ' | ' . __('messages.site_name'))

@php
    $sections = [
        ['id' => 'tentang', 'label' => __('messages.service.tentang')],
        ['id' => 'kelayakan', 'label' => __('messages.service.kelayakan')],
        ['id' => 'proses', 'label' => __('messages.service.proses')],
        ['id' => 'dokumen', 'label' => __('messages.service.dokumen')],
        ['id' => 'borang', 'label' => __('messages.service.borang')],
        ['id' => 'faq', 'label' => __('messages.service.faq')],
    ];
@endphp

@push('head')
<style>
    .nav-link.active { color: #243D57; font-weight: 600; border-left-color: #243D57; }
    .prose-section { scroll-margin-top: 7rem; }
</style>
@endpush

@section('content')
<x-breadcrumb :items="[
    ['label' => __('messages.nav.perkhidmatan'), 'href' => route('service.index')],
    ['label' => $service->name],
]" />

<section class="bg-primary text-white py-10">
    <div class="container-page">
        <div class="text-jata-yellow text-sm uppercase tracking-wider mb-2 flex items-center gap-2">
            <x-heroicon-o-document-text class="w-4 h-4" />
            <span class="capitalize">{{ $service->category }}</span>
        </div>
        <h1 class="font-display text-3xl md:text-5xl font-bold mb-3">{{ $service->name }}</h1>
        <p class="text-white/85 max-w-3xl">{{ $service->summary }}</p>
    </div>
</section>

{{-- Mobile dropdown nav --}}
<details class="lg:hidden bg-white border-b">
    <summary class="container-page py-3 flex items-center justify-between cursor-pointer font-semibold text-primary">
        <span>{{ __('messages.on_this_page') }}</span>
        <x-heroicon-o-chevron-down class="w-4 h-4" />
    </summary>
    <ul class="container-page pb-4 text-sm space-y-2">
        @foreach($sections as $s)
            <li><a href="#{{ $s['id'] }}" class="text-primary hover:underline">{{ $s['label'] }}</a></li>
        @endforeach
    </ul>
</details>

<section class="container-page py-10 grid grid-cols-1 lg:grid-cols-[240px_1fr] gap-10">

    {{-- Sticky left rail --}}
    <nav aria-label="{{ __('messages.on_this_page') }}" class="hidden lg:block sticky top-24 self-start text-sm">
        <div class="text-xs uppercase font-semibold text-gray-500 tracking-wider mb-3">{{ __('messages.on_this_page') }}</div>
        <ul class="border-l border-gray-200 space-y-1">
            @foreach($sections as $i => $s)
                <li>
                    <a href="#{{ $s['id'] }}"
                       class="nav-link block px-3 py-1.5 border-l-2 -ml-px {{ $i === 0 ? 'active' : 'border-transparent' }} text-gray-700 hover:text-primary">
                        {{ $s['label'] }}
                    </a>
                </li>
            @endforeach
        </ul>
        @if($service->processing_days)
            <div class="mt-6 p-4 bg-primary-pale rounded">
                <div class="text-xs text-primary font-semibold mb-1">{{ __('messages.service.processing_time') }}</div>
                <div class="text-2xl font-display font-bold text-primary">{{ $service->processing_days }} {{ app()->getLocale() === 'ms' ? 'hari' : 'days' }}</div>
            </div>
        @endif
    </nav>

    <article class="max-w-3xl space-y-12">
        <section id="tentang" class="prose-section">
            <h2 class="font-display text-2xl font-bold text-primary mb-3">{{ __('messages.service.tentang') }}</h2>
            <p class="mb-3">{{ $service->summary }}</p>
            @if($service->sop_path)
                <a href="#" class="inline-flex items-center gap-2 text-primary hover:underline text-sm font-semibold">
                    <x-heroicon-o-arrow-down-tray class="w-4 h-4" />
                    {{ __('messages.service.download_sop') }}
                </a>
            @endif
        </section>

        <section id="kelayakan" class="prose-section">
            <h2 class="font-display text-2xl font-bold text-primary mb-3">{{ __('messages.service.kelayakan') }}</h2>
            @if($service->getTranslations('eligibility'))
                <p>{{ $service->eligibility }}</p>
            @else
                <x-state.empty :title="__('messages.states.empty.service_no_sop')" tone="warning" />
            @endif
        </section>

        <section id="proses" class="prose-section">
            <h2 class="font-display text-2xl font-bold text-primary mb-3">{{ __('messages.service.proses') }}</h2>
            @php $steps = $service->process_steps ?? []; @endphp
            @if(!empty($steps))
                <ol class="space-y-4">
                    @foreach($steps as $i => $step)
                        <li class="flex gap-4">
                            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center font-bold">{{ $i + 1 }}</div>
                            <div>
                                <h3 class="font-semibold">{{ $step }}</h3>
                            </div>
                        </li>
                    @endforeach
                </ol>
            @else
                <x-state.empty :title="__('messages.states.empty.title')" tone="warning" />
            @endif
        </section>

        <section id="dokumen" class="prose-section">
            <h2 class="font-display text-2xl font-bold text-primary mb-3">{{ __('messages.service.dokumen') }}</h2>
            @php $docs = $service->required_documents ?? []; @endphp
            @if(!empty($docs))
                <ul class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-sm">
                    @foreach($docs as $doc)
                        <li class="flex items-center gap-2 p-3 border rounded">
                            <x-heroicon-o-check-circle class="w-4 h-4 text-primary flex-shrink-0" />
                            <span>{{ $doc }}</span>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-gray-600 text-sm">{{ __('messages.service.docs_help') }}</p>
            @endif
        </section>

        <section id="borang" class="prose-section">
            <h2 class="font-display text-2xl font-bold text-primary mb-3">{{ __('messages.service.borang') }}</h2>
            @if($relatedForms->isEmpty())
                <x-state.empty icon="heroicon-o-document-text" :title="__('messages.states.empty.borang_search')" tone="info" />
            @else
                <div class="space-y-3">
                    @foreach($relatedForms as $form)
                        <a href="#" class="flex items-center justify-between p-4 border rounded hover:border-primary hover:shadow transition">
                            <div class="flex items-center gap-3">
                                <x-heroicon-o-document-text class="w-5 h-5 text-primary flex-shrink-0" />
                                <div>
                                    <div class="font-semibold">{{ $form->name }}</div>
                                    <div class="text-xs text-gray-500">v{{ $form->version }} - {{ $form->file_size_human }}</div>
                                </div>
                            </div>
                            <span class="text-primary text-sm font-semibold flex items-center gap-1">
                                {{ __('messages.service.muat_turun') }}
                                <x-heroicon-o-arrow-down-tray class="w-4 h-4" />
                            </span>
                        </a>
                    @endforeach
                </div>
            @endif
        </section>

        <section id="faq" class="prose-section">
            <h2 class="font-display text-2xl font-bold text-primary mb-3">{{ __('messages.service.faq') }}</h2>
            @if($relatedFaqs->isEmpty())
                <x-state.empty icon="heroicon-o-question-mark-circle" :title="__('messages.states.empty.title')" tone="warning" />
            @else
                <div class="space-y-2">
                    @foreach($relatedFaqs as $faq)
                        <details class="border rounded">
                            <summary class="p-4 cursor-pointer font-semibold flex items-center justify-between">
                                <span>{{ $faq->question }}</span>
                                <x-heroicon-o-chevron-down class="w-4 h-4" />
                            </summary>
                            <div class="p-4 pt-0 text-sm text-gray-700">{{ $faq->answer }}</div>
                        </details>
                    @endforeach
                </div>
            @endif
        </section>
    </article>
</section>

{{-- Sticky bottom-right Apply CTA --}}
<div class="fixed bottom-6 right-6 z-30 flex flex-col gap-3 items-end no-print">
    <a href="#" class="bg-primary hover:bg-primary-mute text-white font-semibold px-5 py-3 rounded-full shadow-xl flex items-center gap-2">
        <x-heroicon-o-pencil-square class="w-4 h-4" />
        {{ __('messages.service.mohon_sekarang') }}
        <x-heroicon-o-arrow-right class="w-4 h-4" />
    </a>
</div>
@endsection

@push('scripts')
<script>
  const sections = document.querySelectorAll('.prose-section');
  const links = document.querySelectorAll('.nav-link');
  if (sections.length && links.length && 'IntersectionObserver' in window) {
    const obs = new IntersectionObserver(entries => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          links.forEach(l => l.classList.remove('active'));
          const link = document.querySelector('.nav-link[href="#' + entry.target.id + '"]');
          if (link) link.classList.add('active');
        }
      });
    }, { rootMargin: '-30% 0px -60% 0px' });
    sections.forEach(s => obs.observe(s));
  }
</script>
@endpush
