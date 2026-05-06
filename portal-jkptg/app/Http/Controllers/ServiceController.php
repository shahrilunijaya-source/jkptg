<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\Form;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Service::where('active', true)->orderBy('sort');
        $activeCategory = $request->string('kategori')->toString() ?: null;
        if ($activeCategory) {
            $query->where('category', $activeCategory);
        }
        $services = $query->get();
        $categories = Service::where('active', true)->orderBy('sort')->get()
            ->pluck('category')->unique()->filter()->values();
        return view('perkhidmatan.index', compact('services', 'categories', 'activeCategory'));
    }

    public function show(string $slug)
    {
        $service = Service::where('slug', $slug)->where('active', true)->firstOrFail();

        $slugRoot = explode('-', $service->slug)[0];

        $relatedForms = Form::where('active', true)
            ->where(function ($q) use ($service, $slugRoot) {
                $q->where('category', $service->category)
                    ->orWhere('category', $slugRoot)
                    ->orWhere('slug', 'like', $slugRoot . '%');
            })
            ->orderBy('name')
            ->get();

        $relatedFaqs = Faq::where('active', true)
            ->whereIn('category', [$service->category, $slugRoot, 'umum'])
            ->orderBy('sort')
            ->get();

        return view('perkhidmatan.show', compact('service', 'relatedForms', 'relatedFaqs'));
    }

    public function sop(string $slug)
    {
        $service = Service::where('slug', $slug)->where('active', true)->firstOrFail();
        return view('perkhidmatan.sop', compact('service'));
    }

    public function cartaAlir(string $slug)
    {
        $service = Service::where('slug', $slug)->where('active', true)->firstOrFail();
        return view('perkhidmatan.carta-alir', compact('service'));
    }

    public function sopPdf(string $slug)
    {
        $service = Service::where('slug', $slug)->where('active', true)->firstOrFail();
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.sop', compact('service'));
        return $pdf->download("SOP-{$service->slug}.pdf");
    }

    public function cartaAlirPdf(string $slug)
    {
        $service = Service::where('slug', $slug)->where('active', true)->firstOrFail();
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.carta-alir', compact('service'));
        return $pdf->download("CartaAlir-{$service->slug}.pdf");
    }
}
