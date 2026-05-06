<?php

namespace App\Http\Controllers;

use App\Models\ChatbotKnowledge;
use App\Models\Faq;
use App\Models\Form;
use App\Models\News;
use App\Models\Page;
use App\Models\Service;
use App\Models\Tender;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $type = $request->query('type', 'all');

        $results = [
            'pages' => collect(), 'services' => collect(), 'news' => collect(),
            'tenders' => collect(), 'forms' => collect(), 'faqs' => collect(), 'kb' => collect(),
        ];
        $totals = ['pages' => 0, 'services' => 0, 'news' => 0, 'tenders' => 0, 'forms' => 0, 'faqs' => 0, 'kb' => 0];
        $grandTotal = 0;

        if (mb_strlen($q) >= 2) {
            $limit = 10;

            if ($type === 'all' || $type === 'pages') {
                $r = Page::search($q)->get();
                $results['pages'] = $r->take($limit); $totals['pages'] = $r->count(); $grandTotal += $r->count();
            }
            if ($type === 'all' || $type === 'services') {
                $r = Service::search($q)->get();
                $results['services'] = $r->take($limit); $totals['services'] = $r->count(); $grandTotal += $r->count();
            }
            if ($type === 'all' || $type === 'news') {
                $r = News::search($q)->get()->sortByDesc('published_at');
                $results['news'] = $r->take($limit); $totals['news'] = $r->count(); $grandTotal += $r->count();
            }
            if ($type === 'all' || $type === 'tenders') {
                $r = Tender::search($q)->get();
                $results['tenders'] = $r->take($limit); $totals['tenders'] = $r->count(); $grandTotal += $r->count();
            }
            if ($type === 'all' || $type === 'forms') {
                $r = Form::search($q)->get();
                $results['forms'] = $r->take($limit); $totals['forms'] = $r->count(); $grandTotal += $r->count();
            }
            if ($type === 'all' || $type === 'faqs') {
                $r = Faq::search($q)->get();
                $results['faqs'] = $r->take($limit); $totals['faqs'] = $r->count(); $grandTotal += $r->count();
            }
            if ($type === 'all' || $type === 'kb') {
                $r = ChatbotKnowledge::search($q)->get();
                $results['kb'] = $r->take($limit); $totals['kb'] = $r->count(); $grandTotal += $r->count();
            }
        }

        return view('search.index', compact('q', 'type', 'results', 'totals', 'grandTotal'));
    }
}
