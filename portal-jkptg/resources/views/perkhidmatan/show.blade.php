@extends('layouts.public')

@section('title', $service->name . ' | ' . __('messages.site_name'))

@php
    $sections = [
        ['id' => 'tentang',    'label' => __('messages.service.tentang')],
        ['id' => 'kelayakan',  'label' => __('messages.service.kelayakan')],
        ['id' => 'proses',     'label' => __('messages.service.proses')],
        ['id' => 'dokumen',    'label' => __('messages.service.dokumen')],
        ['id' => 'borang',     'label' => __('messages.service.borang')],
        ['id' => 'faq',        'label' => __('messages.service.faq')],
    ];
@endphp

@push('head')
<style>
    .toc-link.active { @apply bg-slate-900 text-white; }
    .prose-section { scroll-margin-top: 7rem; }
</style>
@endpush

@section('content')
{{-- Statement band --}}
<section class="border-b border-slate-200 bg-white">
    <div class="container-page py-10 md:py-14">
        <div class="flex flex-wrap items-baseline gap-x-4 gap-y-1 mb-5">
            <span class="mono-cap text-slate-500">02 · {{ __('messages.nav.perkhidmatan') }}</span>
            <span class="mono-cap text-slate-300" aria-hidden="true">/</span>
            <span class="mono-cap text-slate-700">{{ ucfirst($service->category) }}</span>
            <span class="mono-cap text-slate-300" aria-hidden="true">/</span>
            <span class="mono-cap text-slate-900">SVC-{{ str_pad($service->id, 3, '0', STR_PAD_LEFT) }}</span>
        </div>
        <h1 class="text-[32px] md:text-[44px] font-bold text-slate-900 leading-[1.1] tracking-tight mb-3 max-w-3xl">{{ $service->name }}</h1>
        <p class="text-[16px] text-slate-600 leading-relaxed max-w-3xl">{{ $service->summary }}</p>
    </div>
</section>

<x-breadcrumb :items="[
    ['label' => __('messages.nav.perkhidmatan'), 'href' => route('service.index')],
    ['label' => $service->name],
]" />

{{-- Mobile TOC dropdown --}}
<details class="lg:hidden bg-white border-b border-slate-200">
    <summary class="container-page py-3 flex items-center justify-between cursor-pointer mono-cap text-slate-900">
        <span>{{ __('messages.on_this_page') }}</span>
        <x-heroicon-o-chevron-down class="w-4 h-4" />
    </summary>
    <ul class="container-page pb-4 space-y-1">
        @foreach($sections as $s)
            <li><a href="#{{ $s['id'] }}" class="block py-1.5 text-[14px] text-slate-700 hover:text-primary">{{ $s['label'] }}</a></li>
        @endforeach
    </ul>
</details>

<section class="bg-slate-50 border-b border-slate-200">
    <div class="container-page py-12 grid grid-cols-1 lg:grid-cols-[260px_1fr] gap-10 lg:gap-16">

        {{-- Sticky left rail --}}
        <aside class="hidden lg:block sticky top-28 self-start">
            <div class="mono-cap text-slate-500 mb-4">{{ __('messages.on_this_page') }}</div>
            <ul class="space-y-px">
                @foreach($sections as $i => $s)
                    <li>
                        <a href="#{{ $s['id'] }}"
                           class="toc-link flex items-baseline gap-3 px-3 py-2 text-[13px] text-slate-700 hover:bg-slate-100 transition-colors duration-150 {{ $i === 0 ? 'active' : '' }}">
                            <span class="mono-meta tabular-nums opacity-70">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                            <span class="font-medium">{{ $s['label'] }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>

            @if($service->processing_days)
                <div class="mt-8 border border-slate-200 bg-white p-5">
                    <div class="mono-cap text-slate-500 mb-2">{{ __('messages.service.processing_time') }}</div>
                    <div class="font-mono tabular-nums text-[40px] text-slate-900 leading-none">{{ str_pad($service->processing_days, 2, '0', STR_PAD_LEFT) }}</div>
                    <div class="mono-meta text-slate-500 mt-1">{{ app()->getLocale() === 'ms' ? 'HARI BEKERJA' : 'WORKING DAYS' }}</div>
                </div>
            @endif
        </aside>

        <article class="max-w-3xl space-y-14">
            <section id="tentang" class="prose-section">
                <h2 class="mono-cap text-slate-500 mb-3">01 · {{ __('messages.service.tentang') }}</h2>
                <div class="text-slate-700 leading-relaxed mb-4">{{ $service->summary }}</div>
                @if($service->sop_path)
                    <a href="#" class="inline-flex items-center gap-2 mono-cap text-primary hover:text-primary-700">
                        <span>↓</span>
                        <span>{{ __('messages.service.download_sop') }}</span>
                    </a>
                @endif
            </section>

            <section id="kelayakan" class="prose-section">
                <h2 class="mono-cap text-slate-500 mb-3">02 · {{ __('messages.service.kelayakan') }}</h2>
                @if($service->getTranslations('eligibility'))
                    <p class="text-slate-700 leading-relaxed">{{ $service->eligibility }}</p>
                @else
                    <x-state.empty :title="__('messages.states.empty.service_no_sop')" tone="warning" />
                @endif
            </section>

            <section id="proses" class="prose-section">
                <h2 class="mono-cap text-slate-500 mb-4">03 · {{ __('messages.service.proses') }}</h2>
                @php $steps = $service->process_steps ?? []; @endphp
                @if(!empty($steps))
                    <ol class="border border-slate-200 bg-white divide-y divide-slate-200 mb-6">
                        @foreach($steps as $i => $step)
                            <li class="flex gap-5 p-4">
                                <div class="font-mono tabular-nums text-[14px] text-slate-400 w-12 flex-shrink-0">
                                    {{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}/{{ str_pad(count($steps), 2, '0', STR_PAD_LEFT) }}
                                </div>
                                <div class="text-[15px] text-slate-900 font-medium">{{ $step }}</div>
                            </li>
                        @endforeach
                    </ol>
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('service.sop', $service->slug) }}" class="btn-outline">
                            <span class="mono-cap">SOP</span>
                            <span aria-hidden="true">↗</span>
                        </a>
                        <a href="{{ route('service.carta-alir', $service->slug) }}" class="btn-outline">
                            <span class="mono-cap">CARTA ALIR</span>
                            <span aria-hidden="true">↗</span>
                        </a>
                    </div>
                @else
                    <x-state.empty :title="__('messages.states.empty.title')" tone="warning" />
                @endif
            </section>

            <section id="dokumen" class="prose-section">
                <h2 class="mono-cap text-slate-500 mb-4">04 · {{ __('messages.service.dokumen') }}</h2>
                @php $docs = $service->required_documents ?? []; @endphp
                @if(!empty($docs))
                    <ul class="grid grid-cols-1 sm:grid-cols-2 gap-px bg-slate-200 border border-slate-200">
                        @foreach($docs as $i => $doc)
                            <li class="flex items-baseline gap-3 bg-white p-4 text-[14px] text-slate-700">
                                <span class="mono-meta tabular-nums text-slate-400 w-8 flex-shrink-0">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                <span>{{ $doc }}</span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-[14px] text-slate-500">{{ __('messages.service.docs_help') }}</p>
                @endif
            </section>

            <section id="borang" class="prose-section">
                <h2 class="mono-cap text-slate-500 mb-4">05 · {{ __('messages.service.borang') }}</h2>
                @if($relatedForms->isEmpty())
                    <x-state.empty icon="heroicon-o-document-text" :title="__('messages.states.empty.borang_search')" tone="info" />
                @else
                    <ul class="border border-slate-200 bg-white divide-y divide-slate-200">
                        @foreach($relatedForms as $form)
                            <li>
                                <a href="#" class="flex items-baseline justify-between gap-4 p-4 hover:bg-slate-50 transition-colors duration-150">
                                    <div class="flex items-baseline gap-3 min-w-0 flex-1">
                                        <span class="mono-cap text-slate-400">FRM</span>
                                        <div class="min-w-0">
                                            <div class="font-medium text-slate-900 text-[15px] leading-snug truncate">{{ $form->name }}</div>
                                            <div class="mono-meta tabular-nums text-slate-500 mt-0.5">v{{ $form->version }} · {{ $form->file_size_human }}</div>
                                        </div>
                                    </div>
                                    <span class="mono-cap text-primary flex-shrink-0">↓ {{ __('messages.service.muat_turun') }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </section>

            <section id="faq" class="prose-section">
                <h2 class="mono-cap text-slate-500 mb-4">06 · {{ __('messages.service.faq') }}</h2>
                @if($relatedFaqs->isEmpty())
                    <x-state.empty icon="heroicon-o-question-mark-circle" :title="__('messages.states.empty.title')" tone="warning" />
                @else
                    <ul class="border border-slate-200 bg-white divide-y divide-slate-200">
                        @foreach($relatedFaqs as $i => $faq)
                            <li>
                                <details class="group">
                                    <summary class="flex items-baseline gap-4 p-4 cursor-pointer hover:bg-slate-50 transition-colors duration-150">
                                        <span class="mono-cap text-slate-400 tabular-nums w-8 flex-shrink-0">Q{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                        <span class="flex-1 font-medium text-slate-900 text-[15px] leading-snug">{{ $faq->question }}</span>
                                        <span class="text-slate-400 group-open:rotate-180 transition-transform" aria-hidden="true">▾</span>
                                    </summary>
                                    <div class="px-4 pb-4 pl-[3.5rem] text-[14px] text-slate-700 leading-relaxed">{{ $faq->answer }}</div>
                                </details>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </section>
        </article>
    </div>
</section>

{{-- Sticky bottom-right Apply CTA — sharp rectangle --}}
<div class="fixed bottom-6 right-6 z-30 no-print">
    <a href="#" class="btn-primary">
        <span>{{ __('messages.service.mohon_sekarang') }}</span>
        <span aria-hidden="true">→</span>
    </a>
</div>
@endsection

@push('scripts')
<script>
  const sections = document.querySelectorAll('.prose-section');
  const links = document.querySelectorAll('.toc-link');
  if (sections.length && links.length && 'IntersectionObserver' in window) {
    const obs = new IntersectionObserver(entries => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          links.forEach(l => l.classList.remove('active'));
          const link = document.querySelector('.toc-link[href="#' + entry.target.id + '"]');
          if (link) link.classList.add('active');
        }
      });
    }, { rootMargin: '-30% 0px -60% 0px' });
    sections.forEach(s => obs.observe(s));
  }
</script>
@endpush
