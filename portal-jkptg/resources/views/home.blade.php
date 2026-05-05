@extends('layouts.public')

@section('title', __('messages.site_name'))

@section('content')
<section class="bg-gradient-to-br from-primary to-primary-mute text-white py-16">
    <div class="container-page max-w-4xl">
        <p class="font-display uppercase tracking-wider text-jata-yellow text-sm mb-3">{{ __('messages.site_name') }}</p>
        <h1 class="font-display font-bold text-4xl md:text-6xl leading-tight mb-4">
            @php
              $tagline = \App\Models\Setting::get('site.tagline');
              echo is_array($tagline) ? ($tagline[app()->getLocale()] ?? '') : $tagline;
            @endphp
        </h1>
        <p class="text-base md:text-lg text-white/90 max-w-2xl">
            {{ app()->getLocale() === 'ms'
                ? 'Kami merekodkan, melindungi dan menguruskan harta tanah negara untuk manfaat rakyat Malaysia.'
                : 'We record, protect, and manage national land assets for the benefit of the Malaysian people.' }}
        </p>
        <div class="mt-6 flex flex-wrap gap-3">
            <a href="#perkhidmatan" class="bg-white text-primary font-semibold px-5 py-2.5 rounded hover:bg-primary-pale">
                {{ __('messages.nav.perkhidmatan') }}
            </a>
            <a href="#hubungi" class="border border-white/60 text-white px-5 py-2.5 rounded hover:bg-white/10">
                {{ __('messages.nav.hubungi') }}
            </a>
        </div>
    </div>
</section>

<section id="perkhidmatan" class="py-12 bg-gray-50">
    <div class="container-page">
        <h2 class="font-display text-2xl font-bold text-primary mb-2">{{ __('messages.megamenu.most_popular') }}</h2>
        <p class="text-gray-600 mb-6">{{ __('messages.megamenu.tagline') }}.</p>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3">
            @foreach(\App\Models\Service::where('active', true)->orderBy('sort')->limit(6)->get() as $service)
                <a href="#" class="bg-white rounded-md p-4 border hover:border-primary hover:shadow text-center group">
                    <x-heroicon-o-document-text class="w-8 h-8 mx-auto text-primary mb-2" />
                    <div class="font-semibold text-sm group-hover:text-primary">{{ $service->name }}</div>
                </a>
            @endforeach
        </div>
        <p class="text-xs text-gray-500 mt-6">{{ app()->getLocale() === 'ms' ? 'Susun atur penuh hero + persona doors akan dibina dalam Phase 5.' : 'Full hero + persona doors layout to be built in Phase 5.' }}</p>
    </div>
</section>
@endsection
