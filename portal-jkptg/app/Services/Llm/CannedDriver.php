<?php

namespace App\Services\Llm;

use App\Models\ChatbotKnowledge;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CannedDriver implements LlmDriver
{
    public function name(): string
    {
        return 'canned';
    }

    public function chat(string $userMessage, array $history, string $locale): LlmResponse
    {
        $start = microtime(true);
        $hit = $this->matchKnowledge($userMessage, $locale);
        $latency = (int) round((microtime(true) - $start) * 1000);

        if ($hit) {
            return new LlmResponse(
                content: $hit->getTranslation('answer', $locale, false) ?: $hit->getTranslation('answer', 'ms'),
                citation: $hit->source_url ?: ('KB#' . $hit->slug),
                latencyMs: $latency,
                driver: 'canned',
                model: 'kb-match',
            );
        }

        return new LlmResponse(
            content: $this->fallbackText($locale),
            citation: null,
            latencyMs: $latency,
            driver: 'canned',
            model: 'no-match',
        );
    }

    private function matchKnowledge(string $query, string $locale): ?ChatbotKnowledge
    {
        $tokens = $this->tokenize($query);
        if (empty($tokens)) {
            return null;
        }

        $candidates = ChatbotKnowledge::where('active', true)->get();

        $best = null;
        $bestScore = 0;

        foreach ($candidates as $kb) {
            $score = $this->score($kb, $tokens, $locale);
            if ($score > $bestScore) {
                $bestScore = $score;
                $best = $kb;
            }
        }

        return $bestScore >= 2 ? $best : null;
    }

    private function score(ChatbotKnowledge $kb, array $tokens, string $locale): int
    {
        $score = 0;
        $haystack = mb_strtolower(implode(' ', [
            $kb->getTranslation('question', 'ms', false) ?: '',
            $kb->getTranslation('question', 'en', false) ?: '',
            $kb->getTranslation('answer', $locale, false) ?: '',
            is_array($kb->keywords) ? implode(' ', $kb->keywords) : '',
            $kb->category ?? '',
        ]));

        foreach ($tokens as $t) {
            if (Str::contains($haystack, $t)) {
                $score += (mb_strlen($t) >= 5) ? 2 : 1;
            }
        }
        return $score;
    }

    private function tokenize(string $q): array
    {
        $stopwords = ['saya','anda','apa','apakah','bagaimana','bila','di','ke','dan','atau','yang','untuk','what','how','when','is','the','a','an','to','for','of','and','or'];
        $clean = mb_strtolower(preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $q));
        $parts = preg_split('/\s+/', trim($clean), -1, PREG_SPLIT_NO_EMPTY);
        return array_values(array_filter($parts, fn ($t) => mb_strlen($t) >= 3 && !in_array($t, $stopwords, true)));
    }

    private function fallbackText(string $locale): string
    {
        return $locale === 'en'
            ? "I couldn't find an exact match. Please try a different keyword, browse Services, or contact the nearest JKPTG branch via /hubungi."
            : "Maaf, saya tidak menemui jawapan yang tepat. Sila cuba kata kunci lain, layari Perkhidmatan, atau hubungi cawangan JKPTG terdekat di /hubungi.";
    }
}
