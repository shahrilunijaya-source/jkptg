<aside aria-label="{{ __('messages.a11y.panel_title') }}"
       class="fixed left-0 top-1/3 z-40 hidden md:flex flex-col bg-primary text-white rounded-r shadow-lg no-print">
    <button type="button"
            @click="a11y.large = !a11y.large; a11y.xlarge = false"
            class="p-3 hover:bg-primary-mute focus:bg-primary-mute focus:outline-none"
            :aria-pressed="a11y.large ? 'true' : 'false'"
            :title="'{{ __('messages.a11y.text_larger') }}'">
        <x-heroicon-o-magnifying-glass-plus class="w-5 h-5" />
        <span class="sr-only">{{ __('messages.a11y.text_larger') }}</span>
    </button>
    <button type="button"
            @click="a11y.xlarge = !a11y.xlarge; a11y.large = false"
            class="p-3 hover:bg-primary-mute focus:bg-primary-mute focus:outline-none"
            :aria-pressed="a11y.xlarge ? 'true' : 'false'"
            :title="'{{ __('messages.a11y.text_smaller') }}'">
        <x-heroicon-o-magnifying-glass-minus class="w-5 h-5" />
        <span class="sr-only">{{ __('messages.a11y.text_smaller') }}</span>
    </button>
    <button type="button"
            @click="a11y.contrast = !a11y.contrast"
            class="p-3 hover:bg-primary-mute focus:bg-primary-mute focus:outline-none"
            :aria-pressed="a11y.contrast ? 'true' : 'false'"
            :title="'{{ __('messages.a11y.high_contrast') }}'">
        <x-heroicon-o-sun class="w-5 h-5" />
        <span class="sr-only">{{ __('messages.a11y.high_contrast') }}</span>
    </button>
    <button type="button"
            @click="a11y.large = false; a11y.xlarge = false; a11y.contrast = false"
            class="p-3 hover:bg-primary-mute focus:bg-primary-mute focus:outline-none"
            :title="'{{ __('messages.a11y.reset') }}'">
        <x-heroicon-o-arrow-path class="w-5 h-5" />
        <span class="sr-only">{{ __('messages.a11y.reset') }}</span>
    </button>
</aside>
