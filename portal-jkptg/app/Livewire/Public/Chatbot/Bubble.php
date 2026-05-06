<?php

namespace App\Livewire\Public\Chatbot;

use App\Models\ChatbotQuickReply;
use App\Models\ChatMessage;
use App\Models\ChatSession;
use App\Services\Llm\LlmService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Bubble extends Component
{
    public bool $open = false;
    public string $input = '';
    public ?string $error = null;
    public bool $thinking = false;
    public ?int $sessionId = null;
    public string $sessionUuid;
    public array $messages = [];

    public function mount(): void
    {
        $this->sessionUuid = (string) (request()->cookie('jkptg_chat_uuid') ?? Str::uuid());
    }

    #[Computed]
    public function quickReplies()
    {
        return ChatbotQuickReply::where('active', true)->orderBy('sort')->get();
    }

    public function toggle(): void
    {
        $this->open = !$this->open;
        if ($this->open && $this->sessionId === null) {
            $this->bootSession();
        }
    }

    public function useQuickReply(int $id): void
    {
        $qr = ChatbotQuickReply::find($id);
        if (!$qr) {
            return;
        }
        $this->input = $qr->payload_query;
        $this->send();
    }

    public function send(): void
    {
        $this->error = null;
        $msg = trim($this->input);
        if ($msg === '') {
            return;
        }

        $key = 'chatbot:' . (request()->ip() ?? 'unknown');
        $perHour = (int) config('chatbot.rate_limit.per_ip_per_hour', 10);
        if (RateLimiter::tooManyAttempts($key, $perHour)) {
            $secs = RateLimiter::availableIn($key);
            $this->error = __('messages.chatbot.rate_limited', ['secs' => $secs]);
            return;
        }
        RateLimiter::hit($key, 3600);

        if ($this->sessionId === null) {
            $this->bootSession();
        }

        $session = ChatSession::find($this->sessionId);
        if (!$session) {
            $this->error = __('messages.chatbot.error_generic');
            return;
        }

        $userMsg = ChatMessage::create([
            'chat_session_id' => $session->id,
            'role' => 'user',
            'content' => $msg,
            'created_at' => now(),
        ]);
        $session->increment('message_count');

        $this->messages[] = [
            'role' => 'user',
            'content' => $msg,
            'citation' => null,
            'fell_back' => false,
            'at' => now()->format('H:i'),
        ];

        $this->input = '';
        $this->thinking = true;

        $history = [];
        $window = (int) config('chatbot.history_window', 6);
        $prior = ChatMessage::where('chat_session_id', $session->id)
            ->orderByDesc('id')->limit($window)->get()->reverse();
        foreach ($prior as $m) {
            if ($m->id === $userMsg->id) continue;
            $history[] = [
                'role' => $m->role === 'bot' ? 'assistant' : $m->role,
                'content' => $m->content,
            ];
        }

        $locale = app()->getLocale();
        $service = app(LlmService::class);
        $resp = $service->reply($msg, $history, $locale, $session->id);

        ChatMessage::create([
            'chat_session_id' => $session->id,
            'role' => 'bot',
            'content' => $resp->content,
            'citation' => $resp->citation,
            'created_at' => now(),
        ]);
        $session->increment('message_count');

        $this->messages[] = [
            'role' => 'bot',
            'content' => $resp->content,
            'citation' => $resp->citation,
            'fell_back' => $resp->fellBack,
            'at' => now()->format('H:i'),
        ];

        $this->thinking = false;
    }

    private function bootSession(): void
    {
        $session = ChatSession::create([
            'user_id' => Auth::id(),
            'session_uuid' => $this->sessionUuid,
            'locale' => app()->getLocale(),
            'started_at' => now(),
            'message_count' => 0,
        ]);
        $this->sessionId = $session->id;

        cookie()->queue(cookie('jkptg_chat_uuid', $this->sessionUuid, 60 * 24 * 30));

        $this->messages[] = [
            'role' => 'bot',
            'content' => app()->getLocale() === 'en'
                ? 'Hi, I can help with JKPTG services. How can I assist you?'
                : 'Salam, saya boleh bantu mengenai perkhidmatan JKPTG. Apa yang boleh saya bantu?',
            'citation' => null,
            'fell_back' => false,
            'at' => now()->format('H:i'),
        ];
    }

    public function render()
    {
        return view('livewire.public.chatbot.bubble');
    }
}
