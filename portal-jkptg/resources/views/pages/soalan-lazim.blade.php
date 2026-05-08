@extends('layouts.public')

@section('title', __('messages.utility.soalan_lazim') . ' | ' . __('messages.site_name'))

@section('content')
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

<x-breadcrumb :items="[['label' => __('messages.utility.soalan_lazim')]]" />

<section class="py-12">
    <div class="container-page max-w-4xl">
        @if($faqs->count())
            @foreach($categories as $cat)
                <div class="mb-8">
                    <h2 class="font-display text-xl font-bold text-primary mb-3 capitalize border-b pb-2">{{ str_replace('-', ' ', $cat) }}</h2>
                    <div class="space-y-2" x-data="{ active: null }">
                        @foreach($faqs->where('category', $cat) as $i => $faq)
                            <div class="bg-white border rounded-lg overflow-hidden reveal-on-scroll" style="--reveal-delay:{{ $loop->index * 40 }}ms">
                                <button type="button"
                                    class="w-full cursor-pointer px-4 py-4 font-semibold text-primary hover:bg-gray-50 flex items-start gap-3 text-left transition-colors duration-150"
                                    @click="active = active === {{ $i }} ? null : {{ $i }}"
                                    :aria-expanded="active === {{ $i }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                        class="w-5 h-5 mt-0.5 flex-shrink-0 transition-transform duration-300"
                                        :class="active === {{ $i }} ? 'rotate-90' : 'rotate-0'">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                                    </svg>
                                    <span class="flex-1">{{ $faq->question }}</span>
                                </button>
                                <div x-show="active === {{ $i }}"
                                    x-transition:enter="transition-all ease-[cubic-bezier(0.23,1,0.32,1)] duration-300"
                                    x-transition:enter-start="opacity-0 max-h-0"
                                    x-transition:enter-end="opacity-100 max-h-96"
                                    x-transition:leave="transition-all ease-[cubic-bezier(0.23,1,0.32,1)] duration-200"
                                    x-transition:leave-start="opacity-100 max-h-96"
                                    x-transition:leave-end="opacity-0 max-h-0"
                                    class="overflow-hidden">
                                    <div class="px-4 pb-4 pl-12 text-sm text-gray-700 leading-relaxed border-t border-gray-100 pt-3">
                                        {!! nl2br(e($faq->answer)) !!}
                                    </div>
                                </div>
                            </div>
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
