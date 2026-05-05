<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Service;
use Illuminate\Http\Request;

class PersonaController extends Controller
{
    private const PERSONAS = [
        'orang-awam' => [
            'icon' => 'users',
            'service_categories' => ['tanah', 'pajakan', 'lesen', 'strata'],
        ],
        'kementerian-jabatan' => [
            'icon' => 'building-office-2',
            'service_categories' => ['pajakan', 'tanah'],
        ],
        'warga-jkptg' => [
            'icon' => 'identification',
            'service_categories' => ['tanah', 'pajakan', 'lesen', 'strata'],
        ],
    ];

    public function show(Request $request, string $persona)
    {
        if (!isset(self::PERSONAS[$persona])) {
            abort(404);
        }

        $config = self::PERSONAS[$persona];

        $services = Service::where('active', true)
            ->whereIn('category', $config['service_categories'])
            ->orderBy('sort')
            ->limit(6)
            ->get();

        $news = News::whereNotNull('published_at')
            ->orderByDesc('published_at')
            ->limit(3)
            ->get();

        return view('persona.show', [
            'personaSlug' => $persona,
            'personaIcon' => $config['icon'],
            'services' => $services,
            'news' => $news,
        ]);
    }
}
