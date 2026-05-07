<div class="fixed bottom-6 right-6 z-40 chatbot-bubble no-print" wire:ignore.self>
    @if (! $open)
        <button type="button"
                wire:click="toggle"
                class="bg-primary text-white rounded-full w-14 h-14 shadow-xl hover:bg-primary-mute focus:outline-none focus:ring-4 focus:ring-primary/30 flex items-center justify-center transition"
                aria-label="{{ __('messages.chatbot.open') }}">
            <x-heroicon-o-chat-bubble-left-ellipsis class="w-6 h-6" aria-hidden="true" />
        </button>
    @else
        <section role="dialog"
                 aria-label="{{ __('messages.chatbot.title') }}"
                 class="w-[360px] max-w-[calc(100vw-3rem)] h-[560px] max-h-[calc(100vh-6rem)] bg-white rounded-2xl shadow-2xl border border-gray-200 flex flex-col overflow-hidden">

            <header class="bg-primary text-white px-4 py-3 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <x-heroicon-s-chat-bubble-left-ellipsis class="w-5 h-5" aria-hidden="true" />
                    <div>
                        <p class="font-semibold text-sm leading-tight">{{ __('messages.chatbot.title') }}</p>
                        <p class="text-[11px] opacity-80 leading-tight">{{ __('messages.chatbot.subtitle') }}</p>
                    </div>
                </div>
                <button type="button" wire:click="toggle"
                        class="hover:bg-white/10 rounded p-1 focus:outline-none focus:ring-2 focus:ring-white"
                        aria-label="{{ __('messages.chatbot.close') }}">
                    <x-heroicon-o-x-mark class="w-5 h-5" aria-hidden="true" />
                </button>
            </header>

            <div class="flex-1 overflow-y-auto px-3 py-3 space-y-3 bg-gray-50" role="log" aria-live="polite" aria-atomic="false">
                @foreach ($messages as $m)
                    <div class="flex {{ $m['role'] === 'user' ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-[85%]">
                            <div class="rounded-2xl px-3 py-2 text-sm leading-relaxed
                                        {{ $m['role'] === 'user'
                                           ? 'bg-primary text-white rounded-br-sm'
                                           : 'bg-white border border-gray-200 text-gray-800 rounded-bl-sm' }}">
                                {!! nl2br(e($m['content'])) !!}
                            </div>
                            @if ($m['role'] === 'bot')
                                <div class="text-[10px] text-gray-500 mt-1 flex items-center gap-2 px-1">
                                    <span>{{ $m['at'] }}</span>
                                    @if ($m['citation'])
                                        <span class="inline-flex items-center gap-1">
                                            <x-heroicon-m-bookmark class="w-3 h-3" aria-hidden="true" />
                                            {{ $m['citation'] }}
                                        </span>
                                    @endif
                                    @if (! empty($m['fell_back']))
                                        <span class="inline-flex items-center gap-1 text-amber-700">
                                            <x-heroicon-m-exclamation-triangle class="w-3 h-3" aria-hidden="true" />
                                            {{ __('messages.chatbot.fallback') }}
                                        </span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach

                @if ($thinking)
                    <div class="flex justify-start">
                        <div class="bg-white border border-gray-200 rounded-2xl rounded-bl-sm px-3 py-2 text-sm text-gray-500 flex items-center gap-1" aria-label="{{ __('messages.chatbot.thinking') }}">
                            <span class="w-1.5 h-1.5 bg-gray-400 rounded-full animate-pulse"></span>
                            <span class="w-1.5 h-1.5 bg-gray-400 rounded-full animate-pulse" style="animation-delay:.15s"></span>
                            <span class="w-1.5 h-1.5 bg-gray-400 rounded-full animate-pulse" style="animation-delay:.3s"></span>
                        </div>
                    </div>
                @endif
            </div>

            @if (count($this->quickReplies) > 0 && count($messages) <= 1)
                <div class="px-3 py-2 border-t border-gray-200 bg-white flex flex-wrap gap-1.5">
                    @foreach ($this->quickReplies as $qr)
                        <button type="button"
                                wire:click="useQuickReply({{ $qr->id }})"
                                class="text-xs px-2.5 py-1 border border-gray-300 rounded-full text-gray-700 hover:bg-gray-50 hover:border-primary focus:outline-none focus:ring-2 focus:ring-primary/30">
                            {{ $qr->getTranslation('label', app()->getLocale(), false) ?: $qr->getTranslation('label', 'ms') }}
                        </button>
                    @endforeach
                </div>
            @endif

            @if ($error)
                <div class="px-3 py-2 bg-red-50 border-t border-red-200 text-xs text-red-700" role="alert">
                    {{ $error }}
                </div>
            @endif

            <form wire:submit.prevent="send" class="border-t border-gray-200 bg-white p-2 flex items-end gap-2">
                <label for="chatbot-input" class="sr-only">{{ __('messages.chatbot.input_label') }}</label>
                <textarea id="chatbot-input"
                          wire:model="input"
                          rows="1"
                          maxlength="2000"
                          @keydown.enter.prevent="$wire.send()"
                          placeholder="{{ __('messages.chatbot.placeholder') }}"
                          class="flex-1 resize-none border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary"></textarea>
                <button type="submit"
                        wire:loading.attr="disabled"
                        wire:target="send"
                        class="bg-primary text-white rounded-lg w-10 h-10 flex items-center justify-center hover:bg-primary-mute disabled:opacity-50 disabled:cursor-not-allowed focus:outline-none focus:ring-2 focus:ring-primary/30"
                        aria-label="{{ __('messages.chatbot.send') }}">
                    <x-heroicon-s-paper-airplane class="w-4 h-4" aria-hidden="true" />
                </button>
            </form>

            <p class="text-[10px] text-gray-500 px-3 py-1.5 bg-gray-50 border-t border-gray-200">
                {{ __('messages.chatbot.disclaimer') }}
            </p>
        </section>
    @endif
</div>
