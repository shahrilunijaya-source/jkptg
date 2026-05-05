<?php

namespace App\Http\Controllers;

use App\Http\Middleware\SetLocale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class LocaleController extends Controller
{
    public function __invoke(Request $request, string $locale)
    {
        $cookie = Cookie::make(SetLocale::cookieName(), $locale, 60 * 24 * 365, '/', null, false, false);
        $back = $request->headers->get('referer') ?: route('home');
        return redirect($back)->withCookie($cookie);
    }
}
