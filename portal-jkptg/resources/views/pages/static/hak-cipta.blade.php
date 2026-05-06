@extends('layouts.public')

@section('title', __('messages.footer.hak_cipta') . ' | ' . __('messages.site_name'))

@section('content')
<x-statement-band :label="__('messages.static.legal')" :title="__('messages.static.hak_cipta.heading')" />

<x-breadcrumb :items="[['label' => __('messages.footer.hak_cipta')]]" />

<section class="py-12">
    <div class="container-page max-w-3xl prose prose-sm md:prose-base text-gray-800">
        <p>{{ __('messages.static.hak_cipta.intro') }}</p>
        <h2 class="text-primary">{{ __('messages.static.hak_cipta.h_owner') }}</h2>
        <p>{{ __('messages.static.hak_cipta.p_owner') }}</p>
        <h2 class="text-primary">{{ __('messages.static.hak_cipta.h_use') }}</h2>
        <p>{{ __('messages.static.hak_cipta.p_use') }}</p>
        <h2 class="text-primary">{{ __('messages.static.hak_cipta.h_third') }}</h2>
        <p>{{ __('messages.static.hak_cipta.p_third') }}</p>
        <p class="text-sm text-gray-500 mt-8">{{ __('messages.static.last_review', ['date' => '2026-01-01']) }}</p>
    </div>
</section>
@endsection
