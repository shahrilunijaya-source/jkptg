@extends('layouts.public')

@section('title', __('messages.site_name'))

@php
    $tagline = \App\Models\Setting::get('site.tagline');
    $tagline = is_array($tagline) ? ($tagline[app()->getLocale()] ?? '') : $tagline;
    $services = \App\Models\Service::where('active', true)->orderBy('sort')->limit(6)->get();
    $beritas = \App\Models\News::whereNotNull('published_at')->where('type', 'berita')->orderByDesc('published_at')->limit(3)->get();
    $pengumumans = \App\Models\News::whereNotNull('published_at')->where('type', 'pengumuman')->orderByDesc('published_at')->limit(3)->get();
    $tenders = \App\Models\Tender::where('status', 'open')->orderBy('closes_at')->limit(3)->get();
    $heroImage = 'https://images.unsplash.com/photo-1592723905426-1181bf431d3a?auto=format&fit=crop&w=1920&q=80';
@endphp

@section('content')

{{-- HERO: Stage 5 variant A overlay --}}
<section class="relative min-h-[640px] md:min-h-[720px] flex flex-col justify-between text-white overflow-hidden">
    <div class="absolute inset-0 bg-cover bg-center"
         style="background-image: linear-gradient(180deg, rgba(15,30,51,0.55) 0%, rgba(15,30,51,0.85) 100%), url('{{ $heroImage }}');"
         aria-hidden="true"></div>

    <div class="relative container-page pt-16 md:pt-24 max-w-4xl">
        <p class="hero-animate font-display uppercase tracking-wider text-jata-yellow text-sm mb-3" style="--anim-delay:0ms">{{ __('messages.site_name') }}</p>
        <h1 class="hero-animate font-display font-bold text-4xl md:text-6xl leading-tight mb-4" style="--anim-delay:100ms">{{ $tagline }}</h1>
        <p class="hero-animate text-base md:text-lg text-white/90 max-w-2xl" style="--anim-delay:230ms">
            {{ app()->getLocale() === 'ms'
                ? 'Kami merekodkan, melindungi dan menguruskan harta tanah negara untuk manfaat rakyat Malaysia.'
                : 'We record, protect, and manage national land assets for the benefit of the Malaysian people.' }}
        </p>
        <div class="hero-animate mt-6 flex flex-wrap gap-3" style="--anim-delay:350ms">
            <a href="#berita" class="bg-white text-primary font-semibold px-5 py-2.5 rounded hover:bg-primary-pale transition">
                {{ app()->getLocale() === 'ms' ? 'Pengumuman Terkini' : 'Latest Announcements' }}
            </a>
            <a href="#tender" class="border border-white/60 text-white px-5 py-2.5 rounded hover:bg-white/10 transition">
                {{ app()->getLocale() === 'ms' ? 'Iklan Perolehan' : 'Procurement Notices' }}
            </a>
        </div>
    </div>

    {{-- 3 persona doors floating --}}
    <div class="relative container-page pb-12 md:pb-20 mt-16">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach([
                ['orang-awam', 'users', __('messages.persona.orang_awam.title'), __('messages.persona.orang_awam.summary'), __('messages.persona.cta_start')],
                ['kementerian-jabatan', 'building-office-2', __('messages.persona.kementerian_jabatan.title'), __('messages.persona.kementerian_jabatan.summary'), __('messages.persona.cta_start')],
                ['warga-jkptg', 'identification', __('messages.persona.warga_jkptg.title'), __('messages.persona.warga_jkptg.summary'), __('messages.persona.cta_login')],
            ] as [$slug, $icon, $title, $summary, $cta])
                <a href="{{ route('persona.show', $slug) }}"
                   class="group hero-animate bg-white/10 backdrop-blur-md hover:bg-white/20 hover:-translate-y-1 border border-white/30 rounded-lg p-6 transition focus:outline-none focus:ring-2 focus:ring-jata-yellow"
                   style="--anim-delay:{{ 460 + $loop->index * 100 }}ms">
                    <x-dynamic-component :component="'heroicon-o-' . $icon" class="w-10 h-10 text-jata-yellow mb-3" />
                    <h2 class="font-display font-bold text-xl mb-1">{{ $title }}</h2>
                    <p class="text-sm text-white/85">{{ $summary }}</p>
                    <div class="mt-3 text-jata-yellow group-hover:translate-x-1 transition flex items-center gap-1 text-sm font-semibold">
                        {{ $cta }} <x-heroicon-o-arrow-right class="w-4 h-4" />
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>

{{-- 6 SERVICE TILES STRIP --}}
<section class="bg-gray-50 py-10">
    <div class="container-page">
        <h2 class="reveal-on-scroll font-display text-2xl font-bold text-primary mb-2">{{ __('messages.home.services_title') }}</h2>
        <p class="text-gray-600 mb-6">{{ __('messages.home.services_subtitle') }}</p>
        @if($services->isEmpty())
            <x-state.empty icon="heroicon-o-archive-box-x-mark" :title="__('messages.states.empty.title')" :message="__('messages.states.empty.message')" tone="warning" />
        @else
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3">
                @foreach($services as $service)
                    <a href="{{ route('service.show', $service->slug) }}" class="reveal-on-scroll bg-white rounded-md p-4 border hover:border-primary text-center group hover-lift" style="--reveal-delay:{{ $loop->index * 40 }}ms">
                        <x-heroicon-o-document-text class="w-8 h-8 mx-auto text-primary mb-2" />
                        <div class="font-semibold text-sm group-hover:text-primary leading-snug">{{ $service->name }}</div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</section>

{{-- BERITA & PENGUMUMAN 3-TAB --}}
<section id="berita" class="py-10 bg-white" x-data="{ tab: 'berita' }">
    <div class="container-page">
        <div class="flex items-end justify-between mb-6">
            <h2 class="reveal-on-scroll font-display text-2xl font-bold text-primary">{{ __('messages.home.news_title') }}</h2>
            <a href="#" class="text-sm text-primary hover:underline">{{ __('messages.home.view_all') }} &rarr;</a>
        </div>
        <div role="tablist" class="border-b flex gap-1 mb-6">
            <button role="tab" :aria-selected="tab === 'berita'" @click="tab = 'berita'"
                    :class="tab === 'berita' ? 'border-primary text-primary' : 'border-transparent text-gray-600 hover:text-primary'"
                    class="px-4 py-2 border-b-2 font-semibold transition">
                {{ __('messages.home.tab_berita') }}
            </button>
            <button role="tab" :aria-selected="tab === 'tender'" @click="tab = 'tender'"
                    :class="tab === 'tender' ? 'border-primary text-primary' : 'border-transparent text-gray-600 hover:text-primary'"
                    class="px-4 py-2 border-b-2 font-semibold transition">
                {{ __('messages.home.tab_tender') }}
            </button>
            <button role="tab" :aria-selected="tab === 'pengumuman'" @click="tab = 'pengumuman'"
                    :class="tab === 'pengumuman' ? 'border-primary text-primary' : 'border-transparent text-gray-600 hover:text-primary'"
                    class="px-4 py-2 border-b-2 font-semibold transition">
                {{ __('messages.home.tab_pengumuman') }}
            </button>
        </div>

        {{-- Berita panel --}}
        <div role="tabpanel" x-show="tab === 'berita'">
            @if($beritas->isEmpty())
                <x-state.empty icon="heroicon-o-newspaper" :title="__('messages.states.empty.news')" tone="warning" />
            @else
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($beritas as $b)
                        <article class="reveal-on-scroll rounded-lg overflow-hidden border group hover-lift" style="--reveal-delay:{{ $loop->index * 50 }}ms">
                            <div class="h-32 bg-gradient-to-br from-primary-pale to-primary-light"></div>
                            <div class="p-4">
                                <div class="text-xs text-gray-500 mb-1">{{ $b->published_at?->isoFormat('D MMM Y') }}</div>
                                <h3 class="font-semibold text-primary mb-2 group-hover:underline">{{ $b->title }}</h3>
                                <p class="text-sm text-gray-700">{{ $b->excerpt }}</p>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Tender panel --}}
        <div id="tender" role="tabpanel" x-show="tab === 'tender'" x-cloak>
            @if($tenders->isEmpty())
                <x-state.empty icon="heroicon-o-document-text" :title="__('messages.states.empty.tender')" tone="info" />
            @else
                <div class="space-y-3">
                    @foreach($tenders as $t)
                        @php $daysLeft = (int) round(now()->diffInHours($t->closes_at) / 24); @endphp
                        <a href="#" class="flex flex-wrap items-center gap-3 p-4 border rounded hover:border-primary hover:shadow transition">
                            <div class="font-mono text-xs text-gray-500 w-32">{{ $t->reference_no }}</div>
                            <div class="flex-1 font-semibold text-primary">{{ $t->title }}</div>
                            <div class="text-sm text-gray-600">{{ $t->closes_at->isoFormat('D MMM Y, H:mm') }}</div>
                            <span class="text-xs px-2 py-0.5 rounded {{ $daysLeft <= 7 ? 'bg-amber-100 text-amber-800' : 'bg-green-100 text-green-800' }}">
                                {{ $daysLeft > 0 ? $daysLeft . ' ' . (app()->getLocale() === 'ms' ? 'hari lagi' : 'days left') : (app()->getLocale() === 'ms' ? 'Tutup' : 'Closed') }}
                            </span>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Pengumuman panel --}}
        <div role="tabpanel" x-show="tab === 'pengumuman'" x-cloak>
            @if($pengumumans->isEmpty())
                <x-state.empty icon="heroicon-o-megaphone" :title="__('messages.states.empty.news')" tone="warning" />
            @else
                <ul class="divide-y rounded-lg border">
                    @foreach($pengumumans as $p)
                        <li class="p-4 flex flex-wrap items-center gap-3 hover:bg-gray-50 transition">
                            <div class="text-xs text-gray-500 w-24">{{ $p->published_at?->isoFormat('D MMM Y') }}</div>
                            <a href="#" class="font-semibold text-primary hover:underline flex-1">{{ $p->title }}</a>
                            @if($p->important)
                                <span class="text-xs px-2 py-0.5 bg-amber-100 text-amber-800 rounded">{{ app()->getLocale() === 'ms' ? 'Penting' : 'Important' }}</span>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</section>

{{-- PAUTAN AGENSI strip --}}
<section class="reveal-on-scroll bg-primary-pale py-8">
    <div class="container-page">
        <h2 class="font-display text-lg font-semibold text-primary mb-4">{{ __('messages.home.agencies_title') }}</h2>
        <div class="flex flex-wrap items-center gap-4">
            @foreach([
                'Kementerian Sumber Asli',
                'Jabatan Ukur dan Pemetaan',
                'Jabatan Penilaian',
                'e-Tanah Negeri',
                'SISPAA',
                'JPN',
            ] as $agency)
                <a href="#" class="bg-white rounded p-3 px-5 text-sm font-medium text-gray-700 border hover:border-primary hover:text-primary transition">
                    {{ $agency }}
                </a>
            @endforeach
        </div>
    </div>
</section>

@endsection
