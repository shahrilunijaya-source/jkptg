<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Page;
use App\Models\Service;
use App\Models\Tender;
use Illuminate\Http\Response;

class SeoController extends Controller
{
    public function sitemap(): Response
    {
        $urls = [];
        $urls[] = ['loc' => url('/'),                       'priority' => '1.0', 'changefreq' => 'daily'];
        $urls[] = ['loc' => url('/perkhidmatan'),           'priority' => '0.9', 'changefreq' => 'weekly'];
        $urls[] = ['loc' => url('/panduan/borang'),         'priority' => '0.8', 'changefreq' => 'weekly'];
        $urls[] = ['loc' => url('/korporat'),               'priority' => '0.7', 'changefreq' => 'monthly'];
        $urls[] = ['loc' => url('/sumber'),                 'priority' => '0.7', 'changefreq' => 'monthly'];
        $urls[] = ['loc' => url('/hubungi'),                'priority' => '0.6', 'changefreq' => 'monthly'];
        $urls[] = ['loc' => url('/cari'),                   'priority' => '0.5', 'changefreq' => 'weekly'];
        foreach (['orang-awam', 'kementerian-jabatan', 'warga-jkptg'] as $p) {
            $urls[] = ['loc' => url('/untuk/' . $p),        'priority' => '0.7', 'changefreq' => 'weekly'];
        }
        foreach (Page::where('published', true)->get() as $p) {
            $urls[] = ['loc' => url('/halaman/' . $p->slug), 'lastmod' => $p->updated_at?->toAtomString(), 'priority' => '0.6'];
        }
        foreach (Service::where('active', true)->get() as $s) {
            $urls[] = ['loc' => url('/perkhidmatan/' . $s->slug), 'lastmod' => $s->updated_at?->toAtomString(), 'priority' => '0.8'];
        }
        foreach (News::whereNotNull('published_at')->where('published_at', '<=', now())->get() as $n) {
            $urls[] = ['loc' => url('/berita/' . $n->slug), 'lastmod' => ($n->updated_at ?? $n->published_at)?->toAtomString(), 'priority' => '0.5'];
        }
        foreach (Tender::whereIn('status', ['open', 'closed'])->get() as $t) {
            $urls[] = ['loc' => url('/tender/' . $t->slug), 'lastmod' => $t->updated_at?->toAtomString(), 'priority' => '0.5'];
        }

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap-0.9">' . "\n";
        foreach ($urls as $u) {
            $xml .= '  <url>' . "\n";
            $xml .= '    <loc>' . htmlspecialchars($u['loc'], ENT_XML1) . '</loc>' . "\n";
            if (!empty($u['lastmod'])) $xml .= '    <lastmod>' . $u['lastmod'] . '</lastmod>' . "\n";
            if (!empty($u['changefreq'])) $xml .= '    <changefreq>' . $u['changefreq'] . '</changefreq>' . "\n";
            if (!empty($u['priority'])) $xml .= '    <priority>' . $u['priority'] . '</priority>' . "\n";
            $xml .= '  </url>' . "\n";
        }
        $xml .= '</urlset>' . "\n";

        return response($xml, 200)
            ->header('Content-Type', 'application/xml; charset=utf-8')
            ->header('Cache-Control', 'public, max-age=3600');
    }

    public function robots(): Response
    {
        $body = <<<TXT
User-agent: *
Allow: /
Disallow: /admin
Disallow: /admin/
Disallow: /livewire/
Disallow: /storage/

Sitemap: {{SITEMAP}}
TXT;
        $body = str_replace('{{SITEMAP}}', url('/sitemap.xml'), $body);
        return response($body, 200)->header('Content-Type', 'text/plain; charset=utf-8');
    }

    public function securityTxt(): Response
    {
        $expires = now()->addYear()->toAtomString();
        $email = \App\Models\Setting::get('site.email', 'webmaster@jkptg.gov.my');
        $body = <<<TXT
Contact: mailto:{$email}
Expires: {$expires}
Preferred-Languages: ms, en
Canonical: {{CANONICAL}}
Policy: {{POLICY}}
TXT;
        $body = str_replace(['{{CANONICAL}}', '{{POLICY}}'], [url('/.well-known/security.txt'), url('/halaman/polisi-keselamatan')], $body);
        return response($body, 200)->header('Content-Type', 'text/plain; charset=utf-8');
    }

    public function humansTxt(): Response
    {
        $body = <<<TXT
/* TEAM */
Agency: Jabatan Ketua Pengarah Tanah dan Galian Persekutuan (JKPTG)
Site: https://www.jkptg.gov.my
Stack: Laravel 11 LTS, Filament v3, Livewire 3, Tailwind 3, MySQL 8
Compliance: PPPA Bil 1/2025, WCAG 2.1 AA, SPLaSK
Last Updated: {{DATE}}
TXT;
        $body = str_replace('{{DATE}}', now()->toDateString(), $body);
        return response($body, 200)->header('Content-Type', 'text/plain; charset=utf-8');
    }
}
