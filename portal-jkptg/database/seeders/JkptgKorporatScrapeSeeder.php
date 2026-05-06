<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Seeds korporat pages with content scraped from www.jkptg.gov.my (May 2026).
 * Content sourced live; English translations are paraphrased summaries
 * since official EN content was not published. Marked as such in body.
 */
class JkptgKorporatScrapeSeeder extends Seeder
{
    public function run(): void
    {
        $jsonPath = database_path('seeders/data/jkptg-korporat.json');
        if (! file_exists($jsonPath)) {
            $this->command->warn("Scrape JSON not found at {$jsonPath}. Skipping.");
            return;
        }

        $raw = file_get_contents($jsonPath);
        // Strip UTF-8 BOM if present (PowerShell Out-File adds one in PS5)
        if (substr($raw, 0, 3) === "\xEF\xBB\xBF") {
            $raw = substr($raw, 3);
        }
        $data = json_decode($raw, true);
        if (! is_array($data)) {
            $this->command->warn('Invalid scrape JSON. Skipping.');
            return;
        }

        // English summaries (paraphrased — JKPTG official site is BM-only)
        $enSummary = [
            'latar-belakang' => 'JKPTG is a department under the Ministry of Energy, Water, Land and Natural Resources (now KASA). Established in 1957, it has been transferred across several ministries.',
            'visi-misi-objektif' => 'Vision: Leader in federal land management. Mission: Manage federal land with integrity and excellence. Objectives: ensure systematic land administration, deliver quality services, and uphold land law compliance.',
            'fungsi-jabatan' => 'JKPTG drives national land administration: exercises Director-General powers under Section 8 of the National Land Code 1965, manages federal land titles, oversees land acquisition, and administers small estate distribution.',
            'perutusan-ketua-pengarah' => 'Message from the Director-General — leadership statement on JKPTG strategic priorities for federal land governance.',
            'bahagian-khidmat-pengurusan-bkp' => 'Management Services Division (BKP) — handles HR, finance, administration, and corporate support.',
            'bahagian-dasar-dan-konsultasi-bd-k' => 'Policy and Consultation Division (BD&K) — drafts land-administration policy, consultative reviews, and inter-agency coordination.',
            'bahagian-standard-dan-inspektorat-bsi' => 'Standards and Inspectorate Division (BSI) — sets service standards, conducts inspections, and audits compliance.',
            'bahagian-pengurusan-dan-perundangan-tanah-bppt' => 'Land Management and Legislation Division (BPPT) — drafts and reviews land legislation; advises on legal matters.',
            'bahagian-harta-tanah-persekutuan-bhtp' => 'Federal Land Management Division (BPTP) — manages federal land assets including leases, alienations, and reservations.',
            'bahagian-pengambilan-tanah-bpt' => 'Land Acquisition and Special Projects Division (BPTPK) — administers land acquisition under Act 486 and special federal projects.',
            'bahagian-pembahagian-pusaka-bpp' => 'Estate Distribution Division (BPP) — handles small-estate distribution under the Small Estates (Distribution) Act 1955.',
            'bahagian-hakmilik-strata-dan-stratum-bhss' => 'Strata Title and Stratum Division (BHSS) — administers strata title under Act 318 and stratum (subterranean) titles.',
            'bahagian-pengurusan-ict-pentadbiran-tanah-bpict-pt' => 'Digitalisation and Land Administration Division (BDPT) — drives ICT modernisation including the e-Tanah and MyLAND systems.',
            'unit-integriti' => 'Integrity Unit — promotes integrity, prevents misconduct, and handles disciplinary matters.',
            'unit-undang-undang' => 'Legal Unit — provides legal advice, drafts agreements, and represents JKPTG in legal proceedings.',
        ];

        $titleEn = [
            'latar-belakang' => 'Background',
            'visi-misi-objektif' => 'Vision, Mission & Objectives',
            'fungsi-jabatan' => 'Department Functions',
            'perutusan-ketua-pengarah' => 'Director-General\'s Message',
            'bahagian-khidmat-pengurusan-bkp' => 'Management Services Division (BKP)',
            'bahagian-dasar-dan-konsultasi-bd-k' => 'Policy and Consultation Division (BD&K)',
            'bahagian-standard-dan-inspektorat-bsi' => 'Standards and Inspectorate Division (BSI)',
            'bahagian-pengurusan-dan-perundangan-tanah-bppt' => 'Land Management and Legislation Division (BPPT)',
            'bahagian-harta-tanah-persekutuan-bhtp' => 'Federal Land Management Division (BPTP)',
            'bahagian-pengambilan-tanah-bpt' => 'Land Acquisition and Special Projects Division (BPTPK)',
            'bahagian-pembahagian-pusaka-bpp' => 'Estate Distribution Division (BPP)',
            'bahagian-hakmilik-strata-dan-stratum-bhss' => 'Strata Title and Stratum Division (BHSS)',
            'bahagian-pengurusan-ict-pentadbiran-tanah-bpict-pt' => 'Digitalisation and Land Administration Division (BDPT)',
            'unit-integriti' => 'Integrity Unit',
            'unit-undang-undang' => 'Legal Unit',
        ];

        // Sort order — main korporat first, then bahagian, then unit
        $sortOrder = [
            'perutusan-ketua-pengarah' => 100,
            'latar-belakang' => 110,
            'visi-misi-objektif' => 120,
            'fungsi-jabatan' => 130,
            'bahagian-khidmat-pengurusan-bkp' => 200,
            'bahagian-dasar-dan-konsultasi-bd-k' => 210,
            'bahagian-standard-dan-inspektorat-bsi' => 220,
            'bahagian-pengurusan-dan-perundangan-tanah-bppt' => 230,
            'bahagian-harta-tanah-persekutuan-bhtp' => 240,
            'bahagian-pengambilan-tanah-bpt' => 250,
            'bahagian-pembahagian-pusaka-bpp' => 260,
            'bahagian-hakmilik-strata-dan-stratum-bhss' => 270,
            'bahagian-pengurusan-ict-pentadbiran-tanah-bpict-pt' => 280,
            'unit-integriti' => 300,
            'unit-undang-undang' => 310,
        ];

        $count = 0;
        foreach ($data as $slug => $entry) {
            if (! isset($entry['title'], $entry['body'])) {
                continue;
            }

            $titleMs = $entry['title'];
            $bodyMs = $this->formatBody($entry['body']);
            $bodyEnText = $enSummary[$slug] ?? Str::limit(strip_tags($bodyMs), 200);

            Page::updateOrCreate(['slug' => $slug], [
                'title' => [
                    'ms' => $titleMs,
                    'en' => $titleEn[$slug] ?? $titleMs,
                ],
                'body' => [
                    'ms' => $bodyMs,
                    'en' => '<p>' . e($bodyEnText) . '</p><p><em>EN translation paraphrased; refer to BM version for authoritative content.</em></p>',
                ],
                'meta_title' => [
                    'ms' => "{$titleMs} | JKPTG",
                    'en' => ($titleEn[$slug] ?? $titleMs) . ' | JKPTG',
                ],
                'meta_description' => [
                    'ms' => Str::limit(strip_tags($bodyMs), 160),
                    'en' => Str::limit($bodyEnText, 160),
                ],
                'sort' => $sortOrder[$slug] ?? 500,
                'published' => true,
            ]);
            $count++;
        }

        $this->command->info("JkptgKorporatScrapeSeeder: upserted {$count} pages from scrape.");
    }

    private function formatBody(string $raw): string
    {
        // Convert plain-text scrape into safe HTML.
        // Lines starting with "## " become h3; "- " become li; blanks separate paragraphs.
        $lines = preg_split('/\r?\n/', trim($raw));
        $html = [];
        $inList = false;
        $para = [];

        $flushPara = function () use (&$para, &$html) {
            if (! empty($para)) {
                $text = e(implode(' ', $para));
                $html[] = "<p>{$text}</p>";
                $para = [];
            }
        };

        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '') {
                $flushPara();
                if ($inList) {
                    $html[] = '</ul>';
                    $inList = false;
                }
                continue;
            }
            if (Str::startsWith($line, '## ')) {
                $flushPara();
                if ($inList) {
                    $html[] = '</ul>';
                    $inList = false;
                }
                $html[] = '<h3>' . e(substr($line, 3)) . '</h3>';
            } elseif (Str::startsWith($line, '- ')) {
                $flushPara();
                if (! $inList) {
                    $html[] = '<ul>';
                    $inList = true;
                }
                $html[] = '<li>' . e(substr($line, 2)) . '</li>';
            } else {
                if ($inList) {
                    $html[] = '</ul>';
                    $inList = false;
                }
                $para[] = $line;
            }
        }
        $flushPara();
        if ($inList) {
            $html[] = '</ul>';
        }

        $body = implode("\n", $html);
        $body .= "\n<p class=\"text-xs text-gray-500 mt-6\"><em>Sumber: www.jkptg.gov.my (Mei 2026). Kandungan dipetik untuk tujuan demonstrasi prototaip.</em></p>";
        return $body;
    }
}
