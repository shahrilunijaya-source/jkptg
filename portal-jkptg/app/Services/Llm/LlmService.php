<?php

namespace App\Services\Llm;

use App\Models\ChatbotSetting;
use App\Models\LlmApiLog;
use Illuminate\Support\Facades\Log;

class LlmService
{
    public function __construct(
        private readonly AnthropicDriver $anthropic,
        private readonly CannedDriver $canned,
    ) {}

    public function reply(string $userMessage, array $history, string $locale, ?int $sessionId = null): LlmResponse
    {
        $userMessage = Sanitizer::clean($userMessage);
        if ($userMessage === '') {
            return new LlmResponse(
                content: $locale === 'en' ? 'Please type a question.' : 'Sila taipkan soalan.',
                driver: 'canned',
                model: 'empty-input',
            );
        }

        $settings = ChatbotSetting::current();
        $configured = config('chatbot.driver', 'canned');

        if ($settings->kill_switch_active || $settings->isOverBudget() || $configured === 'canned') {
            return $this->runCanned($userMessage, $history, $locale, $sessionId, fellBack: false);
        }

        try {
            $resp = $this->anthropic->chat($userMessage, $history, $locale);
            $this->accrueCost($settings, $resp);
            $this->log($resp, $sessionId, status: 'ok');
            return $resp;
        } catch (RateLimitError | TimeoutError | InvalidResponseError $e) {
            Log::warning('LLM fallback to canned', ['driver' => 'anthropic', 'reason' => $e->getMessage()]);
            $this->log(new LlmResponse(
                content: '',
                driver: 'anthropic',
                model: config('chatbot.anthropic.model'),
            ), $sessionId, status: 'error', errorMessage: $e->getMessage());
            return $this->runCanned($userMessage, $history, $locale, $sessionId, fellBack: true, reason: $e->getMessage());
        }
    }

    private function runCanned(string $msg, array $history, string $locale, ?int $sessionId, bool $fellBack, ?string $reason = null): LlmResponse
    {
        $resp = $this->canned->chat($msg, $history, $locale);
        $this->log($resp, $sessionId, status: 'ok');
        if ($fellBack) {
            return new LlmResponse(
                content: $resp->content,
                citation: $resp->citation,
                latencyMs: $resp->latencyMs,
                driver: 'canned',
                model: $resp->model,
                fellBack: true,
                fallbackReason: $reason,
            );
        }
        return $resp;
    }

    private function accrueCost(ChatbotSetting $s, LlmResponse $resp): void
    {
        if ($resp->costRm <= 0) {
            return;
        }
        $newTotal = (float) $s->cost_month_to_date_rm + $resp->costRm;
        $s->cost_month_to_date_rm = round($newTotal, 4);

        if ($s->cost_month_to_date_rm >= $s->cost_cap_rm) {
            $s->kill_switch_active = true;
            Log::warning('LLM cost cap hit. Kill-switch ON.', [
                'mtd' => $s->cost_month_to_date_rm, 'cap' => $s->cost_cap_rm,
            ]);
        } elseif ($s->isOverAlertThreshold()) {
            Log::notice('LLM cost over alert threshold', [
                'mtd' => $s->cost_month_to_date_rm,
                'cap' => $s->cost_cap_rm,
                'pct' => round($s->cost_month_to_date_rm / $s->cost_cap_rm * 100, 1),
            ]);
        }
        $s->save();
    }

    private function log(LlmResponse $r, ?int $sessionId, string $status, ?string $errorMessage = null): void
    {
        LlmApiLog::create([
            'driver' => $r->driver,
            'model' => $r->model,
            'prompt_tokens' => $r->promptTokens,
            'completion_tokens' => $r->completionTokens,
            'cost_usd' => $r->costUsd,
            'cost_rm' => $r->costRm,
            'latency_ms' => $r->latencyMs,
            'status' => $status,
            'error_message' => $errorMessage,
            'chat_session_id' => $sessionId,
            'created_at' => now(),
        ]);
    }
}
