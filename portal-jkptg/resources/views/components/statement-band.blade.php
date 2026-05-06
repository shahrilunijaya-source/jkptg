@props([
    'label' => null,
    'title' => '',
    'subtitle' => null,
    'icon' => null,
    'compact' => false,
])

<section {{ $attributes->merge(['class' => 'border-b border-slate-200 bg-canvas']) }}>
    <div @class([
        'container-page',
        'py-10 md:py-14' => $compact,
        'py-14 md:py-20' => ! $compact,
    ])>
        @if($label)
            <div class="flex items-center gap-2 mb-4">
                @if($icon)
                    <x-dynamic-component :component="'heroicon-o-' . $icon" class="w-4 h-4 text-bronze" aria-hidden="true" />
                @endif
                <span class="eyebrow">{{ $label }}</span>
            </div>
        @endif

        <h1 @class([
            'font-bold text-canvas-ink leading-[1.1] tracking-tight mb-4 max-w-3xl',
            'text-[28px] md:text-[36px]' => $compact,
            'text-[36px] md:text-[48px]' => ! $compact,
        ])>{{ $title }}</h1>

        @if($subtitle)
            <p class="text-[16px] md:text-[17px] text-slate-700 leading-relaxed max-w-2xl">{{ $subtitle }}</p>
        @endif

        @if(! $slot->isEmpty())
            <div class="mt-7 flex flex-wrap gap-3">
                {{ $slot }}
            </div>
        @endif
    </div>
</section>
