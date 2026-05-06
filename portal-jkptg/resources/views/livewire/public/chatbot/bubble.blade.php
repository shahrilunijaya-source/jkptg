<div class="fixed bottom-6 right-6 z-40 chatbot-bubble no-print" wire:ignore.self>
    @if (! $open)
        <button type="button"
                wire:click="toggle"
                class="bg-primary text-white px-4 py-3 hover:bg-primary-700 focus-visible:bg-primary-700 transition-colors duration-150 inline-flex items-center gap-2 border border-primary"
                aria-label="{{ __('messages.chatbot.open') }}">
            <x-heroicon-o-chat-bubble-left-ellipsis class="w-4 h-4" aria-hidden="true" />
            <span class="font-mono uppercase tracking-[0.12em] text-[11px] font-medium">{{ __('messages.chatbot.title') }}</span>
        </button>
    @else
        <section role="dialog"
                 aria-label="{{ __('messages.chatbot.title') }}"
                 class="w-[380px] max-w-[calc(100vw-3rem)] h-[580px] max-h-[calc(100vh-6rem)] bg-white border border-slate-300 flex flex-col overflow-hidden">

            <header class="bg-slate-900 text-white px-4 py-3 flex items-center justify-between border-b border-slate-700">
                <div class="flex items-center gap-2.5 min-w-0">
                    <x-heroicon-s-chat-bubble-left-ellipsis class="w-4 h-4 flex-shrink-0" aria-hidden="true" />
                    <div class="min-w-0">
                        <p class="font-semibold text-[13px] leading-tight">{{ __('messages.chatbot.title') }}</p>
                        <p class="font-mono uppercase tracking-[0.08em] text-[10px] text-white/60 leading-tight mt-0.5">{{ __('messages.chatbot.subtitle') }}</p>
                    </div>
                </div>
                <button type="button" wire:click="toggle"
                        class="hover:bg-white/10 p-1 focus:outline-none focus-visible:bg-white/10"
                        aria-label="{{ __('messages.chatbot.close') }}">
                    <x-heroicon-o-x-mark class="w-4 h-4" aria-hidden="true" />
                </button>
            </header>

            <div class="flex-1 overflow-y-auto px-4 py-3 space-y-3 bg-slate-50" role="log" aria-live="polite" aria-atomic="false">
                @foreach ($messages as $m)
                    <div class="flex {{ $m['role'] === 'user' ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-[85%]">
                            <div class="px-3 py-2 text-[13px] leading-relaxed border
                                        {{ $m['role'] === 'user'
                                           ? 'bg-primary text-white border-primary'
                                           : 'bg-white border-slate-200 text-slate-900' }}">
                                {!! nl2br(e($m['content'])) !!}
                            </div>
                            @if ($m['role'] === 'bot')
                                <div class="font-mono tabular-nums text-[10px] text-slate-500 mt-1 flex items-center gap-2 px-1">
                                    <span>{{ $m['at'] }}</span>
                                    @if ($m['citation'])
                                        <span class="inline-flex items-center gap-1 uppercase tracking-[0.06em]">
                                            <span aria-hidden="true">·</span>
                                            REF · {{ $m['citation'] }}
                                        </span>
                                    @endif
                                    @if (! empty($m['fell_back']))
                                        <span class="inline-flex items-center gap-1 uppercase tracking-[0.06em] text-amber-700">
                                            <span aria-hidden="true">·</span>
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
                        <div class="bg-white border border-slate-200 px-3 py-2 text-[13px] text-slate-500 flex items-center gap-1" aria-label="{{ __('messages.chatbot.thinking') }}">
                            <span class="w-1.5 h-1.5 bg-slate-400 animate-pulse"></span>
                            <span class="w-1.5 h-1.5 bg-slate-400 animate-pulse" style="animation-delay:.15s"></span>
                            <span class="w-1.5 h-1.5 bg-slate-400 animate-pulse" style="animation-delay:.3s"></span>
                        </div>
                    </div>
                @endif
            </div>

            @if (count($this->quickReplies) > 0 && count($messages) <= 1)
                <div class="px-3 py-2 border-t border-slate-200 bg-white flex flex-wrap gap-1.5">
                    @foreach ($this->quickReplies as $qr)
                        <button type="button"
                                wire:click="useQuickReply({{ $qr->id }})"
                                class="font-mono text-[11px] uppercase tracking-[0.06em] px-2.5 py-1 border border-slate-300 text-slate-700 hover:border-slate-900 hover:text-slate-900 focus:outline-none focus-visible:border-primary focus-visible:text-primary transition-colors duration-150">
                            {{ $qr->getTranslation('label', app()->getLocale(), false) ?: $qr->getTranslation('label', 'ms') }}
                        </button>
                    @endforeach
                </div>
            @endif

            @if ($error)
                <div class="px-3 py-2 bg-red-50 border-t border-red-200 text-[12px] text-red-700" role="alert">
                    {{ $error }}
                </div>
            @endif

            <form wire:submit.prevent="send" class="border-t border-slate-200 bg-white p-2 flex items-end gap-2">
                <label for="chatbot-input" class="sr-only">{{ __('messages.chatbot.input_label') }}</label>
                <textarea id="chatbot-input"
                          wire:model="input"
                          rows="1"
                          maxlength="2000"
                          @keydown.enter.prevent="$wire.send()"
                          placeholder="{{ __('messages.chatbot.placeholder') }}"
                          class="flex-1 resize-none border border-slate-300 px-3 py-2 text-[13px] focus:outline-none focus:border-primary"></textarea>
                <button type="submit"
                        wire:loading.attr="disabled"
                        wire:target="send"
                        class="bg-primary text-white w-10 h-10 flex items-center justify-center hover:bg-primary-700 disabled:opacity-50 disabled:cursor-not-allowed focus:outline-none border border-primary transition-colors duration-150"
                        aria-label="{{ __('messages.chatbot.send') }}">
                    <x-heroicon-s-paper-airplane class="w-4 h-4" aria-hidden="true" />
                </button>
            </form>

            <p class="font-mono text-[10px] text-slate-500 px-3 py-1.5 bg-slate-50 border-t border-slate-200 leading-snug">
                {{ __('messages.chatbot.disclaimer') }}
            </p>
        </section>
    @endif
</div>
