<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocale
{
    private const SUPPORTED = ['ms', 'en'];
    private const COOKIE = 'jkptg_locale';

    public function handle(Request $request, Closure $next)
    {
        $locale = $request->cookie(self::COOKIE)
            ?? $this->fromAcceptLanguage($request)
            ?? config('app.locale', 'ms');

        if (!in_array($locale, self::SUPPORTED, true)) {
            $locale = 'ms';
        }

        App::setLocale($locale);
        return $next($request);
    }

    private function fromAcceptLanguage(Request $request): ?string
    {
        $header = $request->header('Accept-Language', '');
        foreach (preg_split('/,\s*/', $header) as $tag) {
            $tag = strtolower(substr($tag, 0, 2));
            if (in_array($tag, self::SUPPORTED, true)) {
                return $tag;
            }
        }
        return null;
    }

    public static function cookieName(): string
    {
        return self::COOKIE;
    }
}
