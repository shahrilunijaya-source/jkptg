<?php

namespace App\Http\Controllers;

use App\Models\Cawangan;

class HubungiController extends Controller
{
    public function index()
    {
        $hq = Cawangan::where('is_headquarters', true)->first();
        $branches = Cawangan::where('is_headquarters', false)->orderBy('sort')->get();
        return view('hubungi.index', compact('hq', 'branches'));
    }
}
