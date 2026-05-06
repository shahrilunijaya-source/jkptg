<?php

namespace App\Services\Llm;

interface LlmDriver
{
    /**
     * @param  array  $history  [['role'=>'user'|'assistant','content'=>string], ...]
     * @return LlmResponse
     */
    public function chat(string $userMessage, array $history, string $locale): LlmResponse;

    public function name(): string;
}
