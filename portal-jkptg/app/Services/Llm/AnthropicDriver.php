<?php

namespace App\Services\Llm;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;

class AnthropicDriver implements LlmDriver
{
    public function name(): string
    {
        return 'anthropic';
    }

    public function chat(string $userMessage, array $history, string $locale): LlmResponse
    {
        $apiKey = config('chatbot.anthropic.api_key');
        if (empty($apiKey)) {
            throw new InvalidResponseError('ANTHROPIC_API_KEY not set');
        }

        $model = config('chatbot.anthropic.model');
        $maxTokens = (int) config('chatbot.anthropic.max_tokens', 1024);
        $timeout = (int) config('chatbot.anthropic.timeout', 30);

        $messages = [];
        foreach ($history as $h) {
            if (in_array($h['role'] ?? null, ['user', 'assistant'], true) && !empty($h['content'])) {
                $messages[] = ['role' => $h['role'], 'content' => $h['content']];
            }
        }
        $messages[] = ['role' => 'user', 'content' => $userMessage];

        $system = config('chatbot.system_prompt.' . $locale, config('chatbot.system_prompt.ms'));

        $start = microtime(true);
        try {
            $resp = Http::timeout($timeout)
                ->withHeaders([
                    'x-api-key' => $apiKey,
                    'anthropic-version' => '2023-06-01',
                    'content-type' => 'application/json',
                ])
                ->post('https://api.anthropic.com/v1/messages', [
                    'model' => $model,
                    'max_tokens' => $maxTokens,
                    'system' => $system,
                    'messages' => $messages,
                ]);
        } catch (ConnectionException $e) {
            throw new TimeoutError('Anthropic timeout: ' . $e->getMessage(), 0, $e);
        } catch (\Throwable $e) {
            throw new InvalidResponseError('Anthropic error: ' . $e->getMessage(), 0, $e);
        }

        $latency = (int) round((microtime(true) - $start) * 1000);

        if ($resp->status() === 429) {
            throw new RateLimitError('Anthropic 429');
        }
        if (!$resp->successful()) {
            throw new InvalidResponseError('Anthropic ' . $resp->status() . ': ' . $resp->body());
        }

        $data = $resp->json();
        $content = '';
        foreach (($data['content'] ?? []) as $block) {
            if (($block['type'] ?? null) === 'text') {
                $content .= $block['text'];
            }
        }
        $content = trim($content);
        if ($content === '') {
            throw new InvalidResponseError('Anthropic empty response');
        }

        $promptTok = (int) ($data['usage']['input_tokens'] ?? 0);
        $completionTok = (int) ($data['usage']['output_tokens'] ?? 0);

        $usdIn = config('chatbot.anthropic.usd_per_1k_input');
        $usdOut = config('chatbot.anthropic.usd_per_1k_output');
        $rate = config('chatbot.anthropic.usd_to_rm');

        $costUsd = ($promptTok / 1000) * $usdIn + ($completionTok / 1000) * $usdOut;
        $costRm = $costUsd * $rate;

        return new LlmResponse(
            content: $content,
            citation: null,
            promptTokens: $promptTok,
            completionTokens: $completionTok,
            costUsd: round($costUsd, 6),
            costRm: round($costRm, 4),
            latencyMs: $latency,
            driver: 'anthropic',
            model: $model,
        );
    }
}
