<?php

namespace App\Services\Llm;

class LlmResponse
{
    public function __construct(
        public readonly string $content,
        public readonly ?string $citation = null,
        public readonly int $promptTokens = 0,
        public readonly int $completionTokens = 0,
        public readonly float $costUsd = 0.0,
        public readonly float $costRm = 0.0,
        public readonly int $latencyMs = 0,
        public readonly string $driver = 'canned',
        public readonly string $model = 'kb-match',
        public readonly bool $fellBack = false,
        public readonly ?string $fallbackReason = null,
    ) {}
}
