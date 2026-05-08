@props(['items' => []])
<nav aria-label="Breadcrumb" class="bg-gray-50 border-b">
    <ol class="container-page py-3 text-sm flex flex-wrap gap-x-2 text-gray-600">
        <li><a href="{{ route('home') }}" class="hover:text-primary">{{ __('messages.nav.utama') }}</a></li>
        @foreach($items as $i => $item)
            <li aria-hidden="true">&rsaquo;</li>
            @if((isset($item['href']) || isset($item['url'])) && !$loop->last)
                <li><a href="{{ $item['href'] ?? $item['url'] }}" class="hover:text-primary">{{ $item['label'] }}</a></li>
            @else
                <li><span class="text-primary font-medium">{{ $item['label'] }}</span></li>
            @endif
        @endforeach
    </ol>
</nav>
