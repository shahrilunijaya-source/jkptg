@extends('layouts.public')

@section('title', __('messages.site_name'))

@php
    $tagline = \App\Models\Setting::get('site.tagline');
    $tagline = is_array($tagline) ? ($tagline[app()->getLocale()] ?? '') : $tagline;
    $services = \App\Models\Service::where('active', true)->orderBy('sort')->limit(8)->get();
    $beritas = \App\Models\News::whereNotNull('published_at')->where('type', 'berita')->orderByDesc('published_at')->limit(5)->get();
    $pengumumans = \App\Models\News::whereNotNull('published_at')->where('type', 'pengumuman')->orderByDesc('published_at')->limit(5)->get();
    $tenders = \App\Models\Tender::whereIn('status', ['open', 'closed'])->orderByDesc('opens_at')->limit(5)->get();
    $kpiServices = \App\Models\Service::where('active', true)->count();
    $kpiTenders = \App\Models\Tender::where('status', 'open')->count();
    $kpiCawangan = \App\Models\Cawangan::count();
@endphp

@section('content')

{{-- HERO: bilingual statement + KPI rail. No photo. --}}
<section class="border-b border-slate-200 bg-white">
    <div class="container-page py-16 md:py-24">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-16 items-end">
            <div class="lg:col-span-8 max-w-3xl">
                <div class="mono-cap text-slate-500 mb-5">{{ __('messages.site_name') }} · {{ now()->year }}</div>
                <h1 class="text-[40px] sm:text-[52px] lg:text-[64px] font-bold text-slate-900 leading-[1.05] tracking-tight mb-6">
                    {{ $tagline }}
                </h1>
                <p class="text-lg text-slate-600 leading-relaxed max-w-2xl">
                    {{ app()->getLocale() === 'ms'
                        ? 'Merekod, melindungi dan menguruskan harta tanah Persekutuan untuk manfaat rakyat Malaysia. Saluran rasmi maklumat, perkhidmatan dan tender JKPTG.'
                        : 'We record, protect, and manage federal land assets for the benefit of the Malaysian people. The official channel for JKPTG information, services, and tenders.' }}
                </p>
                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="#perkhidmatan" class="btn-primary">
                        <span>{{ app()->getLocale() === 'ms' ? 'Lihat perkhidmatan' : 'Browse services' }}</span>
                        <span aria-hidden="true">→</span>
                    </a>
                    <a href="#berita" class="btn-secondary">
                        {{ app()->getLocale() === 'ms' ? 'Berita & Tender' : 'News & Tenders' }}
                    </a>
                </div>
            </div>

            {{-- KPI rail --}}
            <aside class="lg:col-span-4">
                <dl class="grid grid-cols-3 lg:grid-cols-1 border border-slate-200 divide-y divide-slate-200 lg:divide-y lg:divide-x-0 max-lg:divide-y-0 max-lg:divide-x">
                    <div class="p-5">
                        <dt class="mono-cap text-slate-500 mb-1.5">{{ app()->getLocale() === 'ms' ? 'Perkhidmatan' : 'Services' }}</dt>
                        <dd class="font-mono text-[28px] tabular-nums text-slate-900 leading-none">{{ str_pad($kpiServices, 2, '0', STR_PAD_LEFT) }}</dd>
                    </div>
                    <div class="p-5">
                        <dt class="mono-cap text-slate-500 mb-1.5">{{ app()->getLocale() === 'ms' ? 'Tender Aktif' : 'Open Tenders' }}</dt>
                        <dd class="font-mono text-[28px] tabular-nums text-slate-900 leading-none">{{ str_pad($kpiTenders, 2, '0', STR_PAD_LEFT) }}</dd>
                    </div>
                    <div class="p-5">
                        <dt class="mono-cap text-slate-500 mb-1.5">{{ app()->getLocale() === 'ms' ? 'Cawangan' : 'Branches' }}</dt>
                        <dd class="font-mono text-[28px] tabular-nums text-slate-900 leading-none">{{ str_pad($kpiCawangan, 2, '0', STR_PAD_LEFT) }}</dd>
                    </div>
                </dl>
                <a href="{{ route('hubungi.cawangan') }}" class="mono-cap text-slate-500 hover:text-slate-900 mt-3 inline-flex items-center gap-1.5">
                    <span>{{ app()->getLocale() === 'ms' ? 'STATISTIK PORTAL · ' : 'PORTAL STATISTICS · ' }}{{ now()->format('Y-m-d') }}</span>
                    <span aria-hidden="true">→</span>
                </a>
            </aside>
        </div>
    </div>
</section>

{{-- PERSONA STAT-CARDS --}}
<section aria-labelledby="persona-heading" class="border-b border-slate-200 bg-white">
    <div class="container-page py-12 md:py-16">
        <div class="flex items-baseline justify-between mb-8">
            <h2 id="persona-heading" class="mono-cap text-slate-500">{{ app()->getLocale() === 'ms' ? 'PILIH LALUAN ANDA' : 'CHOOSE YOUR PATH' }}</h2>
            <span class="mono-meta">3 {{ app()->getLocale() === 'ms' ? 'persona' : 'personas' }}</span>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-px bg-slate-200 border border-slate-200">
            @foreach([
                ['code' => 'P-01', 'slug' => 'orang-awam',           'title' => __('messages.persona.orang_awam.title'),           'summary' => __('messages.persona.orang_awam.summary'),           'cta' => __('messages.persona.cta_start')],
                ['code' => 'P-02', 'slug' => 'kementerian-jabatan', 'title' => __('messages.persona.kementerian_jabatan.title'), 'summary' => __('messages.persona.kementerian_jabatan.summary'), 'cta' => __('messages.persona.cta_start')],
                ['code' => 'P-03', 'slug' => 'warga-jkptg',          'title' => __('messages.persona.warga_jkptg.title'),          'summary' => __('messages.persona.warga_jkptg.summary'),          'cta' => __('messages.persona.cta_login')],
            ] as $persona)
                <a href="{{ route('persona.show', $persona['slug']) }}"
                   class="group relative bg-white p-7 transition-colors duration-150 hover:bg-slate-50 focus-visible:bg-slate-50 flex flex-col">
                    <div class="mono-cap text-slate-500 mb-3">{{ $persona['code'] }}</div>
                    <h3 class="text-[22px] font-semibold text-slate-900 leading-tight mb-2 tracking-tight">{{ $persona['title'] }}</h3>
                    <p class="text-[14px] text-slate-600 leading-relaxed mb-6 flex-1">{{ $persona['summary'] }}</p>
                    <div class="flex items-center justify-between mt-auto pt-4 border-t border-slate-200">
                        <span class="text-[13px] font-medium text-primary">{{ $persona['cta'] }}</span>
                        <span class="text-slate-400 group-hover:text-primary group-hover:translate-x-0.5 transition" aria-hidden="true">→</span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>

{{-- SERVICES — dense data tiles --}}
<section id="perkhidmatan" aria-labelledby="services-heading" class="border-b border-slate-200 bg-slate-50">
    <div class="container-page py-12 md:py-16">
        <div class="flex items-baseline justify-between mb-8">
            <div>
                <h2 id="services-heading" class="mono-cap text-slate-500 mb-2">{{ __('messages.home.services_title') }}</h2>
                <p class="text-[15px] text-slate-600 max-w-2xl">{{ __('messages.home.services_subtitle') }}</p>
            </div>
            <a href="{{ route('service.index') }}" class="hidden md:inline-flex items-center gap-1.5 mono-cap text-slate-500 hover:text-slate-900">
                <span>{{ __('messages.home.view_all') }}</span>
                <span aria-hidden="true">→</span>
            </a>
        </div>
        @if($services->isEmpty())
            <x-state.empty icon="heroicon-o-archive-box-x-mark" :title="__('messages.states.empty.title')" :message="__('messages.states.empty.message')" tone="warning" />
        @else
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-px bg-slate-200 border border-slate-200">
                @foreach($services as $i => $service)
                    <a href="{{ route('service.show', $service->slug) }}"
                       class="group bg-white p-5 transition-colors duration-150 hover:bg-slate-50 flex flex-col gap-3 min-h-[148px]">
                        <div class="flex items-center justify-between">
                            <span class="mono-cap text-slate-400">SVC-{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                            <span class="text-slate-300 group-hover:text-primary group-hover:translate-x-0.5 transition" aria-hidden="true">→</span>
                        </div>
                        <div class="font-semibold text-slate-900 text-[15px] leading-snug group-hover:text-primary transition-colors">{{ $service->name }}</div>
                        @if($service->summary ?? null)
                            <div class="text-[13px] text-slate-500 leading-snug line-clamp-2">{{ $service->summary }}</div>
                        @endif
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</section>

{{-- NEWS / TENDER / PENGUMUMAN — flat list, mono dates, status pills --}}
<section id="berita" aria-labelledby="news-heading" class="border-b border-slate-200 bg-white" x-data="{ tab: 'berita' }">
    <div class="container-page py-12 md:py-16">
        <div class="flex items-baseline justify-between mb-6">
            <h2 id="news-heading" class="mono-cap text-slate-500">{{ __('messages.home.news_title') }}</h2>
            <a href="{{ route('search.index') }}" class="mono-cap text-slate-500 hover:text-slate-900">
                {{ __('messages.home.view_all') }} →
            </a>
        </div>
        <div role="tablist" class="border-b border-slate-200 flex gap-0 mb-0">
            @foreach([
                ['key' => 'berita', 'label' => __('messages.home.tab_berita')],
                ['key' => 'tender', 'label' => __('messages.home.tab_tender')],
                ['key' => 'pengumuman', 'label' => __('messages.home.tab_pengumuman')],
            ] as $tab)
                <button role="tab"
                        :aria-selected="tab === '{{ $tab['key'] }}'"
                        @click="tab = '{{ $tab['key'] }}'"
                        :class="tab === '{{ $tab['key'] }}' ? 'text-slate-900 border-primary' : 'text-slate-500 border-transparent hover:text-slate-900'"
                        class="relative px-4 py-3 -mb-px border-b-2 font-medium text-[14px] transition-colors duration-150 focus-visible:outline-none">
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
                    <a href="#" class="grid grid-cols-12 gap-4 py-4 items-baseline hover:bg-slate-50 transition-colors duration-150 px-1 -mx-1">
                        <div class="col-span-3 md:col-span-2 mono-meta tabular-nums">{{ $b->published_at?->format('Y-m-d') }}</div>
                        <div class="col-span-9 md:col-span-9 text-[15px] font-medium text-slate-900 hover:text-primary leading-snug">{{ $b->title }}</div>
                        <div class="hidden md:flex md:col-span-1 justify-end">
                            <span class="status-pill status-pill--baru">BARU</span>
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
                    <a href="#" class="grid grid-cols-12 gap-4 py-4 items-baseline hover:bg-slate-50 transition-colors duration-150 px-1 -mx-1">
                        <div class="col-span-4 md:col-span-2 font-mono text-[12px] text-slate-500 tabular-nums truncate">{{ $t->reference_no }}</div>
                        <div class="col-span-8 md:col-span-7 text-[15px] font-medium text-slate-900 hover:text-primary leading-snug">{{ $t->title }}</div>
                        <div class="hidden md:block md:col-span-2 mono-meta tabular-nums">{{ $t->closes_at?->format('Y-m-d') }}</div>
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
                    <a href="#" class="grid grid-cols-12 gap-4 py-4 items-baseline hover:bg-slate-50 transition-colors duration-150 px-1 -mx-1">
                        <div class="col-span-3 md:col-span-2 mono-meta tabular-nums">{{ $p->published_at?->format('Y-m-d') }}</div>
                        <div class="col-span-9 md:col-span-9 text-[15px] font-medium text-slate-900 hover:text-primary leading-snug">{{ $p->title }}</div>
                        <div class="hidden md:flex md:col-span-1 justify-end">
                            @if($p->important)
                                <span class="status-pill status-pill--draft">PENTING</span>
                            @endif
                        </div>
                    </a>
                @endforeach
            @endif
        </div>
    </div>
</section>

{{-- AGENCIES — monochrome wordmark row --}}
<section aria-labelledby="agencies-heading" class="bg-white">
    <div class="container-page py-10 md:py-12">
        <div class="flex items-baseline justify-between mb-5">
            <h2 id="agencies-heading" class="mono-cap text-slate-500">{{ __('messages.home.agencies_title') }}</h2>
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
                    <a href="#" class="text-[13px] font-medium text-slate-500 hover:text-slate-900 transition-colors duration-150 underline-offset-4 hover:underline decoration-slate-300">{{ $agency }}</a>
                </li>
            @endforeach
        </ul>
    </div>
</section>

@endsection
