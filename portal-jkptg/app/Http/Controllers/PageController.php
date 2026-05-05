<?php

namespace App\Http\Controllers;

use App\Models\Page;

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
}
