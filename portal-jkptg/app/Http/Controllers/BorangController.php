<?php

namespace App\Http\Controllers;

use App\Models\Form;
use Illuminate\Http\Request;

class BorangController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->input('q', ''));
        $cat = $request->input('cat');

        $query = Form::where('active', true);

        if ($q !== '') {
            $like = '%' . $q . '%';
            $query->where(function ($w) use ($like) {
                $w->where('slug', 'like', $like)
                    ->orWhereRaw("JSON_EXTRACT(name, '$.ms') LIKE ?", [$like])
                    ->orWhereRaw("JSON_EXTRACT(name, '$.en') LIKE ?", [$like]);
            });
        }

        if ($cat) {
            $query->where('category', $cat);
        }

        $forms = $query->orderBy('name')->get();
        $categories = Form::where('active', true)->distinct()->pluck('category')->sort()->values();

        return view('panduan.borang', compact('forms', 'categories', 'q', 'cat'));
    }
}
