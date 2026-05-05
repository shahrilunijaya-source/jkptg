@props(['count' => 5])
<div role="status" aria-live="polite" aria-label="{{ __('messages.states.loading') }}" class="space-y-2">
    @for ($i = 0; $i < $count; $i++)
        <div class="flex items-center gap-3 p-3 border rounded animate-pulse">
            <div class="w-10 h-10 rounded bg-gray-200"></div>
            <div class="flex-1 space-y-2">
                <div class="h-3 bg-gray-200 rounded w-3/4"></div>
                <div class="h-3 bg-gray-100 rounded w-1/2"></div>
            </div>
            <div class="w-16 h-6 bg-gray-100 rounded"></div>
        </div>
    @endfor
    <span class="sr-only">{{ __('messages.states.loading') }}</span>
</div>
