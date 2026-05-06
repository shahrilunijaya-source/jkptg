<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\Page;
use App\Models\Service;

class PageController extends Controller
{
    private const KORPORAT_SLUGS = [
        // Profil utama (scraped from www.jkptg.gov.my)
        'perutusan-ketua-pengarah',
        'latar-belakang',
        'visi-misi-objektif',
        'fungsi-jabatan',
        'piagam-pelanggan',
        'carta-organisasi',
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
        $section = 'korporat';
        return view('pages.show', compact('page', 'section'));
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

    public function logMasuk()
    {
        return view('auth.log-masuk');
    }
}
