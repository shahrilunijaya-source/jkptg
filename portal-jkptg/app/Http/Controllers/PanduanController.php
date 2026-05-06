<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\Service;

class PanduanController extends Controller
{
    public function index()
    {
        $borangCount = Form::where('active', true)->count();
        $services = Service::where('active', true)->orderBy('sort')->get();
        return view('panduan.index', compact('borangCount', 'services'));
    }
}
