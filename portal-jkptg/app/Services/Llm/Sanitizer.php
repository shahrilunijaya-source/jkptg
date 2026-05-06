<?php

namespace App\Services\Llm;

class Sanitizer
{
    public static function clean(string $input): string
    {
        $maxLen = (int) config('chatbot.sanitizer.max_length', 2000);
        $banned = (array) config('chatbot.sanitizer.banned_tokens', []);

        $s = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $input);
        $s = trim((string) $s);

        foreach ($banned as $tok) {
            $s = str_ireplace($tok, '', $s);
        }

        if (mb_strlen($s) > $maxLen) {
            $s = mb_substr($s, 0, $maxLen);
        }
        return $s;
    }
}
