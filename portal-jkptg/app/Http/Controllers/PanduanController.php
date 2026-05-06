<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\Service;

class PanduanController extends Controller
{
    private const ACTS = [
        'akta-pengambilan-tanah-1960' => ['title' => 'Akta Pengambilan Tanah 1960 (Akta 486)', 'year' => 1960, 'topic' => 'pengambilan'],
        'akta-tanah-persekutuan-1957' => ['title' => 'Akta Tanah Persekutuan 1957 (Akta 380)', 'year' => 1957, 'topic' => 'persekutuan'],
        'akta-pajakan-tanah-persekutuan-1955' => ['title' => 'Akta Pajakan Tanah Persekutuan 1955 (Akta 154)', 'year' => 1955, 'topic' => 'pajakan'],
        'akta-hartanah-1965' => ['title' => 'Akta Hartanah 1965', 'year' => 1965, 'topic' => 'hartanah'],
        'akta-pemegang-amanah-bumiputera-1976' => ['title' => 'Akta Pemegang Amanah Bumiputera 1976', 'year' => 1976, 'topic' => 'amanah'],
        'akta-hakmilik-strata-1985' => ['title' => 'Akta Hakmilik Strata 1985 (Akta 318)', 'year' => 1985, 'topic' => 'strata'],
        'akta-pelupusan-1957' => ['title' => 'Akta Pelupusan 1957', 'year' => 1957, 'topic' => 'pelupusan'],
        'kanun-tanah-negara-1965' => ['title' => 'Kanun Tanah Negara 1965 (Akta 56)', 'year' => 1965, 'topic' => 'kanun'],
    ];

    public function index()
    {
        $borangCount = Form::where('active', true)->count();
        $services = Service::where('active', true)->orderBy('sort')->get();
        return view('panduan.index', compact('borangCount', 'services'));
    }

    public function akta()
    {
        $acts = self::ACTS;
        return view('panduan.akta-index', compact('acts'));
    }

    public function aktaShow(string $slug)
    {
        abort_unless(isset(self::ACTS[$slug]), 404);
        $act = self::ACTS[$slug];
        $act['slug'] = $slug;
        return view('panduan.akta-show', compact('act'));
    }

    public function aktaPdf(string $slug)
    {
        abort_unless(isset(self::ACTS[$slug]), 404);
        $act = self::ACTS[$slug];
        $act['slug'] = $slug;
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.akta', compact('act'));
        return $pdf->download("Akta-{$slug}.pdf");
    }
}
