<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\Page;
use App\Models\Service;

class PageController extends Controller
{
    private const LEADER_SLUGS = [
        'ketua-pengarah',
        'timbalan-ketua-pengarah-sektor-kemajuan-pengurusan-perundangan-skpp',
        'timbalan-ketua-pengarah-sektor-penyelarasan-operasi-spo',
        'ketua-pegawai-maklumat-cio',
    ];

    private const KORPORAT_SLUGS = [
        // Profil utama (scraped from www.jkptg.gov.my)
        'perutusan-ketua-pengarah',
        'latar-belakang',
        'visi-misi-objektif',
        'fungsi-jabatan',
        'piagam-pelanggan',
        'carta-organisasi',
        // Pengurusan tertinggi (4 leaders with photos)
        'ketua-pengarah',
        'timbalan-ketua-pengarah-sektor-kemajuan-pengurusan-perundangan-skpp',
        'timbalan-ketua-pengarah-sektor-penyelarasan-operasi-spo',
        'ketua-pegawai-maklumat-cio',
        // Profil bahagian (11)
        'bahagian-khidmat-pengurusan-bkp',
        'bahagian-dasar-dan-konsultasi-bd-k',
        'bahagian-standard-dan-inspektorat-bsi',
        'bahagian-pengurusan-dan-perundangan-tanah-bppt',
        'bahagian-harta-tanah-persekutuan-bhtp',
        'bahagian-pengambilan-tanah-bpt',
        'bahagian-pembahagian-pusaka-bpp',
        'bahagian-hakmilik-strata-dan-stratum-bhss',
        'bahagian-pengurusan-ict-pentadbiran-tanah-bpict-pt',
        'unit-integriti',
        'unit-undang-undang',
        // Korporat tambahan
        'kerjaya', 'penerbitan', 'mengenai-jkptg',
    ];

    public function korporat()
    {
        $pages = Page::whereIn('slug', self::KORPORAT_SLUGS)
            ->where('published', true)
            ->orderBy('sort')
            ->get();
        return view('korporat.index', compact('pages'));
    }

    public function show(string $slug)
    {
        $page = Page::where('slug', $slug)->where('published', true)->firstOrFail();
        return view('pages.show', compact('page'));
    }

    public function korporatShow(string $slug)
    {
        $page = Page::where('slug', $slug)->where('published', true)->firstOrFail();
        $bodyHtml = $page->getTranslation('body', app()->getLocale(), false) ?: '';

        // Extract photo src from <figure> or bare <img>
        preg_match('/<img[^>]+src="([^"]+)"/', $bodyHtml, $im);
        $photo = $im[1] ?? null;

        // Strip <figure> block and leading <h3> (duplicated in header)
        $body = preg_replace('/<figure[^>]*>.*?<\/figure>/is', '', $bodyHtml);
        $body = preg_replace('/<h3[^>]*>.*?<\/h3>/is', '', $body);

        // Parse <h4> sections into title => plain-text-content map
        $sections = [];
        preg_match_all('/<h4[^>]*>(.*?)<\/h4>((?:(?!<h4).)*)/is', $body, $matches, PREG_SET_ORDER);
        foreach ($matches as $match) {
            $title   = trim(strip_tags(html_entity_decode($match[1])));
            $content = trim(strip_tags(html_entity_decode($match[2])));
            if ($title) $sections[$title] = $content;
        }

        // Parse qualifications: "YYYY : Qualification text"
        $qualItems = [];
        $qualRaw = $sections['Kelulusan Akademik'] ?? '';
        foreach (preg_split('/(?=\d{4}\s*:)/', $qualRaw) as $part) {
            if (preg_match('/^(\d{4})\s*:\s*(.+)/u', trim($part), $qm)) {
                $qualItems[] = ['year' => $qm[1], 'text' => trim($qm[2])];
            }
        }

        // Parse awards: split on lines starting with award/darjah keywords or year
        $awardItems = [];
        $awardRaw = $sections['Anugerah'] ?? '';
        if ($awardRaw) {
            foreach (preg_split('/(?=(?:Anugerah|Darjah|Pingat|Bintang)\s)/u', $awardRaw) as $part) {
                $part = trim($part);
                if (strlen($part) > 5) $awardItems[] = $part;
            }
        }

        // Strip all h4 sections that are in the sidebar (qualifications, awards, etc.)
        // and all other h4 sections — they are captured in $sections; body should only have <p> prose
        $body = preg_replace('/<h4[^>]*>.*?<\/h4>\s*(?:<p[^>]*>.*?<\/p>)*/is', '', $body);
        // Remove duplicate name paragraph (text-lg font-semibold)
        $body = preg_replace('/<p[^>]*class="[^"]*font-semibold[^"]*"[^>]*>.*?<\/p>/is', '', $body);
        // Remove tiny source-note paragraph (text-xs metadata)
        $body = preg_replace('/<p[^>]*class="[^"]*text-xs[^"]*"[^>]*>.*?<\/p>/is', '', $body);
        $body = trim($body);

        return view('korporat.show', compact('page', 'photo', 'body', 'sections', 'qualItems', 'awardItems'));
    }

    public function pengurusanTertinggi()
    {
        $leaders = Page::whereIn('slug', self::LEADER_SLUGS)
            ->where('published', true)
            ->orderBy('sort')
            ->get();
        return view('korporat.pengurusan-tertinggi', compact('leaders'));
    }

    public function sumber()
    {
        return view('sumber.index');
    }

    public function soalanLazim()
    {
        $faqs = Faq::where('active', true)->orderBy('category')->orderBy('sort')->get();
        $categories = $faqs->pluck('category')->unique()->values();
        return view('pages.soalan-lazim', compact('faqs', 'categories'));
    }

    public function petaLaman()
    {
        $services = Service::where('active', true)->orderBy('sort')->get();
        $korporatPages = Page::whereIn('slug', self::KORPORAT_SLUGS)
            ->where('published', true)
            ->orderBy('sort')
            ->get();
        return view('pages.peta-laman', compact('services', 'korporatPages'));
    }

    public function staticPage(string $key)
    {
        $views = [
            'hak-cipta' => 'pages.static.hak-cipta',
            'dasar-web' => 'pages.static.dasar-web',
            'panduan-pengguna' => 'pages.static.panduan-pengguna',
        ];
        abort_unless(isset($views[$key]), 404);
        return view($views[$key]);
    }

    public function pagePdf(string $slug)
    {
        $page = Page::where('slug', $slug)->where('published', true)->firstOrFail();
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.page', compact('page'));
        return $pdf->download("{$page->slug}.pdf");
    }
}
