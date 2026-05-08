@props([
    'label' => null,
    'title' => '',
    'subtitle' => null,
    'icon' => null,
    'image' => null,
    'imageFallback' => 'https://images.unsplash.com/photo-1707898525717-52772505df19?auto=format&fit=crop&w=2400&q=80',
    'compact' => false,
    'variant' => 'pictorial',
])

@php
    $bgImage = $image ?? $imageFallback;
@endphp

@if($variant === 'flat')
    {{-- Flat fallback: cream canvas civic band (legacy/special-case use) --}}
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
                <div class="mt-7 flex flex-wrap gap-3">{{ $slot }}</div>
            @endif
        </div>
    </section>
@else
    {{-- Pictorial: forest overlay over photo, white type, bronze eyebrow --}}
    <section {{ $attributes->merge(['class' => 'relative isolate overflow-hidden']) }}>
        <div class="absolute inset-0 z-0"
             style="background-image: url('{{ $bgImage }}'); background-size: cover; background-position: center 40%;"
             aria-hidden="true"></div>
        <div class="absolute inset-0 z-0"
             style="background: linear-gradient(180deg, rgba(11,50,32,0.65) 0%, rgba(11,50,32,0.72) 50%, rgba(11,50,32,0.85) 100%);"
             aria-hidden="true"></div>
        <div class="absolute inset-0 z-0 mix-blend-overlay opacity-25"
             style="background-image: radial-gradient(circle at 30% 35%, rgba(255,255,255,0.18), transparent 55%);"
             aria-hidden="true"></div>

        <div @class([
            'relative z-10 container-page text-white',
            'py-12 md:py-16' => $compact,
            'py-16 md:py-24' => ! $compact,
        ])>
            @if($label)
                <div class="flex items-center gap-3 mb-5">
                    <span class="inline-block w-8 h-px bg-bronze-light" aria-hidden="true"></span>
                    @if($icon)
                        <x-dynamic-component :component="'heroicon-o-' . $icon" class="w-4 h-4 text-bronze-light" aria-hidden="true" />
                    @endif
                    <span class="text-[12px] uppercase tracking-[0.2em] font-semibold text-bronze-light">{{ $label }}</span>
                </div>
            @endif

            <h1 @class([
                'font-bold leading-[1.05] tracking-tight mb-4 max-w-3xl [text-wrap:balance]',
                'text-[32px] md:text-[44px]' => $compact,
                'text-[40px] md:text-[56px]' => ! $compact,
            ])>{{ $title }}</h1>

            @if($subtitle)
                <p class="text-[16px] md:text-[18px] text-white/85 leading-relaxed max-w-2xl">{{ $subtitle }}</p>
            @endif

            @if(! $slot->isEmpty())
                <div class="mt-8 flex flex-wrap gap-3">
                    {{ $slot }}
                </div>
            @endif
        </div>
    </section>
@endif
