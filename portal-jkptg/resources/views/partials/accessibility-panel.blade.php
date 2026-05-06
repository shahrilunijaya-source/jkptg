<aside aria-label="{{ __('messages.a11y.panel_title') }}"
       x-data="{ a11yOpen: false }"
       class="fixed right-0 top-1/2 -translate-y-1/2 z-30 hidden md:flex items-stretch no-print">
    {{-- Toggle handle --}}
    <button type="button"
            @click="a11yOpen = !a11yOpen"
            :aria-expanded="a11yOpen ? 'true' : 'false'"
            class="bg-slate-900 text-white w-7 hover:bg-slate-800 focus:outline-none focus-visible:bg-slate-800 transition-colors duration-150 flex items-center justify-center"
            :title="a11yOpen ? '{{ __('messages.nav.close_menu') }}' : '{{ __('messages.a11y.panel_title') }}'">
        <span class="font-mono text-[10px] uppercase tracking-[0.18em] [writing-mode:vertical-rl] py-3">A11Y</span>
    </button>

    {{-- Panel --}}
    <div x-show="a11yOpen"
         x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-x-2"
         x-transition:enter-end="opacity-100 translate-x-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-x-0"
         x-transition:leave-end="opacity-0 translate-x-2"
         class="bg-slate-900 text-white flex flex-col border-l border-white/10">
        <button type="button"
                @click="a11y.large = !a11y.large; a11y.xlarge = false"
                class="p-3 hover:bg-slate-800 focus:outline-none focus-visible:bg-slate-800 transition-colors duration-150"
                :class="a11y.large ? 'bg-slate-800' : ''"
                :aria-pressed="a11y.large ? 'true' : 'false'"
                :title="'{{ __('messages.a11y.text_larger') }}'">
            <x-heroicon-o-magnifying-glass-plus class="w-5 h-5" />
            <span class="sr-only">{{ __('messages.a11y.text_larger') }}</span>
        </button>
        <button type="button"
                @click="a11y.xlarge = !a11y.xlarge; a11y.large = false"
                class="p-3 hover:bg-slate-800 focus:outline-none focus-visible:bg-slate-800 transition-colors duration-150"
                :class="a11y.xlarge ? 'bg-slate-800' : ''"
                :aria-pressed="a11y.xlarge ? 'true' : 'false'"
                :title="'{{ __('messages.a11y.text_smaller') }}'">
            <x-heroicon-o-magnifying-glass-minus class="w-5 h-5" />
            <span class="sr-only">{{ __('messages.a11y.text_smaller') }}</span>
        </button>
        <button type="button"
                @click="a11y.contrast = !a11y.contrast"
                class="p-3 hover:bg-slate-800 focus:outline-none focus-visible:bg-slate-800 transition-colors duration-150"
                :class="a11y.contrast ? 'bg-slate-800' : ''"
                :aria-pressed="a11y.contrast ? 'true' : 'false'"
                :title="'{{ __('messages.a11y.high_contrast') }}'">
            <x-heroicon-o-sun class="w-5 h-5" />
            <span class="sr-only">{{ __('messages.a11y.high_contrast') }}</span>
        </button>
        <button type="button"
                @click="a11y.large = false; a11y.xlarge = false; a11y.contrast = false"
                class="p-3 hover:bg-slate-800 focus:outline-none focus-visible:bg-slate-800 transition-colors duration-150"
                :title="'{{ __('messages.a11y.reset') }}'">
            <x-heroicon-o-arrow-path class="w-5 h-5" />
            <span class="sr-only">{{ __('messages.a11y.reset') }}</span>
        </button>
    </div>
</aside>
