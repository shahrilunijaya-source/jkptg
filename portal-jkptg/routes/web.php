<?php

use App\Http\Controllers\BorangController;
use App\Http\Controllers\HubungiController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('home'))->name('home');

Route::get('/states', fn () => view('states'))->name('states');

Route::get('/untuk/{persona}', [PersonaController::class, 'show'])
    ->where('persona', 'orang-awam|kementerian-jabatan|warga-jkptg')
    ->name('persona.show');

Route::get('/perkhidmatan', [ServiceController::class, 'index'])->name('service.index');
Route::get('/perkhidmatan/{slug}', [ServiceController::class, 'show'])->name('service.show');

Route::get('/panduan/borang', [BorangController::class, 'index'])->name('borang.index');

Route::get('/korporat', [PageController::class, 'korporat'])->name('korporat.index');
Route::get('/sumber', [PageController::class, 'sumber'])->name('sumber.index');
Route::get('/halaman/{slug}', [PageController::class, 'show'])->name('page.show');

Route::get('/hubungi', [HubungiController::class, 'index'])->name('hubungi.index');

Route::get('/locale/{locale}', LocaleController::class)
    ->where('locale', 'ms|en')
    ->name('locale.switch');
