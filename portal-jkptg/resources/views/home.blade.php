@extends('layouts.public')

@section('title', __('messages.site_name'))

@php
    $tagline = \App\Models\Setting::get('site.tagline');
    $tagline = is_array($tagline) ? ($tagline[app()->getLocale()] ?? '') : $tagline;
    $services = \App\Models\Service::where('active', true)->orderBy('sort')->limit(6)->get();
    $beritas = \App\Models\News::whereNotNull('published_at')->where('type', 'berita')->orderByDesc('published_at')->limit(5)->get();
    $pengumumans = \App\Models\News::whereNotNull('published_at')->where('type', 'pengumuman')->orderByDesc('published_at')->limit(5)->get();
    $tenders = \App\Models\Tender::whereIn('status', ['open', 'closed'])->orderByDesc('opens_at')->limit(5)->get();
    $kpiServices = \App\Models\Service::where('active', true)->count();
    $kpiTenders = \App\Models\Tender::where('status', 'open')->count();
    $kpiCawangan = \App\Models\Cawangan::count();

    $serviceIcons = [
        'document-text', 'building-library', 'map', 'home-modern',
        'banknotes', 'building-office-2', 'document-magnifying-glass', 'scale',
    ];
@endphp

@section('content')

{{-- HERO: bilingual statement on canvas cream --}}
<section class="bg-canvas border-b border-slate-200">
    <div class="container-page py-16 md:py-24">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-end">
            <div class="lg:col-span-8 max-w-3xl">
                <div class="flex items-center gap-2 mb-5">
                    <span class="inline-block w-8 h-px bg-bronze" aria-hidden="true"></span>
                    <span class="eyebrow">{{ __('messages.site_name') }}</span>
                </div>
                <h1 class="text-[40px] sm:text-[52px] lg:text-[60px] font-bold text-canvas-ink leading-[1.05] tracking-tight mb-6">
                    {{ $tagline }}
                </h1>
                <p class="text-lg text-slate-700 leading-relaxed max-w-2xl">
                    {{ app()->getLocale() === 'ms'
                        ? 'Merekod, melindungi dan menguruskan harta tanah Persekutuan untuk manfaat rakyat Malaysia. Saluran rasmi maklumat, perkhidmatan dan tender JKPTG.'
                        : 'We record, protect, and manage federal land assets for the benefit of the Malaysian people. The official channel for JKPTG information, services, and tenders.' }}
                </p>
                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="#perkhidmatan" class="btn-primary">
                        <span>{{ app()->getLocale() === 'ms' ? 'Lihat perkhidmatan' : 'Browse services' }}</span>
                        <x-heroicon-o-arrow-right class="w-4 h-4" />
                    </a>
                    <a href="#berita" class="btn-secondary">
                        {{ app()->getLocale() === 'ms' ? 'Berita & Tender' : 'News & Tenders' }}
                    </a>
                </div>
            </div>

            {{-- KPI rail: civic stats, prose labels, no mono spam --}}
            <aside class="lg:col-span-4">
                <div class="bg-white border border-slate-200 rounded-sm">
                    <dl class="grid grid-cols-3 lg:grid-cols-1 divide-x divide-slate-200 lg:divide-x-0 lg:divide-y">
                        <div class="p-5">
                            <dt class="text-[12px] uppercase tracking-[0.12em] font-semibold text-slate-500 mb-1.5">{{ app()->getLocale() === 'ms' ? 'Perkhidmatan' : 'Services' }}</dt>
                            <dd class="font-bold text-[32px] text-primary leading-none">{{ $kpiServices }}</dd>
                        </div>
                        <div class="p-5">
                            <dt class="text-[12px] uppercase tracking-[0.12em] font-semibold text-slate-500 mb-1.5">{{ app()->getLocale() === 'ms' ? 'Tender Aktif' : 'Open Tenders' }}</dt>
                            <dd class="font-bold text-[32px] text-primary leading-none">{{ $kpiTenders }}</dd>
                        </div>
                        <div class="p-5">
                            <dt class="text-[12px] uppercase tracking-[0.12em] font-semibold text-slate-500 mb-1.5">{{ app()->getLocale() === 'ms' ? 'Cawangan Negeri' : 'State Branches' }}</dt>
                            <dd class="font-bold text-[32px] text-primary leading-none">{{ $kpiCawangan }}</dd>
                        </div>
                    </dl>
                </div>
                <a href="{{ route('hubungi.cawangan') }}" class="mt-3 inline-flex items-center gap-1.5 text-[13px] text-slate-500 hover:text-primary">
                    <span>{{ app()->getLocale() === 'ms' ? 'Statistik kemaskini ' : 'Statistics updated ' }}{{ now()->isoFormat('D MMM Y') }}</span>
                    <x-heroicon-o-arrow-right class="w-3.5 h-3.5" />
                </a>
            </aside>
        </div>
    </div>
</section>

{{-- PERSONA — civic card grid with icon medallions --}}
<section aria-labelledby="persona-heading" class="bg-white border-b border-slate-200">
    <div class="container-page py-14 md:py-16">
        <div class="max-w-3xl mb-10">
            <span class="eyebrow-muted">{{ app()->getLocale() === 'ms' ? 'Untuk Anda' : 'For You' }}</span>
            <h2 id="persona-heading" class="text-[28px] md:text-[32px] font-bold text-canvas-ink leading-tight tracking-tight mt-2">
                {{ app()->getLocale() === 'ms' ? 'Pilih laluan anda' : 'Choose your path' }}
            </h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            @foreach([
                ['slug' => 'orang-awam',           'icon' => 'users',                 'title' => __('messages.persona.orang_awam.title'),           'summary' => __('messages.persona.orang_awam.summary'),           'cta' => __('messages.persona.cta_start')],
                ['slug' => 'kementerian-jabatan', 'icon' => 'building-office-2',     'title' => __('messages.persona.kementerian_jabatan.title'), 'summary' => __('messages.persona.kementerian_jabatan.summary'), 'cta' => __('messages.persona.cta_start')],
                ['slug' => 'warga-jkptg',          'icon' => 'identification',         'title' => __('messages.persona.warga_jkptg.title'),          'summary' => __('messages.persona.warga_jkptg.summary'),          'cta' => __('messages.persona.cta_login')],
            ] as $persona)
                <a href="{{ route('persona.show', $persona['slug']) }}"
                   class="civic-card flex flex-col group">
                    <span class="icon-medallion mb-4">
                        <x-dynamic-component :component="'heroicon-o-' . $persona['icon']" class="w-5 h-5" />
                    </span>
                    <h3 class="text-[20px] font-bold text-canvas-ink leading-tight mb-2 tracking-tight group-hover:text-primary transition-colors">{{ $persona['title'] }}</h3>
                    <p class="text-[14px] text-slate-600 leading-relaxed mb-5 flex-1">{{ $persona['summary'] }}</p>
                    <span class="inline-flex items-center gap-1.5 text-[13px] font-semibold text-primary mt-auto">
                        {{ $persona['cta'] }}
                        <x-heroicon-o-arrow-right class="w-3.5 h-3.5 group-hover:translate-x-0.5 transition-transform" />
                    </span>
                </a>
            @endforeach
        </div>
    </div>
</section>

{{-- SERVICES — civic card grid with icon medallions --}}
<section id="perkhidmatan" aria-labelledby="services-heading" class="bg-canvas-mute border-b border-slate-200">
    <div class="container-page py-14 md:py-16">
        <div class="flex items-baseline justify-between mb-10 flex-wrap gap-4">
            <div class="max-w-2xl">
                <span class="eyebrow-muted">{{ __('messages.home.services_title') }}</span>
                <h2 id="services-heading" class="text-[28px] md:text-[32px] font-bold text-canvas-ink leading-tight tracking-tight mt-2 mb-2">
                    {{ app()->getLocale() === 'ms' ? 'Perkhidmatan Utama' : 'Main Services' }}
                </h2>
                <p class="text-[15px] text-slate-600">{{ __('messages.home.services_subtitle') }}</p>
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
<section id="berita" aria-labelledby="news-heading" class="bg-white border-b border-slate-200" x-data="{ tab: 'berita' }">
    <div class="container-page py-14 md:py-16">
        <div class="flex items-baseline justify-between mb-8 flex-wrap gap-4">
            <div>
                <span class="eyebrow-muted">{{ __('messages.home.news_title') }}</span>
                <h2 id="news-heading" class="text-[28px] md:text-[32px] font-bold text-canvas-ink leading-tight tracking-tight mt-2">
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

        {{-- Berita panel --}}
        <div role="tabpanel" x-show="tab === 'berita'" class="divide-y divide-slate-100">
            @if($beritas->isEmpty())
                <x-state.empty icon="heroicon-o-newspaper" :title="__('messages.states.empty.news')" tone="warning" />
            @else
                @foreach($beritas as $b)
                    <a href="#" class="grid grid-cols-12 gap-4 py-5 items-baseline hover:bg-canvas-mute transition-colors duration-150 px-2 -mx-2 rounded-sm">
                        <div class="col-span-3 md:col-span-2 text-[12px] font-medium text-slate-500">{{ $b->published_at?->isoFormat('D MMM Y') }}</div>
                        <div class="col-span-9 md:col-span-9 text-[15px] font-semibold text-canvas-ink hover:text-primary leading-snug">{{ $b->title }}</div>
                        <div class="hidden md:flex md:col-span-1 justify-end">
                            <span class="status-pill status-pill--baru">{{ app()->getLocale() === 'ms' ? 'BARU' : 'NEW' }}</span>
                        </div>
                    </a>
                @endforeach
            @endif
        </div>

        {{-- Tender panel --}}
        <div id="tender" role="tabpanel" x-show="tab === 'tender'" x-cloak class="divide-y divide-slate-100">
            @if($tenders->isEmpty())
                <x-state.empty icon="heroicon-o-document-text" :title="__('messages.states.empty.tender')" tone="info" />
            @else
                @foreach($tenders as $t)
                    @php $statusClass = $t->status === 'open' ? 'status-pill--open' : 'status-pill--closed'; @endphp
                    <a href="#" class="grid grid-cols-12 gap-4 py-5 items-baseline hover:bg-canvas-mute transition-colors duration-150 px-2 -mx-2 rounded-sm">
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

        {{-- Pengumuman panel --}}
        <div role="tabpanel" x-show="tab === 'pengumuman'" x-cloak class="divide-y divide-slate-100">
            @if($pengumumans->isEmpty())
                <x-state.empty icon="heroicon-o-megaphone" :title="__('messages.states.empty.news')" tone="warning" />
            @else
                @foreach($pengumumans as $p)
                    <a href="#" class="grid grid-cols-12 gap-4 py-5 items-baseline hover:bg-canvas-mute transition-colors duration-150 px-2 -mx-2 rounded-sm">
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

{{-- AGENCIES — restrained wordmark row --}}
<section aria-labelledby="agencies-heading" class="bg-canvas">
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
