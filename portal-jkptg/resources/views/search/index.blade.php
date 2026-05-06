@extends('layouts.public')

@section('title', __('messages.search.page_title') . ($q ? ': ' . $q : '') . ' â€” JKPTG')

@section('content')
<section class="container-page py-8">
    <h1 class="font-display font-semibold text-3xl text-primary mb-2">{{ __('messages.search.page_title') }}</h1>
    @if ($q)
        <p class="text-gray-700 mb-6">
            {{ __('messages.search.results_for', ['q' => $q]) }}:
            <strong class="text-primary">{{ $grandTotal }}</strong>
            {{ __('messages.search.results_count') }}
        </p>
    @else
        <p class="text-gray-600 mb-6">{{ __('messages.search.intro') }}</p>
    @endif

    <form method="get" action="{{ route('search.index') }}" class="mb-6 flex gap-2 flex-wrap" role="search">
        <label for="search-q" class="sr-only">{{ __('messages.search.input_label') }}</label>
        <input id="search-q" type="search" name="q" value="{{ $q }}" required minlength="2"
               placeholder="{{ __('messages.search.placeholder') }}"
               class="flex-1 min-w-[260px] border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-primary/30 focus:border-primary focus:outline-none">
        <select name="type" class="border border-gray-300 rounded-lg px-3 py-2.5 bg-white focus:ring-2 focus:ring-primary/30 focus:border-primary focus:outline-none">
            <option value="all" @selected($type === 'all')>{{ __('messages.search.type_all') }}</option>
            <option value="pages" @selected($type === 'pages')>{{ __('messages.search.type_pages') }}</option>
            <option value="services" @selected($type === 'services')>{{ __('messages.search.type_services') }}</option>
            <option value="news" @selected($type === 'news')>{{ __('messages.search.type_news') }}</option>
            <option value="tenders" @selected($type === 'tenders')>{{ __('messages.search.type_tenders') }}</option>
            <option value="forms" @selected($type === 'forms')>{{ __('messages.search.type_forms') }}</option>
            <option value="faqs" @selected($type === 'faqs')>{{ __('messages.search.type_faqs') }}</option>
            <option value="kb" @selected($type === 'kb')>{{ __('messages.search.type_kb') }}</option>
        </select>
        <button type="submit" class="bg-primary text-white px-5 py-2.5 rounded-lg hover:bg-primary-mute focus:outline-none focus:ring-2 focus:ring-primary/30">
            {{ __('messages.search.button') }}
        </button>
    </form>

    @if ($q && mb_strlen($q) < 2)
        <x-state.error :title="__('messages.search.too_short_title')" :message="__('messages.search.too_short_message')" />
    @elseif ($q && $grandTotal === 0)
        <x-state.empty :title="__('messages.search.empty_title')" :message="__('messages.search.empty_message', ['q' => $q])" />
    @endif

    @php $sectionMap = [
        'pages' => ['label' => __('messages.search.type_pages'), 'icon' => 'document'],
        'services' => ['label' => __('messages.search.type_services'), 'icon' => 'briefcase'],
        'news' => ['label' => __('messages.search.type_news'), 'icon' => 'newspaper'],
        'tenders' => ['label' => __('messages.search.type_tenders'), 'icon' => 'clipboard-document-list'],
        'forms' => ['label' => __('messages.search.type_forms'), 'icon' => 'document-arrow-down'],
        'faqs' => ['label' => __('messages.search.type_faqs'), 'icon' => 'question-mark-circle'],
        'kb' => ['label' => __('messages.search.type_kb'), 'icon' => 'book-open'],
    ]; @endphp

    @foreach ($sectionMap as $key => $meta)
        @php $items = $results[$key]; $tot = $totals[$key]; @endphp
        @if ($items->count() > 0)
            <section class="mb-8">
                <h2 class="font-display font-semibold text-xl text-primary mb-3 flex items-center gap-2">
                    <x-dynamic-component :component="'heroicon-o-' . $meta['icon']" class="w-5 h-5" aria-hidden="true" />
                    {{ $meta['label'] }} <span class="text-sm font-normal text-gray-500">({{ $tot }})</span>
                </h2>
                <ul class="divide-y divide-gray-200 border border-gray-200 rounded-lg overflow-hidden bg-white">
                    @foreach ($items as $item)
                        <li class="p-4 hover:bg-gray-50">
                            @switch($key)
                                @case('pages')
                                    <a href="{{ route('page.show', $item->slug) }}" class="block">
                                        <h3 class="font-semibold text-primary">{{ $item->title }}</h3>
                                        <p class="text-sm text-gray-600 mt-1 line-clamp-2">{{ \Illuminate\Support\Str::limit(strip_tags($item->body), 180) }}</p>
                                    </a>
                                    @break
                                @case('services')
                                    <a href="{{ route('service.show', $item->slug) }}" class="block">
                                        <h3 class="font-semibold text-primary">{{ $item->name }}</h3>
                                        <p class="text-sm text-gray-600 mt-1 line-clamp-2">{{ $item->summary }}</p>
                                    </a>
                                    @break
                                @case('news')
                                    <div>
                                        <h3 class="font-semibold text-primary">{{ $item->title }}</h3>
                                        <p class="text-xs text-gray-500 mt-0.5">{{ $item->published_at?->isoFormat('D MMM Y') }} Â· {{ $item->type }}</p>
                                        <p class="text-sm text-gray-600 mt-1 line-clamp-2">{{ \Illuminate\Support\Str::limit($item->excerpt, 180) }}</p>
                                    </div>
                                    @break
                                @case('tenders')
                                    <div>
                                        <h3 class="font-semibold text-primary">{{ $item->title }}</h3>
                                        <p class="text-xs text-gray-500 mt-0.5">{{ $item->reference_no }} Â· {{ ucfirst($item->status) }} Â· {{ __('messages.search.closes') }}: {{ $item->closes_at?->isoFormat('D MMM Y') }}</p>
                                    </div>
                                    @break
                                @case('forms')
                                    <a href="{{ route('borang.index', ['q' => $q]) }}" class="block">
                                        <h3 class="font-semibold text-primary">{{ $item->name }}</h3>
                                        <p class="text-xs text-gray-500 mt-0.5">{{ $item->slug }}.pdf Â· {{ $item->file_size_human ?? '' }}</p>
                                    </a>
                                    @break
                                @case('faqs')
                                    <div>
                                        <h3 class="font-semibold text-primary">{{ $item->question }}</h3>
                                        <p class="text-sm text-gray-600 mt-1 line-clamp-2">{{ \Illuminate\Support\Str::limit(strip_tags($item->answer), 200) }}</p>
                                    </div>
                                    @break
                                @case('kb')
                                    <div>
                                        <h3 class="font-semibold text-primary">{{ $item->question }}</h3>
                                        <p class="text-sm text-gray-600 mt-1 line-clamp-2">{{ \Illuminate\Support\Str::limit($item->answer, 200) }}</p>
                                    </div>
                                    @break
                            @endswitch
                        </li>
                    @endforeach
                </ul>
                @if ($tot > $items->count())
                    <p class="text-xs text-gray-500 mt-2">
                        {{ __('messages.search.showing_of', ['shown' => $items->count(), 'total' => $tot]) }}
                        <a href="{{ route('search.index', ['q' => $q, 'type' => $key]) }}" class="text-primary hover:underline">{{ __('messages.search.view_all') }}</a>
                    </p>
                @endif
            </section>

<x-breadcrumb :items="[
    ['label' => __('messages.nav.utama'), 'url' => route('home')],
    ['label' => __('messages.search.crumb')],
]" />
        @endif
    @endforeach
</section>
@endsection
