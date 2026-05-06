@extends('layouts.public')

@section('title', __('messages.panduan.user_title') . ' | ' . __('messages.site_name'))

@section('content')
<x-statement-band :label="__('messages.nav.panduan')" :title="__('messages.static.panduan_pengguna.heading')" :subtitle="__('messages.static.panduan_pengguna.help')" />

<x-breadcrumb :items="[['label' => __('messages.panduan.user_title')]]" />

<section class="py-12">
    <div class="container-page max-w-3xl prose prose-sm md:prose-base text-gray-800">
        <h2 class="text-primary">{{ __('messages.static.panduan_pengguna.h_navigate') }}</h2>
        <p>{{ __('messages.static.panduan_pengguna.p_navigate') }}</p>
        <h2 class="text-primary">{{ __('messages.static.panduan_pengguna.h_search') }}</h2>
        <p>{{ __('messages.static.panduan_pengguna.p_search') }}</p>
        <h2 class="text-primary">{{ __('messages.static.panduan_pengguna.h_apply') }}</h2>
        <p>{{ __('messages.static.panduan_pengguna.p_apply') }}</p>
        <h2 class="text-primary">{{ __('messages.static.panduan_pengguna.h_a11y') }}</h2>
        <p>{{ __('messages.static.panduan_pengguna.p_a11y') }}</p>
        <h2 class="text-primary">{{ __('messages.static.panduan_pengguna.h_help') }}</h2>
        <p>{!! __('messages.static.panduan_pengguna.p_help', ['aduan' => route('hubungi.aduan'), 'faq' => route('faq.index')]) !!}</p>
    </div>
</section>
@endsection
