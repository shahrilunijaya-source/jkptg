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
use App\Http\Controllers\SumberController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('home'))->name('home');

Route::get('/states', fn () => view('states'))->name('states');

Route::get('/untuk/{persona}', [PersonaController::class, 'show'])
    ->where('persona', 'orang-awam|kementerian-jabatan|warga-jkptg')
    ->name('persona.show');

Route::get('/perkhidmatan', [ServiceController::class, 'index'])->name('service.index');
Route::get('/perkhidmatan/{slug}', [ServiceController::class, 'show'])
    ->where('slug', '[a-z0-9-]+')
    ->name('service.show');
Route::get('/perkhidmatan/{slug}/sop', [ServiceController::class, 'sop'])
    ->where('slug', '[a-z0-9-]+')
    ->name('service.sop');
Route::get('/perkhidmatan/{slug}/sop/pdf', [ServiceController::class, 'sopPdf'])
    ->where('slug', '[a-z0-9-]+')
    ->name('service.sop.pdf');
Route::get('/perkhidmatan/{slug}/carta-alir', [ServiceController::class, 'cartaAlir'])
    ->where('slug', '[a-z0-9-]+')
    ->name('service.carta-alir');
Route::get('/perkhidmatan/{slug}/carta-alir/pdf', [ServiceController::class, 'cartaAlirPdf'])
    ->where('slug', '[a-z0-9-]+')
    ->name('service.carta-alir.pdf');

Route::get('/panduan', [PanduanController::class, 'index'])->name('panduan.index');
Route::get('/panduan/borang', [BorangController::class, 'index'])->name('borang.index');
Route::get('/panduan/akta', [PanduanController::class, 'akta'])->name('panduan.akta');
Route::get('/panduan/akta/{slug}', [PanduanController::class, 'aktaShow'])
    ->where('slug', '[a-z0-9-]+')
    ->name('panduan.akta.show');
Route::get('/panduan/akta/{slug}/pdf', [PanduanController::class, 'aktaPdf'])
    ->where('slug', '[a-z0-9-]+')
    ->name('panduan.akta.pdf');

Route::get('/korporat', [PageController::class, 'korporat'])->name('korporat.index');
Route::get('/korporat/{slug}', [PageController::class, 'korporatShow'])
    ->where('slug', '[a-z0-9-]+')
    ->name('korporat.show');
Route::get('/sumber', [SumberController::class, 'index'])->name('sumber.index');
Route::get('/sumber/galeri/{type?}', [SumberController::class, 'galeri'])
    ->where('type', 'gambar|audio|video')
    ->name('sumber.galeri');
Route::get('/sumber/data-terbuka', [SumberController::class, 'dataTerbuka'])->name('sumber.data-terbuka');
Route::get('/sumber/pelan-strategik', [SumberController::class, 'pelanStrategik'])->name('sumber.pelan-strategik');
Route::get('/sumber/penerbitan', [SumberController::class, 'penerbitan'])->name('sumber.penerbitan');
Route::get('/sumber/infografik', [SumberController::class, 'infografik'])->name('sumber.infografik');
Route::get('/sumber/arkib/{type?}', [SumberController::class, 'arkib'])
    ->where('type', 'berita|tender|pengumuman|laporan')
    ->name('sumber.arkib');
Route::get('/halaman/{slug}', [PageController::class, 'show'])->name('page.show');
Route::get('/halaman/{slug}/pdf', [PageController::class, 'pagePdf'])
    ->where('slug', '[a-z0-9-]+')
    ->name('page.pdf');

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
