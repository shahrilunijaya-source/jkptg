@extends('layouts.public')

@section('title', 'State Catalog | JKPTG')

@section('content')
<section class="container-page py-12 max-w-5xl">
    <h1 class="font-display text-3xl font-bold text-primary mb-2">Phase 4.5 - State Component Catalog</h1>
    <p class="text-gray-600 mb-8">Visual reference for loading / empty / error / success states. Reused across Phases 5-12.</p>

    <h2 class="font-display text-xl font-bold text-primary mt-10 mb-3">1. Loading - skeleton row (borang/tender)</h2>
    <x-state.skeleton-row :count="3" />

    <h2 class="font-display text-xl font-bold text-primary mt-10 mb-3">2. Loading - skeleton card (news)</h2>
    <x-state.skeleton-card :count="3" :cols="3" />

    <h2 class="font-display text-xl font-bold text-primary mt-10 mb-3">3. Loading - inline spinner</h2>
    <div class="flex flex-wrap gap-6">
        <x-state.loading size="sm" />
        <x-state.loading />
        <x-state.loading size="lg" :label="'Memuat carian...'" />
    </div>

    <h2 class="font-display text-xl font-bold text-primary mt-10 mb-3">4. Empty - generic</h2>
    <x-state.empty />

    <h2 class="font-display text-xl font-bold text-primary mt-10 mb-3">5. Empty - borang search</h2>
    <x-state.empty
        icon="heroicon-o-magnifying-glass"
        :title="'Tiada borang ditemui'"
        :message="__('messages.states.empty.borang_search')"
        :cta="'Tanya chatbot'"
        href="#chatbot"
        tone="info" />

    <h2 class="font-display text-xl font-bold text-primary mt-10 mb-3">6. Empty - news (warning tone)</h2>
    <x-state.empty
        icon="heroicon-o-newspaper"
        :title="__('messages.states.empty.news')"
        :message="'Lawat semula sebentar lagi.'"
        tone="warning" />

    <h2 class="font-display text-xl font-bold text-primary mt-10 mb-3">7. Error - with retry</h2>
    <x-state.error
        :message="__('messages.states.error.search_failed')"
        retry="search" />

    <h2 class="font-display text-xl font-bold text-primary mt-10 mb-3">8. Error - connection lost (chatbot)</h2>
    <x-state.error
        :title="'Sambungan tergendala'"
        :message="__('messages.states.error.connection')" />

    <h2 class="font-display text-xl font-bold text-primary mt-10 mb-3">9. Success - aduan submit</h2>
    <div class="rounded-lg border-l-4 border-green-500 bg-green-50 p-5 flex items-start gap-3">
        <x-heroicon-o-check-circle class="w-6 h-6 flex-shrink-0 text-green-600" />
        <div>
            <h3 class="font-display font-semibold text-lg text-green-900 mb-1">Aduan berjaya dihantar</h3>
            <p class="text-sm text-green-900">No. rujukan: <code class="bg-green-100 px-2 py-0.5 rounded font-mono">JKPTG-MB-2026-00123</code></p>
            <p class="text-xs text-green-800 mt-2">Notifikasi e-mel telah dihantar ke alamat yang didaftarkan.</p>
        </div>
    </div>

    <h2 class="font-display text-xl font-bold text-primary mt-10 mb-3">10. Toast - 4 tones</h2>
    <div class="space-y-3 max-w-md">
        <x-state.toast tone="info" :title="'Mod ringkas aktif'" :message="'Chatbot beralih kepada KB matcher.'" :auto-close-ms="0" />
        <x-state.toast tone="success" :message="__('messages.states.success.saved')" :auto-close-ms="0" />
        <x-state.toast tone="warning" :title="'Sumber tergendala'" :message="'Pelayan API memberi respons lambat.'" :auto-close-ms="0" />
        <x-state.toast tone="error" :title="'Quota LLM habis'" :message="'Tukar ke mod canned secara automatik.'" :auto-close-ms="0" />
    </div>

    <h2 class="font-display text-xl font-bold text-primary mt-10 mb-3">11. Chatbot - first open (greeting + quick replies)</h2>
    <div class="rounded-lg border bg-white shadow-md p-5 max-w-md">
        <div class="flex items-start gap-3 mb-3">
            <div class="w-9 h-9 rounded-full bg-primary text-white flex items-center justify-center flex-shrink-0">
                <x-heroicon-o-chat-bubble-left-ellipsis class="w-5 h-5" />
            </div>
            <div class="bg-gray-100 rounded-2xl rounded-tl-sm p-3 text-sm">
                {{ __('messages.states.chatbot.greeting') }}
            </div>
        </div>
        <div class="flex flex-wrap gap-2 mb-3">
            @foreach(\App\Models\ChatbotQuickReply::where('active', true)->orderBy('sort')->get() as $qr)
                <button type="button" class="text-xs bg-primary-pale text-primary px-3 py-1.5 rounded-full hover:bg-primary-200">
                    {{ $qr->label }}
                </button>
            @endforeach
        </div>
        <p class="text-xs text-gray-500 italic flex items-center gap-1">
            <x-heroicon-o-shield-check class="w-3.5 h-3.5" />
            {{ __('messages.states.chatbot.privacy') }}
        </p>
    </div>

    <h2 class="font-display text-xl font-bold text-primary mt-10 mb-3">12. Chatbot - typing dots</h2>
    <div class="rounded-lg border bg-white p-5 max-w-md">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-full bg-primary text-white flex items-center justify-center">
                <x-heroicon-o-chat-bubble-left-ellipsis class="w-5 h-5" />
            </div>
            <div class="bg-gray-100 rounded-2xl rounded-tl-sm p-3 flex gap-1">
                <span class="w-2 h-2 rounded-full bg-gray-400 animate-bounce" style="animation-delay:0s"></span>
                <span class="w-2 h-2 rounded-full bg-gray-400 animate-bounce" style="animation-delay:0.15s"></span>
                <span class="w-2 h-2 rounded-full bg-gray-400 animate-bounce" style="animation-delay:0.3s"></span>
            </div>
        </div>
    </div>

    <h2 class="font-display text-xl font-bold text-primary mt-10 mb-3">13. Login - inline error</h2>
    <form class="rounded-lg border p-5 max-w-md space-y-3 bg-white">
        <label class="block">
            <span class="text-sm font-semibold">E-mel</span>
            <input type="email" value="admin@jkptg.demo" class="mt-1 block w-full rounded border-red-300 focus:border-red-500" aria-invalid="true" />
        </label>
        <label class="block">
            <span class="text-sm font-semibold">Kata laluan</span>
            <input type="password" value="••••••" class="mt-1 block w-full rounded border-red-300 focus:border-red-500" aria-invalid="true" />
        </label>
        <p class="text-sm text-red-600 flex items-center gap-1">
            <x-heroicon-o-exclamation-circle class="w-4 h-4" />
            {{ __('messages.states.error.login') }}
        </p>
        <button type="button" class="btn-primary w-full justify-center" disabled>
            <x-heroicon-o-arrow-path class="w-4 h-4 animate-spin" />
            Sedang sahkan...
        </button>
    </form>

    <h2 class="font-display text-xl font-bold text-primary mt-10 mb-3">14. Search - empty results with popular suggestions</h2>
    <x-state.empty
        icon="heroicon-o-magnifying-glass"
        :title="__('messages.states.empty.search', ['q' => 'foo bar'])"
        :message="'Cuba kata kunci popular di bawah:'"
        tone="info">
        <div class="mt-3 flex flex-wrap gap-2 justify-center">
            <a href="#" class="text-xs bg-white border px-3 py-1 rounded-full hover:border-primary">pengambilan tanah</a>
            <a href="#" class="text-xs bg-white border px-3 py-1 rounded-full hover:border-primary">pusaka</a>
            <a href="#" class="text-xs bg-white border px-3 py-1 rounded-full hover:border-primary">borang TK01</a>
            <a href="#" class="text-xs bg-white border px-3 py-1 rounded-full hover:border-primary">pajakan</a>
            <a href="#" class="text-xs bg-white border px-3 py-1 rounded-full hover:border-primary">FAQ</a>
        </div>
    </x-state.empty>

    <h2 class="font-display text-xl font-bold text-primary mt-10 mb-3">15. /akaun - first visit empty state</h2>
    <x-state.empty
        icon="heroicon-o-user-circle"
        :title="'Selamat datang!'"
        :message="__('messages.states.empty.akaun')"
        tone="info">
        <div class="mt-4 grid grid-cols-1 sm:grid-cols-3 gap-3 w-full">
            <a href="#" class="bg-white border rounded p-4 hover:border-primary text-left">
                <x-heroicon-o-document-text class="w-6 h-6 text-primary mb-2" />
                <div class="font-semibold text-sm text-primary">Pengambilan Tanah</div>
            </a>
            <a href="#" class="bg-white border rounded p-4 hover:border-primary text-left">
                <x-heroicon-o-document-text class="w-6 h-6 text-primary mb-2" />
                <div class="font-semibold text-sm text-primary">Pusaka Bukan Islam</div>
            </a>
            <a href="#" class="bg-white border rounded p-4 hover:border-primary text-left">
                <x-heroicon-o-document-text class="w-6 h-6 text-primary mb-2" />
                <div class="font-semibold text-sm text-primary">Pajakan Tanah</div>
            </a>
        </div>
    </x-state.empty>

    <h2 class="font-display text-xl font-bold text-primary mt-10 mb-3">16. Filament dashboard skeleton bars</h2>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @for($i=0;$i<4;$i++)
            <div class="rounded-lg border p-4 animate-pulse space-y-3">
                <div class="h-3 bg-gray-100 rounded w-1/2"></div>
                <div class="h-8 bg-gray-200 rounded w-3/4"></div>
                <div class="h-2 bg-gray-100 rounded"></div>
            </div>
        @endfor
    </div>
</section>
@endsection
