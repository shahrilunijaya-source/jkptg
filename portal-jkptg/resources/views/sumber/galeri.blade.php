@extends('layouts.public')

@section('title', __('messages.sumber.galeri') . ' | ' . __('messages.site_name'))

@section('content')
<x-statement-band icon="photo" :label="__('messages.nav.sumber')" :title="__('messages.sumber.galeri')" :subtitle="__('messages.sumber.galeri_desc')" />

<x-breadcrumb :items="[
    ['label' => __('messages.nav.sumber'), 'href' => route('sumber.index')],
    ['label' => __('messages.sumber.galeri')],
]" />

<section class="py-6 border-b">
    <div class="container-page flex flex-wrap gap-2">
        @foreach($allowed as $t)
            <a href="{{ route('sumber.galeri', $t) }}"
               class="px-4 py-1.5 rounded-full text-sm font-semibold border transition {{ $type === $t ? 'bg-primary text-white border-primary' : 'bg-white text-primary border-gray-300 hover:border-primary' }}">
                {{ __('messages.sumber.galeri_' . $t) }}
            </a>
        @endforeach
    </div>
</section>

<section class="py-12">
    <div class="container-page">
        <x-state.empty
            icon="heroicon-o-photo"
            :title="__('messages.sumber.galeri_coming_soon_title')"
            :message="__('messages.sumber.galeri_coming_soon_body')"
            tone="info" />
    </div>
</section>
@endsection
