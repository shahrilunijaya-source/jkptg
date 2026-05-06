<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Page;
use App\Models\Tender;
use Illuminate\Support\Facades\Cache;

class SumberController extends Controller
{
    public function index()
    {
        return view('sumber.index');
    }

    public function galeri(?string $type = null)
    {
        $allowed = ['gambar', 'audio', 'video'];
        if ($type && !in_array($type, $allowed, true)) {
            abort(404);
        }
        $type ??= 'gambar';
        return view('sumber.galeri', compact('type', 'allowed'));
    }

    public function dataTerbuka()
    {
        $datasets = Cache::remember('sumber.datasets', 3600, function () {
            return [
                ['Pengambilan Tanah Persekutuan', 'Bilangan kes pengambilan 2020-2025', 'CSV', '142 KB'],
                ['Pelupusan Tanah Persekutuan', 'Senarai tanah dilupuskan 2024', 'CSV', '88 KB'],
                ['Pajakan Tanah', 'Daftar pajakan aktif', 'XLSX', '256 KB'],
                ['Lesen Pasir & Batu', 'Senarai pelesen aktif', 'CSV', '74 KB'],
                ['Strata Hakmilik', 'Permohonan strata 2024', 'CSV', '120 KB'],
            ];
        });
        return view('sumber.data-terbuka', compact('datasets'));
    }

    public function pelanStrategik()
    {
        $plans = [
            ['Pelan Strategik JKPTG 2021-2025', '2021', 'PDF', '4.2 MB'],
            ['Pelan Strategik JKPTG 2016-2020', '2016', 'PDF', '3.8 MB'],
            ['Pelan Strategik JKPTG 2011-2015', '2011', 'PDF', '2.9 MB'],
        ];
        return view('sumber.pelan-strategik', compact('plans'));
    }

    public function penerbitan()
    {
        $page = Page::where('slug', 'penerbitan')->where('published', true)->first();
        $news = News::where('type', 'pengumuman')
            ->whereNotNull('published_at')
            ->orderByDesc('published_at')
            ->limit(20)
            ->get();
        return view('sumber.penerbitan', compact('page', 'news'));
    }

    public function infografik()
    {
        $items = [
            ['Proses Pengambilan Tanah', 'pengambilan'],
            ['Tatacara Pusaka Bukan Islam', 'pusaka'],
            ['Pajakan Tanah Persekutuan', 'pajakan'],
            ['Permohonan Lesen Pasir', 'lesen-pasir'],
            ['Strata Hakmilik', 'strata'],
            ['Penyewaan Bangunan', 'penyewaan'],
        ];
        return view('sumber.infografik', compact('items'));
    }

    public function arkib(?string $type = null)
    {
        $allowed = ['berita', 'tender', 'pengumuman', 'laporan'];
        if ($type && !in_array($type, $allowed, true)) {
            abort(404);
        }
        $type ??= 'berita';

        $items = collect();
        if ($type === 'tender') {
            $items = Tender::orderByDesc('id')->limit(50)->get();
        } else {
            $items = News::where('type', $type === 'laporan' ? 'pengumuman' : $type)
                ->whereNotNull('published_at')
                ->orderByDesc('published_at')
                ->limit(50)
                ->get();
        }
        return view('sumber.arkib', compact('type', 'allowed', 'items'));
    }
}
