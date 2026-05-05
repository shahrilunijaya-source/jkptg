@props([
    'label' => null,
    'size' => 'md',
])
@php
    $sizes = ['sm' => 'w-4 h-4', 'md' => 'w-6 h-6', 'lg' => 'w-10 h-10'];
    $sz = $sizes[$size] ?? $sizes['md'];
    $labelText = $label ?? __('messages.states.loading');
@endphp
<div role="status" aria-live="polite" class="inline-flex items-center gap-2 text-primary">
    <svg class="{{ $sz }} animate-spin" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3"></circle>
        <path class="opacity-75" d="M4 12a8 8 0 0 1 8-8" stroke="currentColor" stroke-width="3" stroke-linecap="round"></path>
    </svg>
    <span>{{ $labelText }}</span>
</div>
