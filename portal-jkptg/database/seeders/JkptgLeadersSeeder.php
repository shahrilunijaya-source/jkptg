<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Seeds Pengurusan Tertinggi (top management) pages with photos + bios
 * scraped from www.jkptg.gov.my (May 2026).
 */
class JkptgLeadersSeeder extends Seeder
{
    public function run(): void
    {
        $jsonPath = database_path('seeders/data/jkptg-leaders.json');
        if (! file_exists($jsonPath)) {
            $this->command->warn("Leaders JSON not found at {$jsonPath}. Skipping.");
            return;
        }

        $raw = file_get_contents($jsonPath);
        if (substr($raw, 0, 3) === "\xEF\xBB\xBF") {
            $raw = substr($raw, 3);
        }
        $data = json_decode($raw, true);
        if (! is_array($data)) {
            $this->command->warn('Invalid leaders JSON.');
            return;
        }

        // slug → photo file mapping (from scrape results)
        $photos = [
            'ketua-pengarah' => 'DATO_HJ_MOHD_FAUZI_crop.png',
            'timbalan-ketua-pengarah-sektor-kemajuan-pengurusan-perundangan-skpp' => 'asraf.png',
            'timbalan-ketua-pengarah-sektor-penyelarasan-operasi-spo' => 'EN-ASHRAF-TKP.png',
            'ketua-pegawai-maklumat-cio' => 'asraf.png',
        ];

        $titleEn = [
            'ketua-pengarah' => 'Director-General',
            'timbalan-ketua-pengarah-sektor-kemajuan-pengurusan-perundangan-skpp' => 'Deputy Director-General (SKPP — Management & Legal Affairs)',
            'timbalan-ketua-pengarah-sektor-penyelarasan-operasi-spo' => 'Deputy Director-General (SPO — Operations & Coordination)',
            'ketua-pegawai-maklumat-cio' => 'Chief Digital Officer / Chief Information Officer',
        ];

        $sortOrder = [
            'ketua-pengarah' => 50,
            'timbalan-ketua-pengarah-sektor-kemajuan-pengurusan-perundangan-skpp' => 60,
            'timbalan-ketua-pengarah-sektor-penyelarasan-operasi-spo' => 70,
            'ketua-pegawai-maklumat-cio' => 80,
        ];

        $count = 0;
        foreach ($data as $slug => $entry) {
            if (! isset($entry['title'], $entry['name'])) {
                continue;
            }

            $photoFile = $photos[$slug] ?? null;
            $photoUrl = $photoFile ? "/images/leaders/{$photoFile}" : null;
            $bodyMs = $this->renderBody($entry['name'], $entry['title'], $entry['bio'] ?? '', $photoUrl);
            $bodyEn = $this->renderBody($entry['name'], $titleEn[$slug] ?? $entry['title'], '<em>Detailed biography available in Bahasa Melayu version.</em>', $photoUrl);

            Page::updateOrCreate(['slug' => $slug], [
                'title' => [
                    'ms' => $entry['title'],
                    'en' => $titleEn[$slug] ?? $entry['title'],
                ],
                'body' => [
                    'ms' => $bodyMs,
                    'en' => $bodyEn,
                ],
                'meta_title' => [
                    'ms' => $entry['title'] . ' | JKPTG',
                    'en' => ($titleEn[$slug] ?? $entry['title']) . ' | JKPTG',
                ],
                'meta_description' => [
                    'ms' => Str::limit($entry['name'] . ' — ' . strip_tags($entry['bio'] ?? ''), 160),
                    'en' => Str::limit(($titleEn[$slug] ?? $entry['title']) . ' — ' . $entry['name'], 160),
                ],
                'sort' => $sortOrder[$slug] ?? 90,
                'published' => true,
            ]);
            $count++;
        }

        $this->command->info("JkptgLeadersSeeder: upserted {$count} leader pages with photos.");
    }

    private function renderBody(string $name, string $title, string $bio, ?string $photoUrl): string
    {
        $html = '';
        if ($photoUrl) {
            $html .= '<figure class="float-md-right text-center mb-6 md:ml-6" style="max-width:280px;">';
            $html .= '<img src="' . e($photoUrl) . '" alt="' . e($name) . '" class="rounded-lg shadow-md w-full" loading="lazy">';
            $html .= '<figcaption class="text-sm text-gray-600 mt-2 italic">' . e($name) . '</figcaption>';
            $html .= '</figure>';
        }
        $html .= '<h3>' . e($title) . '</h3>';
        $html .= '<p class="text-lg font-semibold text-primary">' . e($name) . '</p>';

        // Bio: paragraphs separated by blank lines OR list-style lines starting with year
        $lines = preg_split('/\r?\n/', trim($bio));
        $para = [];
        $flush = function () use (&$para, &$html) {
            if (! empty($para)) {
                $html .= '<p>' . implode(' ', $para) . '</p>';
                $para = [];
            }
        };
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '') {
                $flush();
                continue;
            }
            if (in_array($line, ['Profil Peribadi', 'Kelulusan Akademik', 'Pengalaman Kerja', 'Anugerah', 'Aktiviti & Sumbangan Lain'], true)) {
                $flush();
                $html .= '<h4 class="font-semibold mt-4">' . e($line) . '</h4>';
                continue;
            }
            $para[] = $line;
        }
        $flush();

        $html .= '<p class="text-xs text-gray-500 mt-6 clear-both"><em>Sumber: www.jkptg.gov.my (Mei 2026). Foto dan kandungan dipetik untuk tujuan demonstrasi prototaip.</em></p>';
        return $html;
    }
}
