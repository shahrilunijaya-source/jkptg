@extends('layouts.public')

@section('title', __('messages.site_name'))

@php
    $tagline = \App\Models\Setting::get('site.tagline');
    $tagline = is_array($tagline) ? ($tagline[app()->getLocale()] ?? '') : $tagline;
    $services = \App\Models\Service::where('active', true)->orderBy('sort')->limit(6)->get();
    $beritas = \App\Models\News::whereNotNull('published_at')->where('type', 'berita')->orderByDesc('published_at')->limit(5)->get();
    $pengumumans = \App\Models\News::whereNotNull('published_at')->where('type', 'pengumuman')->orderByDesc('published_at')->limit(5)->get();
    $tenders = \App\Models\Tender::whereIn('status', ['open', 'closed'])->orderByDesc('opens_at')->limit(5)->get();
    $featuredTender = \App\Models\Tender::where('status', 'open')->orderBy('closes_at')->first();
    $kpiServices = \App\Models\Service::where('active', true)->count();
    $kpiTenders = \App\Models\Tender::where('status', 'open')->count();
    $kpiCawangan = \App\Models\Cawangan::count();
    $kpiBerita = \App\Models\News::whereNotNull('published_at')->where('type', 'berita')->count();

    $serviceIcons = [
        'document-text', 'building-library', 'map', 'home-modern',
        'banknotes', 'building-office-2', 'document-magnifying-glass', 'scale',
    ];

    $heroImage = asset('images/hero/jkptg-hero.jpg');
    $heroFallback = 'https://images.unsplash.com/photo-1591274029987-58ddb3935833?auto=format&fit=crop&w=2400&q=80';
@endphp

@push('head')
<style>
    .hero-figure {
        background-image: url('{{ $heroFallback }}');
        background-size: cover;
        background-position: center 35%;
    }
    .hero-overlay {
        background: linear-gradient(180deg, rgba(11,50,32,0.55) 0%, rgba(11,50,32,0.65) 50%, rgba(11,50,32,0.85) 100%);
    }
</style>
@endpush

@section('content')

{{-- HERO: editorial pictorial --}}
<section class="relative isolate overflow-hidden">
    {{-- Background image + overlay --}}
    <div class="absolute inset-0 z-0 hero-figure" aria-hidden="true"></div>
    <div class="absolute inset-0 z-0 hero-overlay" aria-hidden="true"></div>
    <div class="absolute inset-0 z-0 mix-blend-overlay opacity-30" style="background-image: radial-gradient(circle at 30% 40%, rgba(255,255,255,0.18), transparent 55%);" aria-hidden="true"></div>

    {{-- Hero content --}}
    <div class="relative z-10 container-page pt-20 lg:pt-28 pb-44 lg:pb-56 text-white">
        <div class="max-w-3xl">
            <div class="flex items-center gap-3 mb-6">
                <span class="inline-block w-10 h-px bg-bronze-light" aria-hidden="true"></span>
                <span class="text-[12px] uppercase tracking-[0.2em] font-semibold text-bronze-light">{{ __('messages.utility.official_short') }} · JKPTG</span>
            </div>
            <h1 class="text-[44px] sm:text-[60px] lg:text-[72px] font-bold leading-[1.02] tracking-tight mb-6 [text-wrap:balance]">
                {{ $tagline }}
            </h1>
            <p class="text-lg lg:text-[19px] text-white/90 leading-relaxed max-w-2xl">
                {{ app()->getLocale() === 'ms'
                    ? 'Merekod, melindungi dan menguruskan harta tanah Persekutuan untuk manfaat rakyat Malaysia. Saluran rasmi maklumat, perkhidmatan dan tender JKPTG.'
                    : 'We record, protect, and manage federal land assets for the benefit of the Malaysian people. The official channel for JKPTG information, services, and tenders.' }}
            </p>
            <div class="mt-9 flex flex-wrap gap-3">
                <a href="#perkhidmatan" class="inline-flex items-center gap-2 bg-bronze hover:bg-bronze-dark text-white px-6 py-3 rounded-sm font-semibold transition-colors duration-150">
                    <span>{{ app()->getLocale() === 'ms' ? 'Lihat perkhidmatan' : 'Browse services' }}</span>
                    <x-heroicon-o-arrow-right class="w-4 h-4" />
                </a>
                <a href="#berita" class="inline-flex items-center gap-2 border border-white/40 hover:border-white hover:bg-white/10 text-white px-6 py-3 rounded-sm font-semibold transition-colors duration-150">
                    {{ app()->getLocale() === 'ms' ? 'Berita & Tender' : 'News & Tenders' }}
                </a>
            </div>
        </div>
    </div>
</section>

{{-- PERSONA BAND — floats over hero bottom edge --}}
<section aria-labelledby="persona-heading" class="relative -mt-32 lg:-mt-40 z-20">
    <div class="container-page">
        <div class="flex items-baseline justify-between mb-5">
            <span class="text-[12px] uppercase tracking-[0.18em] font-semibold text-white/85">{{ app()->getLocale() === 'ms' ? 'Pilih Laluan Anda' : 'Choose Your Path' }}</span>
            <span class="text-[12px] uppercase tracking-[0.16em] font-semibold text-white/65 hidden sm:inline">3 {{ app()->getLocale() === 'ms' ? 'persona' : 'personas' }}</span>
        </div>
        <h2 id="persona-heading" class="sr-only">{{ app()->getLocale() === 'ms' ? 'Pilih laluan anda' : 'Choose your path' }}</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            @foreach([
                ['slug' => 'orang-awam',           'icon' => 'users',                 'title' => __('messages.persona.orang_awam.title'),           'summary' => __('messages.persona.orang_awam.summary'),           'cta' => __('messages.persona.cta_start')],
                ['slug' => 'kementerian-jabatan', 'icon' => 'building-office-2',     'title' => __('messages.persona.kementerian_jabatan.title'), 'summary' => __('messages.persona.kementerian_jabatan.summary'), 'cta' => __('messages.persona.cta_start')],
                ['slug' => 'warga-jkptg',          'icon' => 'identification',         'title' => __('messages.persona.warga_jkptg.title'),          'summary' => __('messages.persona.warga_jkptg.summary'),          'cta' => __('messages.persona.cta_login')],
            ] as $persona)
                <a href="{{ route('persona.show', $persona['slug']) }}"
                   class="group bg-white rounded-2xl p-7 shadow-[0_8px_24px_-8px_rgba(11,50,32,0.18)] hover:shadow-[0_16px_40px_-8px_rgba(11,50,32,0.28)] transition-all duration-200 flex flex-col">
                    <div class="flex items-start justify-between mb-4">
                        <span class="icon-medallion w-12 h-12">
                            <x-dynamic-component :component="'heroicon-o-' . $persona['icon']" class="w-6 h-6" />
                        </span>
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full border border-slate-200 text-slate-300 group-hover:border-primary group-hover:text-primary transition-colors duration-150">
                            <x-heroicon-o-arrow-right class="w-3.5 h-3.5 group-hover:translate-x-0.5 transition-transform" />
                        </span>
                    </div>
                    <h3 class="text-[20px] font-bold text-canvas-ink leading-tight mb-2 tracking-tight group-hover:text-primary transition-colors">{{ $persona['title'] }}</h3>
                    <p class="text-[14px] text-slate-600 leading-relaxed mb-5 flex-1">{{ $persona['summary'] }}</p>
                    <span class="inline-flex items-center gap-1.5 text-[13px] font-semibold text-primary mt-auto pt-3 border-t border-slate-100">
                        {{ $persona['cta'] }}
                    </span>
                </a>
            @endforeach
        </div>
    </div>
</section>

{{-- KPI STRIP — full-width 4-stat band on cream --}}
<section class="bg-canvas border-t border-slate-200 mt-16 md:mt-20">
    <div class="container-page">
        <dl class="grid grid-cols-2 lg:grid-cols-4 divide-y divide-slate-200 lg:divide-y-0 lg:divide-x">
            <div class="px-2 py-7 lg:py-9 lg:px-7">
                <dt class="text-[12px] uppercase tracking-[0.16em] font-semibold text-bronze mb-2">{{ app()->getLocale() === 'ms' ? 'Perkhidmatan' : 'Services' }}</dt>
                <dd class="font-bold text-[44px] lg:text-[52px] text-canvas-ink leading-none tracking-tight">{{ $kpiServices }}</dd>
                <p class="text-[13px] text-slate-500 mt-2">{{ app()->getLocale() === 'ms' ? 'Urusan tanah dalam talian' : 'Online land services' }}</p>
            </div>
            <div class="px-2 py-7 lg:py-9 lg:px-7">
                <dt class="text-[12px] uppercase tracking-[0.16em] font-semibold text-bronze mb-2">{{ app()->getLocale() === 'ms' ? 'Tender Aktif' : 'Open Tenders' }}</dt>
                <dd class="font-bold text-[44px] lg:text-[52px] text-canvas-ink leading-none tracking-tight">{{ $kpiTenders }}</dd>
                <p class="text-[13px] text-slate-500 mt-2">{{ app()->getLocale() === 'ms' ? 'Sebut harga semasa' : 'Procurement notices' }}</p>
            </div>
            <div class="px-2 py-7 lg:py-9 lg:px-7">
                <dt class="text-[12px] uppercase tracking-[0.16em] font-semibold text-bronze mb-2">{{ app()->getLocale() === 'ms' ? 'Cawangan' : 'Branches' }}</dt>
                <dd class="font-bold text-[44px] lg:text-[52px] text-canvas-ink leading-none tracking-tight">{{ $kpiCawangan }}</dd>
                <p class="text-[13px] text-slate-500 mt-2">{{ app()->getLocale() === 'ms' ? 'Negeri seluruh Malaysia' : 'States across Malaysia' }}</p>
            </div>
            <div class="px-2 py-7 lg:py-9 lg:px-7">
                <dt class="text-[12px] uppercase tracking-[0.16em] font-semibold text-bronze mb-2">{{ app()->getLocale() === 'ms' ? 'Berita Tahunan' : 'Annual News' }}</dt>
                <dd class="font-bold text-[44px] lg:text-[52px] text-canvas-ink leading-none tracking-tight">{{ $kpiBerita }}</dd>
                <p class="text-[13px] text-slate-500 mt-2">{{ app()->getLocale() === 'ms' ? 'Pengumuman dan kemaskini' : 'Announcements & updates' }}</p>
            </div>
        </dl>
    </div>
</section>

{{-- FEATURED TENDER BANNER --}}
@if($featuredTender)
<section aria-labelledby="featured-tender" class="bg-primary border-y border-primary-900">
    <div class="container-page py-7 lg:py-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-center">
            <div class="lg:col-span-2 flex items-center gap-3">
                <span class="inline-flex items-center justify-center w-11 h-11 rounded-sm bg-bronze text-white">
                    <x-heroicon-o-megaphone class="w-5 h-5" aria-hidden="true" />
                </span>
                <div>
                    <span class="text-[11px] uppercase tracking-[0.16em] font-semibold text-bronze-light">{{ app()->getLocale() === 'ms' ? 'Tender Terkini' : 'Latest Tender' }}</span>
                    <div class="text-white/85 text-[13px] mt-0.5">{{ $featuredTender->reference_no }}</div>
                </div>
            </div>
            <div class="lg:col-span-7 min-w-0">
                <h2 id="featured-tender" class="text-[18px] lg:text-[20px] font-bold text-white leading-snug">{{ $featuredTender->title }}</h2>
                @if($featuredTender->closes_at)
                    <p class="text-[13px] text-white/80 mt-1">{{ app()->getLocale() === 'ms' ? 'Tutup pada' : 'Closes' }} {{ $featuredTender->closes_at->isoFormat('D MMM Y, H:mm') }}</p>
                @endif
            </div>
            <div class="lg:col-span-3 lg:text-right">
                <a href="#tender" class="inline-flex items-center gap-2 bg-white text-primary hover:bg-bronze-light hover:text-primary-900 px-5 py-2.5 rounded-sm font-semibold transition-colors duration-150">
                    <span>{{ app()->getLocale() === 'ms' ? 'Lihat butiran' : 'View details' }}</span>
                    <x-heroicon-o-arrow-right class="w-4 h-4" />
                </a>
            </div>
        </div>
    </div>
</section>
@endif

{{-- MESEJ KETUA PENGARAH --}}
<section aria-labelledby="dg-heading" class="bg-canvas-mute border-b border-slate-200">
    <div class="container-page py-16 md:py-24">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-16 items-center">
            {{-- Portrait + seal --}}
            <figure class="lg:col-span-4 max-w-sm mx-auto lg:mx-0">
                <div class="relative">
                    <div class="aspect-[4/5] bg-primary-100 overflow-hidden rounded-2xl">
                        <img src="{{ asset('images/dg/dg-portrait.jpg') }}"
                             onerror="this.src='https://images.unsplash.com/photo-1568602471122-7832951cc4c5?auto=format&fit=crop&w=900&q=80';"
                             alt="{{ app()->getLocale() === 'ms' ? 'Potret Ketua Pengarah JKPTG' : 'Portrait of the Director General of JKPTG' }}"
                             class="w-full h-full object-cover grayscale-[0.15]">
                    </div>
                    {{-- Bronze seal corner --}}
                    <div class="absolute -bottom-4 -right-4 hidden md:flex items-center justify-center w-20 h-20 bg-bronze text-white shadow-[0_8px_24px_-6px_rgba(11,50,32,0.35)]">
                        <img src="{{ asset('images/jata-negara.png') }}" alt="" class="w-12 h-12 object-contain brightness-0 invert" aria-hidden="true">
                    </div>
                </div>
                <figcaption class="mt-6 lg:mt-8">
                    <div class="text-[12px] uppercase tracking-[0.16em] font-semibold text-bronze mb-1">{{ app()->getLocale() === 'ms' ? 'Ketua Pengarah' : 'Director General' }}</div>
                    <div class="text-[18px] font-bold text-canvas-ink leading-tight">{{ app()->getLocale() === 'ms' ? 'YBhg. Datuk Hj. Ketua Pengarah' : 'YBhg. Datuk Hj. Director General' }}</div>
                    <div class="text-[13px] text-slate-600 mt-1 leading-snug">{{ app()->getLocale() === 'ms' ? 'Jabatan Ketua Pengarah Tanah dan Galian Persekutuan' : 'Department of the Director General of Federal Lands and Mines' }}</div>
                </figcaption>
            </figure>

            {{-- Message --}}
            <div class="lg:col-span-8 max-w-2xl">
                <span class="eyebrow">{{ app()->getLocale() === 'ms' ? 'Mesej Ketua Pengarah' : "Director General's Message" }}</span>
                <h2 id="dg-heading" class="text-[28px] md:text-[36px] font-bold text-canvas-ink leading-tight tracking-tight mt-3 mb-6 [text-wrap:balance]">
                    {{ app()->getLocale() === 'ms'
                        ? 'Memperteguh tadbir urus tanah Persekutuan untuk masa depan rakyat.'
                        : 'Strengthening federal land governance for the future of our people.' }}
                </h2>
                <div class="space-y-4 text-[15px] md:text-[16px] text-slate-700 leading-relaxed">
                    <p>{{ app()->getLocale() === 'ms'
                        ? 'Sebagai peneraju tadbir urus tanah Persekutuan, JKPTG komited memastikan setiap urusan tanah dilaksanakan dengan telus, cekap dan beramanah, demi menjaga warisan negara untuk generasi akan datang.'
                        : 'As the steward of federal land governance, JKPTG is committed to ensuring every land transaction is carried out with transparency, efficiency and integrity, safeguarding the nation\'s heritage for generations to come.' }}</p>
                    <p>{{ app()->getLocale() === 'ms'
                        ? 'Portal rasmi ini adalah saluran utama anda untuk mengakses perkhidmatan, polisi, akta dan maklumat terkini berkaitan tanah Persekutuan. Kami akan terus memperbaiki kualiti penyampaian secara berterusan.'
                        : 'This official portal is your primary channel to access services, policies, acts and current information on federal lands. We will continue to improve service quality on an ongoing basis.' }}</p>
                </div>
                <div class="mt-8 pt-6 border-t border-slate-200 flex flex-wrap items-center justify-between gap-4">
                    <a href="{{ route('page.show', 'mengenai-jkptg') }}" class="inline-flex items-center gap-1.5 text-[14px] font-semibold text-primary hover:text-primary-800">
                        <span>{{ app()->getLocale() === 'ms' ? 'Mengenai JKPTG' : 'About JKPTG' }}</span>
                        <x-heroicon-o-arrow-right class="w-4 h-4" />
                    </a>
                    <a href="{{ route('page.show', 'piagam-pelanggan') }}" class="inline-flex items-center gap-1.5 text-[14px] font-semibold text-slate-600 hover:text-primary">
                        <span>{{ app()->getLocale() === 'ms' ? 'Piagam Pelanggan' : 'Client Charter' }}</span>
                        <x-heroicon-o-arrow-right class="w-4 h-4" />
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- SERVICES — civic card grid --}}
<section id="perkhidmatan" aria-labelledby="services-heading" class="bg-white border-b border-slate-200">
    <div class="container-page py-16 md:py-20">
        <div class="flex items-end justify-between mb-10 flex-wrap gap-4">
            <div class="max-w-2xl">
                <span class="eyebrow-muted">{{ __('messages.home.services_title') }}</span>
                <h2 id="services-heading" class="text-[28px] md:text-[36px] font-bold text-canvas-ink leading-tight tracking-tight mt-2 mb-3">
                    {{ app()->getLocale() === 'ms' ? 'Perkhidmatan Utama' : 'Main Services' }}
                </h2>
                <p class="text-[15px] md:text-[16px] text-slate-600 leading-relaxed">{{ __('messages.home.services_subtitle') }}</p>
            </div>
            <a href="{{ route('service.index') }}" class="hidden md:inline-flex items-center gap-1.5 text-[14px] font-semibold text-primary hover:text-primary-800">
                <span>{{ __('messages.home.view_all') }}</span>
                <x-heroicon-o-arrow-right class="w-4 h-4" />
            </a>
        </div>
        @if($services->isEmpty())
            <x-state.empty icon="heroicon-o-archive-box-x-mark" :title="__('messages.states.empty.title')" :message="__('messages.states.empty.message')" tone="warning" />
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($services as $i => $service)
                    @php $iconName = $serviceIcons[$i % count($serviceIcons)]; @endphp
                    <a href="{{ route('service.show', $service->slug) }}" class="civic-card flex flex-col group">
                        <span class="icon-medallion mb-4">
                            <x-dynamic-component :component="'heroicon-o-' . $iconName" class="w-5 h-5" />
                        </span>
                        <h3 class="text-[17px] font-bold text-canvas-ink leading-snug mb-2 group-hover:text-primary transition-colors">{{ $service->name }}</h3>
                        @if($service->summary ?? null)
                            <p class="text-[14px] text-slate-600 leading-relaxed line-clamp-3 mb-4 flex-1">{{ $service->summary }}</p>
                        @endif
                        <span class="inline-flex items-center gap-1.5 text-[13px] font-semibold text-primary mt-auto">
                            {{ app()->getLocale() === 'ms' ? 'Maklumat lanjut' : 'Learn more' }}
                            <x-heroicon-o-arrow-right class="w-3.5 h-3.5 group-hover:translate-x-0.5 transition-transform" />
                        </span>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</section>

{{-- NEWS / TENDER / PENGUMUMAN --}}
<section id="berita" aria-labelledby="news-heading" class="bg-canvas border-b border-slate-200" x-data="{ tab: 'berita' }">
    <div class="container-page py-16 md:py-20">
        <div class="flex items-baseline justify-between mb-8 flex-wrap gap-4">
            <div>
                <span class="eyebrow-muted">{{ __('messages.home.news_title') }}</span>
                <h2 id="news-heading" class="text-[28px] md:text-[36px] font-bold text-canvas-ink leading-tight tracking-tight mt-2">
                    {{ app()->getLocale() === 'ms' ? 'Berita & Pengumuman' : 'News & Announcements' }}
                </h2>
            </div>
            <a href="{{ route('search.index') }}" class="text-[14px] font-semibold text-primary hover:text-primary-800 inline-flex items-center gap-1.5">
                {{ __('messages.home.view_all') }}
                <x-heroicon-o-arrow-right class="w-4 h-4" />
            </a>
        </div>
        <div role="tablist" class="border-b border-slate-200 flex gap-0 mb-2">
            @foreach([
                ['key' => 'berita', 'label' => __('messages.home.tab_berita')],
                ['key' => 'tender', 'label' => __('messages.home.tab_tender')],
                ['key' => 'pengumuman', 'label' => __('messages.home.tab_pengumuman')],
            ] as $tab)
                <button role="tab"
                        :aria-selected="tab === '{{ $tab['key'] }}'"
                        @click="tab = '{{ $tab['key'] }}'"
                        :class="tab === '{{ $tab['key'] }}' ? 'text-primary border-primary' : 'text-slate-500 border-transparent hover:text-primary'"
                        class="relative px-4 py-3 -mb-px border-b-2 font-semibold text-[14px] transition-colors duration-150 focus-visible:outline-none">
                    {{ $tab['label'] }}
                </button>
            @endforeach
        </div>

        <div role="tabpanel" x-show="tab === 'berita'" class="divide-y divide-slate-100">
            @if($beritas->isEmpty())
                <x-state.empty icon="heroicon-o-newspaper" :title="__('messages.states.empty.news')" tone="warning" />
            @else
                @foreach($beritas as $b)
                    <a href="#" class="grid grid-cols-12 gap-4 py-5 items-baseline hover:bg-white transition-colors duration-150 px-2 -mx-2 rounded-sm">
                        <div class="col-span-3 md:col-span-2 text-[12px] font-medium text-slate-500">{{ $b->published_at?->isoFormat('D MMM Y') }}</div>
                        <div class="col-span-9 md:col-span-9 text-[15px] font-semibold text-canvas-ink hover:text-primary leading-snug">{{ $b->title }}</div>
                        <div class="hidden md:flex md:col-span-1 justify-end">
                            <span class="status-pill status-pill--baru">{{ app()->getLocale() === 'ms' ? 'BARU' : 'NEW' }}</span>
                        </div>
                    </a>
                @endforeach
            @endif
        </div>

        <div id="tender" role="tabpanel" x-show="tab === 'tender'" x-cloak class="divide-y divide-slate-100">
            @if($tenders->isEmpty())
                <x-state.empty icon="heroicon-o-document-text" :title="__('messages.states.empty.tender')" tone="info" />
            @else
                @foreach($tenders as $t)
                    @php $statusClass = $t->status === 'open' ? 'status-pill--open' : 'status-pill--closed'; @endphp
                    <a href="#" class="grid grid-cols-12 gap-4 py-5 items-baseline hover:bg-white transition-colors duration-150 px-2 -mx-2 rounded-sm">
                        <div class="col-span-4 md:col-span-2 font-mono text-[12px] text-slate-500 truncate">{{ $t->reference_no }}</div>
                        <div class="col-span-8 md:col-span-7 text-[15px] font-semibold text-canvas-ink hover:text-primary leading-snug">{{ $t->title }}</div>
                        <div class="hidden md:block md:col-span-2 text-[12px] text-slate-500">{{ $t->closes_at?->isoFormat('D MMM Y') }}</div>
                        <div class="hidden md:flex md:col-span-1 justify-end">
                            <span class="status-pill {{ $statusClass }}">{{ strtoupper($t->status) }}</span>
                        </div>
                    </a>
                @endforeach
            @endif
        </div>

        <div role="tabpanel" x-show="tab === 'pengumuman'" x-cloak class="divide-y divide-slate-100">
            @if($pengumumans->isEmpty())
                <x-state.empty icon="heroicon-o-megaphone" :title="__('messages.states.empty.news')" tone="warning" />
            @else
                @foreach($pengumumans as $p)
                    <a href="#" class="grid grid-cols-12 gap-4 py-5 items-baseline hover:bg-white transition-colors duration-150 px-2 -mx-2 rounded-sm">
                        <div class="col-span-3 md:col-span-2 text-[12px] font-medium text-slate-500">{{ $p->published_at?->isoFormat('D MMM Y') }}</div>
                        <div class="col-span-9 md:col-span-9 text-[15px] font-semibold text-canvas-ink hover:text-primary leading-snug">{{ $p->title }}</div>
                        <div class="hidden md:flex md:col-span-1 justify-end">
                            @if($p->important)
                                <span class="status-pill status-pill--draft">{{ app()->getLocale() === 'ms' ? 'PENTING' : 'IMPORTANT' }}</span>
                            @endif
                        </div>
                    </a>
                @endforeach
            @endif
        </div>
    </div>
</section>

{{-- AGENCIES --}}
<section aria-labelledby="agencies-heading" class="bg-white">
    <div class="container-page py-12 md:py-14">
        <div class="max-w-2xl mb-6">
            <span class="eyebrow-muted">{{ __('messages.home.agencies_title') }}</span>
        </div>
        <ul class="flex flex-wrap items-center gap-x-8 gap-y-3">
            @foreach([
                'Kementerian Sumber Asli',
                'Jabatan Ukur dan Pemetaan',
                'Jabatan Penilaian',
                'e-Tanah Negeri',
                'SISPAA',
                'JPN',
                'JPPH',
                'MyGovernment',
            ] as $agency)
                <li>
                    <a href="#" class="text-[14px] font-medium text-slate-600 hover:text-primary transition-colors duration-150 underline-offset-4 hover:underline decoration-slate-300">{{ $agency }}</a>
                </li>
            @endforeach
        </ul>
    </div>
</section>

@endsection
