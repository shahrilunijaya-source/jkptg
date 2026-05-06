<?php

use App\Http\Controllers\BorangController;
use App\Http\Controllers\HubungiController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PanduanController;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SeoController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('home'))->name('home');

Route::get('/states', fn () => view('states'))->name('states');

Route::get('/untuk/{persona}', [PersonaController::class, 'show'])
    ->where('persona', 'orang-awam|kementerian-jabatan|warga-jkptg')
    ->name('persona.show');

Route::get('/perkhidmatan', [ServiceController::class, 'index'])->name('service.index');
Route::get('/perkhidmatan/{slug}', [ServiceController::class, 'show'])->name('service.show');

Route::get('/panduan', [PanduanController::class, 'index'])->name('panduan.index');
Route::get('/panduan/borang', [BorangController::class, 'index'])->name('borang.index');

Route::get('/korporat', [PageController::class, 'korporat'])->name('korporat.index');
Route::get('/sumber', [PageController::class, 'sumber'])->name('sumber.index');
Route::get('/halaman/{slug}', [PageController::class, 'show'])->name('page.show');

Route::get('/hubungi', [HubungiController::class, 'index'])->name('hubungi.index');
Route::get('/hubungi/ibu-pejabat', [HubungiController::class, 'ibuPejabat'])->name('hubungi.ibu-pejabat');
Route::get('/hubungi/cawangan', [HubungiController::class, 'cawangan'])->name('hubungi.cawangan');
Route::get('/hubungi/cawangan/{slug}', [HubungiController::class, 'cawanganShow'])->name('hubungi.cawangan.show');
Route::get('/hubungi/aduan', [HubungiController::class, 'aduan'])->name('hubungi.aduan');
Route::post('/hubungi/aduan', [HubungiController::class, 'aduanStore'])->name('hubungi.aduan.store');

Route::get('/soalan-lazim', [PageController::class, 'soalanLazim'])->name('faq.index');
Route::get('/peta-laman', [PageController::class, 'petaLaman'])->name('peta-laman');

// PPPA mandatory pages (rule 3.1)
Route::get('/hak-cipta', fn () => app(PageController::class)->staticPage('hak-cipta'))->name('hak-cipta');
Route::get('/dasar-web', fn () => app(PageController::class)->staticPage('dasar-web'))->name('dasar-web');
Route::get('/panduan-pengguna', fn () => app(PageController::class)->staticPage('panduan-pengguna'))->name('panduan-pengguna');

Route::get('/cari', [SearchController::class, 'index'])->name('search.index');

Route::get('/locale/{locale}', LocaleController::class)
    ->where('locale', 'ms|en')
    ->name('locale.switch');

// SEO + SPLaSK
Route::get('/sitemap.xml', [SeoController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [SeoController::class, 'robots'])->name('robots');
Route::get('/.well-known/security.txt', [SeoController::class, 'securityTxt'])->name('security.txt');
Route::get('/humans.txt', [SeoController::class, 'humansTxt'])->name('humans.txt');

// Legacy URL redirects (common old JKPTG paths -> new slugs)
$legacy = [
    '/index.php' => '/',
    '/index.html' => '/',
    '/v2' => '/',
    '/v2/' => '/',
    '/ms' => '/',
    '/en' => '/locale/en',
    '/my' => '/',
    '/services' => '/perkhidmatan',
    '/service' => '/perkhidmatan',
    '/forms' => '/panduan/borang',
    '/form' => '/panduan/borang',
    '/borang' => '/panduan/borang',
    '/contact' => '/hubungi',
    '/about' => '/korporat',
    '/about-us' => '/korporat',
    '/corporate' => '/korporat',
    '/news' => '/sumber',
    '/announcement' => '/sumber',
    '/tender' => '/sumber',
    '/tenders' => '/sumber',
    '/faq' => '/soalan-lazim',
    '/help' => '/cari',
    '/search' => '/cari',
    '/sitemap' => '/peta-laman',
    // PPPA mandatory aliases
    '/penafian' => '/halaman/disclaimer',
    '/dasar-privasi' => '/halaman/polisi-privasi',
    '/dasar-keselamatan' => '/halaman/polisi-keselamatan',
];
foreach ($legacy as $from => $to) {
    Route::redirect($from, $to, 301);
}
