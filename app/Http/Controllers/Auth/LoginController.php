<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Support\MathCaptcha;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\View\View;

class LoginController extends Controller
{
    private const MAX_ATTEMPTS = 5;

    private const DECAY_SECONDS = 60;

    public function create(): View
    {
        return view('auth.login', [
            'captchaQuestion' => MathCaptcha::generate('login'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $key = $this->throttleKey($request);

        if (RateLimiter::tooManyAttempts($key, self::MAX_ATTEMPTS)) {
            $seconds = RateLimiter::availableIn($key);

            return back()
                ->withInput($request->only('email', 'remember'))
                ->withErrors(['email' => "Terlalu banyak percobaan masuk. Coba lagi dalam {$seconds} detik."]);
        }

        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'captcha' => ['required', 'integer', function ($attribute, $value, $fail): void {
                if (! MathCaptcha::check('login', $value)) {
                    $fail('Jawaban captcha salah. Silakan coba lagi.');
                }
            }],
        ]);

        if (! Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']], $request->boolean('remember'))) {
            RateLimiter::hit($key, self::DECAY_SECONDS);

            return back()
                ->withInput($request->only('email', 'remember'))
                ->withErrors(['email' => 'Email atau kata sandi salah.']);
        }

        RateLimiter::clear($key);
        MathCaptcha::forget('login');

        $request->session()->regenerate();

        ActivityLog::record(ActivityLog::ACTION_LOGIN, 'masuk ke panel admin');

        return redirect()->intended(route('admin.dashboard'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        ActivityLog::record(ActivityLog::ACTION_LOGOUT, 'keluar dari panel admin');

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    private function throttleKey(Request $request): string
    {
        return 'login:'.Str::lower((string) $request->input('email')).'|'.$request->ip();
    }
}
