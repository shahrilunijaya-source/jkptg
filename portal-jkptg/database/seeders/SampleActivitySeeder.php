<?php

namespace Database\Seeders;

use App\Models\Faq;
use App\Models\News;
use App\Models\Page;
use App\Models\Service;
use App\Models\User;
use App\Models\VisitLog;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class SampleActivitySeeder extends Seeder
{
    public function run(): void
    {
        $this->seedVisitLogs();
        $this->seedActivities();
    }

    private function seedVisitLogs(): void
    {
        $pages = ['/', '/perkhidmatan', '/perkhidmatan/pengambilan-tanah', '/perkhidmatan/pusaka-bukan-islam',
                  '/panduan/borang', '/hubungi', '/korporat', '/untuk/orang-awam', '/untuk/kementerian-jabatan'];
        $countries = ['MY', 'SG', 'ID'];
        $locales = ['ms', 'en'];
        $rows = [];

        for ($d = 6; $d >= 0; $d--) {
            $base = Carbon::now()->subDays($d)->setTime(8, 0);
            $count = 30 + ($d === 0 ? 50 : 0) + rand(0, 80);
            for ($i = 0; $i < $count; $i++) {
                $rows[] = [
                    'page_path' => $pages[array_rand($pages)],
                    'ip_address' => '203.0.113.' . rand(1, 254),
                    'user_agent_hash' => substr(sha1('ua' . rand()), 0, 32),
                    'country' => $countries[array_rand($countries)],
                    'locale' => $locales[array_rand($locales)],
                    'referer' => null,
                    'user_id' => null,
                    'created_at' => $base->copy()->addMinutes(rand(0, 600))->toDateTimeString(),
                ];
            }
        }
        foreach (array_chunk($rows, 200) as $chunk) {
            VisitLog::insert($chunk);
        }
    }

    private function seedActivities(): void
    {
        $admin = User::where('email', 'admin@jkptg.demo')->first();
        $editor = User::where('email', 'editor@jkptg.demo')->first();
        if (!$admin || !$editor) return;

        $page = Page::first();
        if ($page) {
            activity('page')->causedBy($editor)->performedOn($page)->event('updated')
                ->log('Halaman dikemas kini');
        }

        $service = Service::first();
        if ($service) {
            activity('service')->causedBy($editor)->performedOn($service)->event('updated')
                ->log('Perkhidmatan dikemas kini');
        }

        $news = News::first();
        if ($news) {
            activity('news')->causedBy($editor)->performedOn($news)->event('created')
                ->log('Berita baru ditambah');
        }

        $faq = Faq::first();
        if ($faq) {
            activity('faq')->causedBy($admin)->performedOn($faq)->event('updated')
                ->log('FAQ dikemas kini');
        }

        if ($admin) {
            activity('auth')->causedBy($admin)->event('updated')
                ->log('Log masuk pentadbir');
        }
    }
}
