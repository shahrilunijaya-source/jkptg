<?php

use App\Http\Controllers\LocaleController;
use App\Http\Controllers\PersonaController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('home'))->name('home');

Route::get('/states', fn () => view('states'))->name('states');

Route::get('/untuk/{persona}', [PersonaController::class, 'show'])
    ->where('persona', 'orang-awam|kementerian-jabatan|warga-jkptg')
    ->name('persona.show');

Route::get('/locale/{locale}', LocaleController::class)
    ->where('locale', 'ms|en')
    ->name('locale.switch');
