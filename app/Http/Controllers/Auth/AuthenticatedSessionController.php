<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Laravel\Socialite\Facades\Socialite;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(route('home', absolute: false));
    }
    public function redirectToGoogle(): RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }
    public function handleGoogleCallback(): RedirectResponse

    {
        $googleUser = Socialite::driver('google')->user();

        if (User::where('email', $googleUser->email)->exists()) {
            $user = User::where('email', $googleUser->email)->first();
            Auth::login($user);
            return redirect()->route('home');
        }
        $user = new User;
        $user->name = $googleUser->name;
        $user->email = $googleUser->email;
        $user->password = Hash::make("$user->name$user->email");
        $user->email_verified_at = now();
        $user->remember_token = Str::random(60);
        $user->save();

        Auth::login($user);


        return redirect()->route('home');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
