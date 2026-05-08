@extends('layouts.public')

@section('title', $page->title . ' | ' . __('messages.site_name'))
@section('description', strip_tags($page->meta_description ?? ''))

@section('content')

{{-- Hero header with photo --}}
<section class="relative text-white py-12 overflow-hidden">
    <div class="absolute inset-0"
         style="background-image: linear-gradient(180deg, rgba(15,30,51,0.55) 0%, rgba(15,30,51,0.88) 100%), url('https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=1920&q=80'); background-size: cover; background-position: center;" aria-hidden="true"></div>
    <div class="relative container-page">
        <div class="flex items-center gap-2 text-jata-yellow text-sm uppercase tracking-wider mb-2">
            <x-heroicon-o-user class="w-4 h-4" />
            <span>{{ __('messages.nav.korporat') }}</span>
        </div>
        <h1 class="font-display text-3xl md:text-4xl font-bold">{{ $page->title }}</h1>
    </div>
</section>

<x-breadcrumb :items="[
    ['label' => __('messages.nav.korporat'), 'url' => route('korporat.index')],
    ['label' => __('messages.korporat.pengurusan_tertinggi'), 'url' => route('korporat.pengurusan')],
    ['label' => $page->title],
]" />

<section class="py-10 bg-gray-50 min-h-screen">
    <div class="container-page space-y-6">

        {{-- Profile identity card --}}
        <div class="reveal-on-scroll rounded-2xl bg-white border shadow-sm overflow-hidden flex flex-col sm:flex-row">
            {{-- Photo --}}
            <div class="sm:w-56 md:w-64 flex-shrink-0 bg-primary-pale">
                @if($photo)
                    <img src="{{ $photo }}" alt="{{ $page->title }}"
                         class="w-full h-56 sm:h-full object-cover object-top">
                @else
                    <div class="w-full h-56 sm:h-full flex items-center justify-center">
                        <x-heroicon-o-user class="w-20 h-20 text-primary/25" />
                    </div>
                @endif
            </div>

            {{-- Identity --}}
            <div class="p-8 flex flex-col justify-center flex-1">
                <span class="inline-flex items-center gap-2 text-xs font-semibold uppercase tracking-widest text-primary/50 mb-3">
                    <span class="w-5 h-px bg-primary/30 inline-block"></span>
                    {{ __('messages.korporat.pengurusan_tertinggi') }}
                </span>
                <h2 class="font-display text-2xl md:text-3xl font-bold text-primary leading-snug mb-2">
                    {{ $page->title }}
                </h2>
                @if($page->meta_description)
                    <p class="text-gray-500 text-sm leading-relaxed max-w-lg">
                        {{ strip_tags($page->meta_description) }}
                    </p>
                @endif
                <div class="mt-5 flex flex-wrap items-center gap-3">
                    <span class="inline-flex items-center gap-1.5 text-xs bg-primary-pale text-primary font-semibold rounded-full px-3 py-1">
                        <x-heroicon-o-building-office-2 class="w-3.5 h-3.5" />
                        Jabatan Ketua Pengarah Tanah &amp; Galian Persekutuan
                    </span>
                </div>
            </div>
        </div>

        {{-- Content + sidebar --}}
        @php
            $careerSections = collect($sections)->only(['Pengalaman Kerja','Aktiviti & Sumbangan Lain'])->filter();
            $hasBody = strlen(trim(strip_tags($body))) > 10;
            $hasCareer = $hasBody || $careerSections->isNotEmpty();
        @endphp
        <div class="grid grid-cols-1 lg:grid-cols-[1fr_288px] gap-6 items-start">

            {{-- Main: prose body + named career sections --}}
            @if($hasCareer)
            <div class="reveal-on-scroll rounded-2xl bg-white border p-8 space-y-6" style="--reveal-delay:0ms">
                <h3 class="font-display text-base font-bold text-primary uppercase tracking-widest pb-3 border-b">
                    {{ app()->getLocale() === 'ms' ? 'Latar Belakang & Kerjaya' : 'Background & Career' }}
                </h3>

                @if($hasBody)
                <div class="prose prose-slate prose-sm max-w-none">
                    {!! $body !!}
                </div>
                @endif

                @foreach($careerSections as $secTitle => $secContent)
                    @if($secContent)
                    <div>
                        <h4 class="text-xs font-bold text-primary/60 uppercase tracking-wider mb-2">{{ $secTitle }}</h4>
                        <p class="text-sm text-gray-700 leading-relaxed">{{ $secContent }}</p>
                    </div>
                    @endif
                @endforeach

                @if(!$hasBody && $careerSections->isEmpty())
                <p class="text-sm text-gray-400 italic">{{ app()->getLocale() === 'ms' ? 'Maklumat sedang dikemaskini.' : 'Information being updated.' }}</p>
                @endif
            </div>
            @else
            {{-- No career data: show placeholder so grid still has two columns --}}
            <div class="reveal-on-scroll rounded-2xl bg-primary-pale/40 border border-dashed border-primary/20 p-8 flex items-center justify-center" style="--reveal-delay:40ms">
                <p class="text-sm text-primary/40 italic text-center">{{ app()->getLocale() === 'ms' ? 'Maklumat lanjut sedang dikemaskini.' : 'Further information being updated.' }}</p>
            </div>
            @endif

            {{-- Sidebar --}}
            <div class="space-y-4 lg:sticky lg:top-24">

                {{-- Qualifications --}}
                @if($qualItems)
                <div class="reveal-on-scroll rounded-2xl bg-white border p-6" style="--reveal-delay:60ms">
                    <h4 class="text-xs font-bold text-primary uppercase tracking-widest mb-4 flex items-center gap-2">
                        <x-heroicon-o-academic-cap class="w-4 h-4" />
                        {{ app()->getLocale() === 'ms' ? 'Kelulusan Akademik' : 'Qualifications' }}
                    </h4>
                    <div class="space-y-4">
                        @foreach($qualItems as $q)
                        <div class="flex gap-3">
                            <span class="flex-shrink-0 text-xs font-bold text-white bg-primary rounded px-2 py-0.5 h-fit mt-0.5 tabular-nums">
                                {{ $q['year'] }}
                            </span>
                            <p class="text-sm text-gray-700 leading-snug">{{ $q['text'] }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Awards --}}
                @if($awardItems)
                <div class="reveal-on-scroll rounded-2xl bg-white border p-6" style="--reveal-delay:100ms">
                    <h4 class="text-xs font-bold text-primary uppercase tracking-widest mb-4 flex items-center gap-2">
                        <x-heroicon-o-star class="w-4 h-4" />
                        {{ app()->getLocale() === 'ms' ? 'Anugerah & Darjah' : 'Awards & Honours' }}
                    </h4>
                    <ul class="space-y-3">
                        @foreach($awardItems as $award)
                        <li class="flex gap-2.5 text-sm text-gray-700 leading-snug">
                            <span class="w-1.5 h-1.5 rounded-full bg-jata-yellow flex-shrink-0 mt-1.5"></span>
                            <span>{{ $award }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif

                {{-- Actions + meta --}}
                <div class="reveal-on-scroll rounded-2xl bg-white border p-5 space-y-4" style="--reveal-delay:140ms">
                    <p class="text-xs text-gray-400">
                        {{ __('messages.footer.kemaskini') }}: {{ $page->updated_at->isoFormat('D MMM Y') }}
                    </p>
                    <div class="flex gap-2 no-print">
                        <a href="{{ route('page.pdf', $page->slug) }}"
                           class="flex-1 flex items-center justify-center gap-1.5 text-xs border rounded-lg py-2.5 text-gray-600 hover:text-primary hover:border-primary transition-colors duration-150">
                            <x-heroicon-o-arrow-down-tray class="w-3.5 h-3.5" /> PDF
                        </a>
                        <button type="button" onclick="window.print()"
                            class="flex-1 flex items-center justify-center gap-1.5 text-xs border rounded-lg py-2.5 text-gray-600 hover:text-primary hover:border-primary transition-colors duration-150">
                            <x-heroicon-o-printer class="w-3.5 h-3.5" />
                            {{ app()->getLocale() === 'ms' ? 'Cetak' : 'Print' }}
                        </button>
                    </div>
                    <a href="{{ route('korporat.pengurusan') }}"
                       class="flex items-center gap-1.5 text-xs text-primary hover:underline font-medium">
                        <x-heroicon-o-arrow-left class="w-3.5 h-3.5" />
                        {{ app()->getLocale() === 'ms' ? 'Semua Pengurusan' : 'All Leadership' }}
                    </a>
                </div>

            </div>
        </div>
    </div>
</section>
@endsection
