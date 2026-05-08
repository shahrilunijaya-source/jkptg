<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AutoLoginAdmin
{
    public function handle(Request $request, Closure $next)
    {
        $onAuthPage = $request->routeIs('filament.admin.auth.*');

        if (app()->environment('local') && ! Auth::check() && ! $onAuthPage) {
            $admin = User::first();
            if ($admin) {
                Auth::login($admin, remember: true);
            }
        }

        return $next($request);
    }
}
