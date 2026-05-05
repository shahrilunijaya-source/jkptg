@props([
    'icon' => 'heroicon-o-inbox',
    'title' => null,
    'message' => null,
    'cta' => null,
    'href' => null,
    'tone' => 'neutral',
])
@php
    $titleText = $title ?? __('messages.states.empty.title');
    $messageText = $message ?? __('messages.states.empty.message');
    $tones = [
        'neutral' => 'bg-gray-50 text-gray-700 border-gray-200',
        'info' => 'bg-primary-pale text-primary border-primary-200',
        'warning' => 'bg-amber-50 text-amber-900 border-amber-200',
    ];
    $cls = $tones[$tone] ?? $tones['neutral'];
@endphp
<div role="status" aria-live="polite"
     class="rounded-lg border-2 border-dashed {{ $cls }} p-8 text-center flex flex-col items-center gap-3">
    <x-dynamic-component :component="$icon" class="w-12 h-12 opacity-60" />
    <h3 class="font-display font-semibold text-lg">{{ $titleText }}</h3>
    <p class="text-sm max-w-md">{{ $messageText }}</p>
    @if ($cta && $href)
        <a href="{{ $href }}" class="btn-primary mt-2">
            {{ $cta }}
            <x-heroicon-o-arrow-right class="w-4 h-4" />
        </a>
    @elseif ($cta)
        <button type="button" class="btn-primary mt-2">{{ $cta }}</button>
    @endif
    {{ $slot }}
</div>
