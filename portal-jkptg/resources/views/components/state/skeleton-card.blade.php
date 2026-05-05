@props(['count' => 6, 'cols' => 3])
<div role="status" aria-live="polite" aria-label="{{ __('messages.states.loading') }}"
     class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-{{ $cols }} gap-4">
    @for ($i = 0; $i < $count; $i++)
        <div class="rounded-lg border overflow-hidden animate-pulse">
            <div class="h-40 bg-gray-200"></div>
            <div class="p-4 space-y-3">
                <div class="h-3 bg-gray-100 rounded w-1/4"></div>
                <div class="h-4 bg-gray-200 rounded w-3/4"></div>
                <div class="h-3 bg-gray-100 rounded"></div>
                <div class="h-3 bg-gray-100 rounded w-5/6"></div>
            </div>
        </div>
    @endfor
    <span class="sr-only">{{ __('messages.states.loading') }}</span>
</div>
