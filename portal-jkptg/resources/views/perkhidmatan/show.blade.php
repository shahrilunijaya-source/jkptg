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

@php
$categoryImages = [
    'tanah'   => 'https://images.unsplash.com/photo-1531819318554-84abdf082937?auto=format&fit=crop&w=1920&q=80',
    'pajakan' => 'https://images.unsplash.com/photo-1680243032601-6b1ca903bc2a?auto=format&fit=crop&w=1920&q=80',
    'lesen'   => 'https://images.unsplash.com/photo-1695169152266-d9ac86fab9c5?auto=format&fit=crop&w=1920&q=80',
    'strata'  => 'https://images.unsplash.com/photo-1611924779080-d20389c1f56c?auto=format&fit=crop&w=1920&q=80',
];
$heroBg = $categoryImages[$service->category] ?? null;
@endphp

@section('content')
<section class="relative text-white py-10 overflow-hidden">
    @if($heroBg)
        <div class="absolute inset-0"
             style="background-image: linear-gradient(180deg, rgba(15,30,51,0.55) 0%, rgba(15,30,51,0.88) 100%), url('{{ $heroBg }}'); background-size: cover; background-position: center;" aria-hidden="true"></div>
    @else
        <div class="absolute inset-0 bg-primary" aria-hidden="true"></div>
    @endif
    <div class="relative container-page">
        <div class="text-jata-yellow text-sm uppercase tracking-wider mb-2 flex items-center gap-2">
            <x-heroicon-o-document-text class="w-4 h-4" />
            <span class="capitalize">{{ $service->category }}</span>
        </div>
        <h1 class="font-display text-3xl md:text-5xl font-bold mb-3">{{ $service->name }}</h1>
        <p class="text-white/85 max-w-3xl">{{ $service->summary }}</p>
    </div>
</section>

<x-breadcrumb :items="[
    ['label' => __('messages.nav.perkhidmatan'), 'href' => route('service.index')],
    ['label' => $service->name],
]" />

{{-- Mobile dropdown nav --}}
<div class="lg:hidden bg-white border-b" x-data="{ open: false }">
    <button type="button" @click="open = !open"
        class="container-page w-full py-3 flex items-center justify-between cursor-pointer font-semibold text-primary">
        <span>{{ __('messages.on_this_page') }}</span>
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
            class="w-4 h-4 transition-transform duration-300" :class="open ? 'rotate-180' : 'rotate-0'">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
        </svg>
    </button>
    <div x-show="open"
        x-transition:enter="transition-all ease-[cubic-bezier(0.23,1,0.32,1)] duration-300"
        x-transition:enter-start="opacity-0 max-h-0"
        x-transition:enter-end="opacity-100 max-h-64"
        x-transition:leave="transition-all ease-[cubic-bezier(0.23,1,0.32,1)] duration-200"
        x-transition:leave-start="opacity-100 max-h-64"
        x-transition:leave-end="opacity-0 max-h-0"
        class="overflow-hidden">
        <ul class="container-page pb-4 text-sm space-y-2">
            @foreach($sections as $s)
                <li><a href="#{{ $s['id'] }}" @click="open = false" class="text-primary hover:underline">{{ $s['label'] }}</a></li>
            @endforeach
        </ul>
    </div>
</div>

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
                <ol class="space-y-4 mb-6">
                    @foreach($steps as $i => $step)
                        <li class="flex gap-4">
                            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center font-bold">{{ $i + 1 }}</div>
                            <div>
                                <h3 class="font-semibold">{{ $step }}</h3>
                            </div>
                        </li>
                    @endforeach
                </ol>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('service.sop', $service->slug) }}" class="text-sm font-semibold text-primary border border-primary px-4 py-2 rounded hover:bg-primary hover:text-white transition flex items-center gap-1">
                        <x-heroicon-o-clipboard-document-list class="w-4 h-4" />
                        {{ __('messages.service.sop_breadcrumb') }}
                    </a>
                    <a href="{{ route('service.carta-alir', $service->slug) }}" class="text-sm font-semibold text-primary border border-primary px-4 py-2 rounded hover:bg-primary hover:text-white transition flex items-center gap-1">
                        <x-heroicon-o-arrow-trending-down class="w-4 h-4" />
                        {{ __('messages.service.carta_alir_breadcrumb') }}
                    </a>
                </div>
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
                        <a href="#" class="reveal-on-scroll flex items-center justify-between p-4 border rounded hover:border-primary hover-lift" style="--reveal-delay:{{ $loop->index * 40 }}ms">
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
                <div class="space-y-2" x-data="{ active: null }">
                    @foreach($relatedFaqs as $i => $faq)
                        <div class="border rounded-lg overflow-hidden">
                            <button type="button"
                                class="w-full px-4 py-4 cursor-pointer font-semibold flex items-center justify-between text-left hover:bg-gray-50 transition-colors duration-150"
                                @click="active = active === {{ $i }} ? null : {{ $i }}"
                                :aria-expanded="active === {{ $i }}">
                                <span>{{ $faq->question }}</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                    class="w-4 h-4 flex-shrink-0 transition-transform duration-300"
                                    :class="active === {{ $i }} ? 'rotate-180' : 'rotate-0'">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                </svg>
                            </button>
                            <div x-show="active === {{ $i }}"
                                x-transition:enter="transition-all ease-[cubic-bezier(0.23,1,0.32,1)] duration-300"
                                x-transition:enter-start="opacity-0 max-h-0"
                                x-transition:enter-end="opacity-100 max-h-96"
                                x-transition:leave="transition-all ease-[cubic-bezier(0.23,1,0.32,1)] duration-200"
                                x-transition:leave-start="opacity-100 max-h-96"
                                x-transition:leave-end="opacity-0 max-h-0"
                                class="overflow-hidden">
                                <div class="px-4 pb-4 pt-3 text-sm text-gray-700 border-t border-gray-100">{{ $faq->answer }}</div>
                            </div>
                        </div>
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
