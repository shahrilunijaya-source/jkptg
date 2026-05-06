<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\Page;
use App\Models\Service;

class PageController extends Controller
{
    private const KORPORAT_SLUGS = [
        'mengenai-jkptg', 'visi-misi', 'piagam-pelanggan',
        'carta-organisasi', 'kerjaya', 'penerbitan',
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
}
