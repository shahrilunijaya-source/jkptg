<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function show()
    {
        if (Auth::check()) {
            return $this->redirectAfterLogin(Auth::user());
        }

        return view('auth.log-masuk');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:6', 'max:255'],
            'remember' => ['nullable'],
        ]);

        $key = 'login:' . strtolower($data['email']) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);

            throw ValidationException::withMessages([
                'email' => __('messages.auth.errors.throttled', ['seconds' => $seconds]),
            ]);
        }

        $remember = (bool) $request->boolean('remember');

        if (! Auth::attempt(['email' => $data['email'], 'password' => $data['password']], $remember)) {
            RateLimiter::hit($key, 60);

            throw ValidationException::withMessages([
                'email' => __('messages.auth.errors.invalid'),
            ]);
        }

        RateLimiter::clear($key);
        $request->session()->regenerate();

        return $this->redirectAfterLogin(Auth::user());
    }

    public function destroy(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    private function redirectAfterLogin(User $user)
    {
        if ($user->canAccessPanel(Filament::getPanel('admin'))) {
            return redirect()->intended('/admin');
        }

        return redirect()->intended(route('home'));
    }
}
