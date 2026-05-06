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
    .toc-link.active { color: #1B5E3F; font-weight: 700; background: #E8F0EA; }
    .prose-section { scroll-margin-top: 7rem; }
</style>
@endpush

@section('content')
{{-- Statement band — civic, with category eyebrow --}}
<section class="bg-canvas border-b border-slate-200">
    <div class="container-page py-12 md:py-16">
        <div class="flex items-center gap-2 mb-4">
            <x-heroicon-o-document-text class="w-4 h-4 text-bronze" aria-hidden="true" />
            <span class="eyebrow">{{ __('messages.nav.perkhidmatan') }}</span>
            <span class="text-slate-300" aria-hidden="true">/</span>
            <span class="eyebrow text-slate-500 capitalize">{{ $service->category }}</span>
        </div>
        <h1 class="text-[32px] md:text-[44px] font-bold text-canvas-ink leading-[1.1] tracking-tight mb-4 max-w-3xl">{{ $service->name }}</h1>
        <p class="text-[16px] md:text-[17px] text-slate-700 leading-relaxed max-w-3xl">{{ $service->summary }}</p>
    </div>
</section>

<x-breadcrumb :items="[
    ['label' => __('messages.nav.perkhidmatan'), 'href' => route('service.index')],
    ['label' => $service->name],
]" />

{{-- Mobile TOC --}}
<details class="lg:hidden bg-white border-b border-slate-200">
    <summary class="container-page py-3 flex items-center justify-between cursor-pointer text-[14px] font-semibold text-primary">
        <span>{{ __('messages.on_this_page') }}</span>
        <x-heroicon-o-chevron-down class="w-4 h-4" />
    </summary>
    <ul class="container-page pb-4 space-y-1">
        @foreach($sections as $s)
            <li><a href="#{{ $s['id'] }}" class="block py-1.5 text-[14px] text-slate-700 hover:text-primary">{{ $s['label'] }}</a></li>
        @endforeach
    </ul>
</details>

<section class="bg-white border-b border-slate-200">
    <div class="container-page py-12 grid grid-cols-1 lg:grid-cols-[260px_1fr] gap-10 lg:gap-16">

        {{-- Sticky left rail --}}
        <aside class="hidden lg:block sticky top-28 self-start">
            <div class="eyebrow-muted mb-4">{{ __('messages.on_this_page') }}</div>
            <ul class="space-y-px">
                @foreach($sections as $i => $s)
                    <li>
                        <a href="#{{ $s['id'] }}"
                           class="toc-link block px-3 py-2 text-[14px] text-slate-700 hover:bg-canvas-mute hover:text-primary transition-colors duration-150 rounded-sm {{ $i === 0 ? 'active' : '' }}">
                            {{ $s['label'] }}
                        </a>
                    </li>
                @endforeach
            </ul>

            @if($service->processing_days)
                <div class="mt-8 bg-canvas-mute border border-slate-200 rounded-sm p-5">
                    <div class="eyebrow-muted mb-2">{{ __('messages.service.processing_time') }}</div>
                    <div class="font-bold text-[36px] text-primary leading-none">{{ $service->processing_days }}</div>
                    <div class="text-[13px] text-slate-600 mt-1">{{ app()->getLocale() === 'ms' ? 'hari bekerja' : 'working days' }}</div>
                </div>
            @endif
        </aside>

        <article class="max-w-3xl space-y-12">
            <section id="tentang" class="prose-section">
                <h2 class="text-[24px] md:text-[26px] font-bold text-canvas-ink leading-tight tracking-tight mb-3">{{ __('messages.service.tentang') }}</h2>
                <p class="text-slate-700 leading-relaxed mb-4">{{ $service->summary }}</p>
                @if($service->sop_path)
                    <a href="#" class="inline-flex items-center gap-2 text-primary font-semibold hover:text-primary-800 text-[14px]">
                        <x-heroicon-o-arrow-down-tray class="w-4 h-4" />
                        <span>{{ __('messages.service.download_sop') }}</span>
                    </a>
                @endif
            </section>

            <section id="kelayakan" class="prose-section">
                <h2 class="text-[24px] md:text-[26px] font-bold text-canvas-ink leading-tight tracking-tight mb-3">{{ __('messages.service.kelayakan') }}</h2>
                @if($service->getTranslations('eligibility'))
                    <p class="text-slate-700 leading-relaxed">{{ $service->eligibility }}</p>
                @else
                    <x-state.empty :title="__('messages.states.empty.service_no_sop')" tone="warning" />
                @endif
            </section>

            <section id="proses" class="prose-section">
                <h2 class="text-[24px] md:text-[26px] font-bold text-canvas-ink leading-tight tracking-tight mb-5">{{ __('messages.service.proses') }}</h2>
                @php $steps = $service->process_steps ?? []; @endphp
                @if(!empty($steps))
                    <ol class="space-y-5 mb-7">
                        @foreach($steps as $i => $step)
                            <li class="flex gap-5">
                                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center font-bold text-[15px]">{{ $i + 1 }}</div>
                                <div class="pt-2">
                                    <h3 class="text-[15px] font-semibold text-canvas-ink leading-snug">{{ $step }}</h3>
                                </div>
                            </li>
                        @endforeach
                    </ol>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('service.sop', $service->slug) }}" class="btn-outline">
                            <x-heroicon-o-clipboard-document-list class="w-4 h-4" />
                            <span>{{ __('messages.service.sop_breadcrumb') }}</span>
                        </a>
                        <a href="{{ route('service.carta-alir', $service->slug) }}" class="btn-outline">
                            <x-heroicon-o-arrow-trending-down class="w-4 h-4" />
                            <span>{{ __('messages.service.carta_alir_breadcrumb') }}</span>
                        </a>
                    </div>
                @else
                    <x-state.empty :title="__('messages.states.empty.title')" tone="warning" />
                @endif
            </section>

            <section id="dokumen" class="prose-section">
                <h2 class="text-[24px] md:text-[26px] font-bold text-canvas-ink leading-tight tracking-tight mb-5">{{ __('messages.service.dokumen') }}</h2>
                @php $docs = $service->required_documents ?? []; @endphp
                @if(!empty($docs))
                    <ul class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach($docs as $doc)
                            <li class="flex items-start gap-3 bg-canvas-mute rounded-sm p-4 text-[14px] text-slate-700">
                                <x-heroicon-o-check-circle class="w-4 h-4 text-primary flex-shrink-0 mt-0.5" aria-hidden="true" />
                                <span>{{ $doc }}</span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-[14px] text-slate-500">{{ __('messages.service.docs_help') }}</p>
                @endif
            </section>

            <section id="borang" class="prose-section">
                <h2 class="text-[24px] md:text-[26px] font-bold text-canvas-ink leading-tight tracking-tight mb-5">{{ __('messages.service.borang') }}</h2>
                @if($relatedForms->isEmpty())
                    <x-state.empty icon="heroicon-o-document-text" :title="__('messages.states.empty.borang_search')" tone="info" />
                @else
                    <div class="space-y-3">
                        @foreach($relatedForms as $form)
                            <a href="#" class="flex items-center justify-between gap-4 bg-white border border-slate-200 rounded-sm p-4 hover:border-primary transition-colors duration-150 group">
                                <div class="flex items-center gap-3 min-w-0">
                                    <span class="icon-medallion w-9 h-9 flex-shrink-0">
                                        <x-heroicon-o-document-text class="w-4 h-4" />
                                    </span>
                                    <div class="min-w-0">
                                        <div class="font-semibold text-canvas-ink text-[14px] leading-snug truncate">{{ $form->name }}</div>
                                        <div class="text-[12px] text-slate-500 mt-0.5">v{{ $form->version }} · {{ $form->file_size_human }}</div>
                                    </div>
                                </div>
                                <span class="text-primary text-[13px] font-semibold flex items-center gap-1 flex-shrink-0">
                                    <span class="hidden sm:inline">{{ __('messages.service.muat_turun') }}</span>
                                    <x-heroicon-o-arrow-down-tray class="w-4 h-4" />
                                </span>
                            </a>
                        @endforeach
                    </div>
                @endif
            </section>

            <section id="faq" class="prose-section">
                <h2 class="text-[24px] md:text-[26px] font-bold text-canvas-ink leading-tight tracking-tight mb-5">{{ __('messages.service.faq') }}</h2>
                @if($relatedFaqs->isEmpty())
                    <x-state.empty icon="heroicon-o-question-mark-circle" :title="__('messages.states.empty.title')" tone="warning" />
                @else
                    <div class="space-y-3">
                        @foreach($relatedFaqs as $faq)
                            <details class="bg-white border border-slate-200 rounded-sm group">
                                <summary class="p-4 cursor-pointer flex items-center justify-between gap-4 text-[14.5px] font-semibold text-canvas-ink hover:text-primary transition-colors">
                                    <span>{{ $faq->question }}</span>
                                    <x-heroicon-o-chevron-down class="w-4 h-4 group-open:rotate-180 transition-transform flex-shrink-0" />
                                </summary>
                                <div class="px-4 pb-4 text-[14px] text-slate-700 leading-relaxed">{{ $faq->answer }}</div>
                            </details>
                        @endforeach
                    </div>
                @endif
            </section>
        </article>
    </div>
</section>

{{-- Sticky bottom-right Apply CTA --}}
<div class="fixed bottom-6 right-6 z-30 no-print">
    <a href="#" class="btn-primary shadow-[0_4px_16px_rgba(11,50,32,0.25)]">
        <x-heroicon-o-pencil-square class="w-4 h-4" />
        <span>{{ __('messages.service.mohon_sekarang') }}</span>
        <x-heroicon-o-arrow-right class="w-4 h-4" />
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
