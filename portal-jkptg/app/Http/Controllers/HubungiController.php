<?php

namespace App\Http\Controllers;

use App\Models\Cawangan;
use App\Models\Feedback;
use Illuminate\Http\Request;

class HubungiController extends Controller
{
    public function index()
    {
        $hq = Cawangan::where('is_headquarters', true)->first();
        $branches = Cawangan::where('is_headquarters', false)->orderBy('sort')->get();
        return view('hubungi.index', compact('hq', 'branches'));
    }

    public function ibuPejabat()
    {
        $hq = Cawangan::where('is_headquarters', true)->firstOrFail();
        return view('hubungi.ibu-pejabat', compact('hq'));
    }

    public function cawangan()
    {
        $branches = Cawangan::where('is_headquarters', false)->orderBy('sort')->get();
        return view('hubungi.cawangan', compact('branches'));
    }

    public function cawanganShow(string $slug)
    {
        $branch = Cawangan::where('slug', $slug)->firstOrFail();
        return view('hubungi.cawangan-show', compact('branch'));
    }

    public function aduan()
    {
        $categories = ['perkhidmatan', 'maklumat', 'pegawai', 'sistem-portal', 'lain-lain'];
        return view('hubungi.aduan', compact('categories'));
    }

    public function aduanStore(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:120',
            'email' => 'required|email|max:160',
            'phone' => 'nullable|string|max:32',
            'category' => 'required|string|in:perkhidmatan,maklumat,pegawai,sistem-portal,lain-lain',
            'subject' => 'required|string|max:200',
            'message' => 'required|string|max:4000',
        ]);

        $data['reference_number'] = Feedback::generateReferenceNumber();
        $data['ip_address'] = $request->ip();

        $feedback = Feedback::create($data);

        return redirect()
            ->route('hubungi.aduan')
            ->with('aduan_reference', $feedback->reference_number);
    }
}
