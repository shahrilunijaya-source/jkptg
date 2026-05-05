@props([
    'tone' => 'info',
    'title' => null,
    'message' => null,
    'dismissible' => true,
    'autoCloseMs' => 5000,
])
@php
    $tones = [
        'info' => ['cls' => 'bg-blue-50 text-blue-900 border-blue-200', 'icon' => 'heroicon-o-information-circle', 'iconCls' => 'text-blue-600'],
        'success' => ['cls' => 'bg-green-50 text-green-900 border-green-200', 'icon' => 'heroicon-o-check-circle', 'iconCls' => 'text-green-600'],
        'warning' => ['cls' => 'bg-amber-50 text-amber-900 border-amber-200', 'icon' => 'heroicon-o-exclamation-triangle', 'iconCls' => 'text-amber-600'],
        'error' => ['cls' => 'bg-red-50 text-red-900 border-red-200', 'icon' => 'heroicon-o-x-circle', 'iconCls' => 'text-red-600'],
    ];
    $cfg = $tones[$tone] ?? $tones['info'];
@endphp
<div x-data="{ show: true }"
     x-show="show"
     x-init="setTimeout(() => show = false, {{ (int) $autoCloseMs }})"
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0 translate-y-2"
     x-transition:enter-end="opacity-100 translate-y-0"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     role="status"
     aria-live="polite"
     class="rounded-lg border-l-4 {{ $cfg['cls'] }} p-4 shadow-md flex items-start gap-3 max-w-md">
    <x-dynamic-component :component="$cfg['icon']" class="w-5 h-5 flex-shrink-0 {{ $cfg['iconCls'] }}" />
    <div class="flex-1 text-sm">
        @if ($title)<div class="font-semibold mb-0.5">{{ $title }}</div>@endif
        <div>{{ $message ?? $slot }}</div>
    </div>
    @if ($dismissible)
        <button type="button" @click="show = false" class="text-gray-500 hover:text-gray-700" aria-label="{{ __('messages.states.toast.dismiss') }}">
            <x-heroicon-o-x-mark class="w-4 h-4" />
        </button>
    @endif
</div>
