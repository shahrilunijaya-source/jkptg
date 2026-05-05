@extends('layouts.public')

@section('title', __('messages.nav.perkhidmatan') . ' | ' . __('messages.site_name'))

@section('content')
<x-breadcrumb :items="[['label' => __('messages.nav.perkhidmatan')]]" />

<section class="bg-gradient-to-br from-primary to-primary-mute text-white py-12">
    <div class="container-page">
        <div class="flex items-center gap-2 text-jata-yellow text-sm uppercase tracking-wider mb-2">
            <x-heroicon-o-document-text class="w-4 h-4" />
            <span>{{ __('messages.nav.perkhidmatan') }}</span>
        </div>
        <h1 class="font-display text-3xl md:text-5xl font-bold mb-3">{{ __('messages.megamenu.tagline') }}</h1>
        <p class="text-white/85 max-w-2xl">{{ __('messages.persona.services_help') }}</p>
    </div>
</section>

<section class="py-12">
    <div class="container-page">
        @if($services->isEmpty())
            <x-state.empty icon="heroicon-o-archive-box-x-mark" :title="__('messages.states.empty.title')" tone="warning" />
        @else
            @foreach($categories as $cat)
                @php $catServices = $services->where('category', $cat); @endphp
                <h2 class="font-display text-xl font-bold text-primary mb-4 capitalize flex items-center gap-2">
                    <span class="w-1.5 h-6 bg-jata-yellow rounded"></span>
                    {{ ucfirst($cat) }}
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-10">
                    @foreach($catServices as $service)
                        <a href="{{ route('service.show', $service->slug) }}"
                           class="group rounded-lg border bg-white p-5 hover:shadow hover:border-primary transition">
                            <div class="w-10 h-10 rounded bg-primary-pale text-primary flex items-center justify-center mb-3">
                                <x-heroicon-o-document-text class="w-5 h-5" />
                            </div>
                            <h3 class="font-semibold text-primary group-hover:underline mb-1">{{ $service->name }}</h3>
                            <p class="text-sm text-gray-600 line-clamp-2">{{ $service->summary }}</p>
                            @if($service->processing_days)
                                <p class="text-xs text-gray-500 mt-3 flex items-center gap-1">
                                    <x-heroicon-o-clock class="w-3.5 h-3.5" />
                                    {{ $service->processing_days }} {{ app()->getLocale() === 'ms' ? 'hari' : 'days' }}
                                </p>
                            @endif
                        </a>
                    @endforeach
                </div>
            @endforeach
        @endif
    </div>
</section>
@endsection
