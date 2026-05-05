<?php

use App\Http\Controllers\LocaleController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('home'))->name('home');

Route::get('/states', fn () => view('states'))->name('states');

Route::get('/locale/{locale}', LocaleController::class)
    ->where('locale', 'ms|en')
    ->name('locale.switch');
