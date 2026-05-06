@extends('layouts.public')

@section('title', __('messages.utility.soalan_lazim') . ' | ' . __('messages.site_name'))

@section('content')
<x-breadcrumb :items="[['label' => __('messages.utility.soalan_lazim')]]" />

<section class="bg-gradient-to-br from-primary to-primary-mute text-white py-12">
    <div class="container-page">
        <div class="flex items-center gap-2 text-jata-yellow text-sm uppercase tracking-wider mb-2">
            <x-heroicon-o-question-mark-circle class="w-4 h-4" />
            <span>{{ __('messages.utility.soalan_lazim') }}</span>
        </div>
        <h1 class="font-display text-3xl md:text-5xl font-bold mb-3">{{ __('messages.faq.heading') }}</h1>
        <p class="text-white/85 max-w-2xl">{{ __('messages.faq.help') }}</p>
    </div>
</section>

<section class="py-12">
    <div class="container-page max-w-4xl">
        @if($faqs->count())
            @foreach($categories as $cat)
                <div class="mb-8">
                    <h2 class="font-display text-xl font-bold text-primary mb-3 capitalize border-b pb-2">{{ str_replace('-', ' ', $cat) }}</h2>
                    <div class="space-y-2">
                        @foreach($faqs->where('category', $cat) as $faq)
                            <details class="bg-white border rounded-lg group" name="faq-{{ $cat }}">
                                <summary class="cursor-pointer p-4 font-semibold text-primary hover:bg-gray-50 flex items-start gap-3 list-none">
                                    <x-heroicon-o-chevron-right class="w-5 h-5 mt-0.5 transition-transform group-open:rotate-90 flex-shrink-0" />
                                    <span class="flex-1">{{ $faq->question }}</span>
                                </summary>
                                <div class="px-4 pb-4 pl-12 text-sm text-gray-700 leading-relaxed">
                                    {!! nl2br(e($faq->answer)) !!}
                                </div>
                            </details>
                        @endforeach
                    </div>
                </div>
            @endforeach
        @else
            <x-state.empty icon="heroicon-o-question-mark-circle" :title="__('messages.states.empty.title')" />
        @endif

        <div class="mt-12 rounded-lg border border-jata-yellow/40 bg-jata-yellow/10 p-5">
            <div class="flex items-start gap-3">
                <x-heroicon-o-information-circle class="w-5 h-5 text-jata-red flex-shrink-0 mt-0.5" />
                <div class="text-sm text-gray-800">
                    <p class="font-semibold mb-1">{{ __('messages.faq.no_answer_title') }}</p>
                    <p>{!! __('messages.faq.no_answer_body', ['url' => route('hubungi.aduan')]) !!}</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
