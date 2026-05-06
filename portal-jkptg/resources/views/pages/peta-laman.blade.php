@extends('layouts.public')

@section('title', __('messages.utility.peta_laman') . ' | ' . __('messages.site_name'))

@section('content')
<x-breadcrumb :items="[['label' => __('messages.utility.peta_laman')]]" />

<section class="bg-gradient-to-br from-primary to-primary-mute text-white py-12">
    <div class="container-page">
        <div class="flex items-center gap-2 text-jata-yellow text-sm uppercase tracking-wider mb-2">
            <x-heroicon-o-map class="w-4 h-4" />
            <span>{{ __('messages.utility.peta_laman') }}</span>
        </div>
        <h1 class="font-display text-3xl md:text-5xl font-bold mb-3">{{ __('messages.peta_laman.heading') }}</h1>
        <p class="text-white/85 max-w-2xl">{{ __('messages.peta_laman.help') }}</p>
    </div>
</section>

<section class="py-12">
    <div class="container-page grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

        <div>
            <h2 class="font-display text-lg font-bold text-primary mb-3 border-b pb-2">{{ __('messages.nav.utama') }}</h2>
            <ul class="space-y-1.5 text-sm">
                <li><a href="{{ route('home') }}" class="text-gray-700 hover:text-primary hover:underline">{{ __('messages.nav.utama') }}</a></li>
                <li><a href="{{ route('persona.show', 'orang-awam') }}" class="text-gray-700 hover:text-primary hover:underline">{{ __('messages.persona.orang_awam.title') }}</a></li>
                <li><a href="{{ route('persona.show', 'kementerian-jabatan') }}" class="text-gray-700 hover:text-primary hover:underline">{{ __('messages.persona.kementerian_jabatan.title') }}</a></li>
                <li><a href="{{ route('persona.show', 'warga-jkptg') }}" class="text-gray-700 hover:text-primary hover:underline">{{ __('messages.persona.warga_jkptg.title') }}</a></li>
            </ul>
        </div>

        <div>
            <h2 class="font-display text-lg font-bold text-primary mb-3 border-b pb-2">{{ __('messages.nav.perkhidmatan') }}</h2>
            <ul class="space-y-1.5 text-sm">
                <li><a href="{{ route('service.index') }}" class="text-gray-700 hover:text-primary hover:underline">{{ __('messages.peta_laman.all_services') }}</a></li>
                @foreach($services as $s)
                    <li><a href="{{ route('service.show', $s->slug) }}" class="text-gray-700 hover:text-primary hover:underline">{{ $s->name }}</a></li>
                @endforeach
            </ul>
        </div>

        <div>
            <h2 class="font-display text-lg font-bold text-primary mb-3 border-b pb-2">{{ __('messages.nav.panduan') }}</h2>
            <ul class="space-y-1.5 text-sm">
                <li><a href="{{ route('panduan.index') }}" class="text-gray-700 hover:text-primary hover:underline">{{ __('messages.nav.panduan') }}</a></li>
                <li><a href="{{ route('borang.index') }}" class="text-gray-700 hover:text-primary hover:underline">{{ __('messages.borang.title') }}</a></li>
                <li><a href="{{ route('faq.index') }}" class="text-gray-700 hover:text-primary hover:underline">{{ __('messages.utility.soalan_lazim') }}</a></li>
                <li><a href="{{ route('panduan-pengguna') }}" class="text-gray-700 hover:text-primary hover:underline">{{ __('messages.panduan.user_title') }}</a></li>
            </ul>
        </div>

        <div>
            <h2 class="font-display text-lg font-bold text-primary mb-3 border-b pb-2">{{ __('messages.nav.korporat') }}</h2>
            <ul class="space-y-1.5 text-sm">
                <li><a href="{{ route('korporat.index') }}" class="text-gray-700 hover:text-primary hover:underline">{{ __('messages.nav.korporat') }}</a></li>
                @foreach($korporatPages as $p)
                    <li><a href="{{ route('page.show', $p->slug) }}" class="text-gray-700 hover:text-primary hover:underline">{{ $p->title }}</a></li>
                @endforeach
            </ul>
        </div>

        <div>
            <h2 class="font-display text-lg font-bold text-primary mb-3 border-b pb-2">{{ __('messages.nav.sumber') }}</h2>
            <ul class="space-y-1.5 text-sm">
                <li><a href="{{ route('sumber.index') }}" class="text-gray-700 hover:text-primary hover:underline">{{ __('messages.nav.sumber') }}</a></li>
            </ul>
        </div>

        <div>
            <h2 class="font-display text-lg font-bold text-primary mb-3 border-b pb-2">{{ __('messages.nav.hubungi') }}</h2>
            <ul class="space-y-1.5 text-sm">
                <li><a href="{{ route('hubungi.index') }}" class="text-gray-700 hover:text-primary hover:underline">{{ __('messages.nav.hubungi') }}</a></li>
                <li><a href="{{ route('hubungi.ibu-pejabat') }}" class="text-gray-700 hover:text-primary hover:underline">{{ __('messages.hubungi.hq') }}</a></li>
                <li><a href="{{ route('hubungi.cawangan') }}" class="text-gray-700 hover:text-primary hover:underline">{{ __('messages.hubungi.branches') }}</a></li>
                <li><a href="{{ route('hubungi.aduan') }}" class="text-gray-700 hover:text-primary hover:underline">{{ __('messages.utility.aduan') }}</a></li>
            </ul>
        </div>

        <div class="md:col-span-2 lg:col-span-3">
            <h2 class="font-display text-lg font-bold text-primary mb-3 border-b pb-2">{{ __('messages.peta_laman.legal') }}</h2>
            <ul class="grid grid-cols-2 md:grid-cols-4 gap-x-4 gap-y-1.5 text-sm">
                <li><a href="{{ route('page.show', 'disclaimer') }}" class="text-gray-700 hover:text-primary hover:underline">{{ __('messages.footer.disclaimer') }}</a></li>
                <li><a href="{{ route('page.show', 'polisi-privasi') }}" class="text-gray-700 hover:text-primary hover:underline">{{ __('messages.footer.polisi_privasi') }}</a></li>
                <li><a href="{{ route('page.show', 'polisi-keselamatan') }}" class="text-gray-700 hover:text-primary hover:underline">{{ __('messages.footer.polisi_keselamatan') }}</a></li>
                <li><a href="{{ route('hak-cipta') }}" class="text-gray-700 hover:text-primary hover:underline">{{ __('messages.footer.hak_cipta') }}</a></li>
                <li><a href="{{ route('dasar-web') }}" class="text-gray-700 hover:text-primary hover:underline">{{ __('messages.peta_laman.dasar_web') }}</a></li>
                <li><a href="{{ route('panduan-pengguna') }}" class="text-gray-700 hover:text-primary hover:underline">{{ __('messages.panduan.user_title') }}</a></li>
                <li><a href="{{ route('peta-laman') }}" class="text-gray-700 hover:text-primary hover:underline">{{ __('messages.utility.peta_laman') }}</a></li>
            </ul>
        </div>
    </div>
</section>
@endsection
