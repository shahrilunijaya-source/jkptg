@props([
    'title' => null,
    'message' => null,
    'retry' => null,
    'support' => 'webmaster@jkptg.gov.my',
])
@php
    $titleText = $title ?? __('messages.states.error.title');
    $messageText = $message ?? __('messages.states.error.message');
@endphp
<div role="alert" aria-live="assertive"
     class="rounded-lg border-l-4 border-red-500 bg-red-50 p-5 text-red-900 flex items-start gap-3">
    <x-heroicon-o-exclamation-triangle class="w-6 h-6 flex-shrink-0 text-red-600" />
    <div class="flex-1">
        <h3 class="font-display font-semibold text-lg mb-1">{{ $titleText }}</h3>
        <p class="text-sm">{{ $messageText }}</p>
        <div class="mt-3 flex flex-wrap items-center gap-3 text-sm">
            @if ($retry)
                <button type="button" wire:click="{{ $retry }}" class="btn-primary !bg-red-600 !hover:bg-red-700">
                    <x-heroicon-o-arrow-path class="w-4 h-4" />
                    {{ __('messages.states.error.retry') }}
                </button>
            @endif
            @if ($support)
                <a href="mailto:{{ $support }}" class="text-red-700 hover:underline">
                    {{ __('messages.states.error.contact', ['email' => $support]) }}
                </a>
            @endif
        </div>
        {{ $slot }}
    </div>
</div>
