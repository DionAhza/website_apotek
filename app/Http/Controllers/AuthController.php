<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function register(): View
    {
        return view('auth.register');
    }

    public function registerAction(RegisterRequest $request): RedirectResponse
    {
        // Role selalu "user". Admin hanya dibuat lewat seeder/perintah terkontrol.
        $user = User::create([
            'name' => $request->validated('name'),
            'email' => $request->validated('email'),
            'password' => Hash::make($request->validated('password')),
            'role' => User::ROLE_USER,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('home')->with('sukses', 'Akun berhasil dibuat. Selamat datang, '.$user->name.'!');
    }

    public function login(): View
    {
        return view('auth.login');
    }

    public function loginAction(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withErrors(['email' => 'Email atau kata sandi salah.'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        $default = $request->user()->isAdmin()
            ? route('admin.dashboard')
            : route('home');

        return redirect()->intended($default)->with('sukses', 'Berhasil masuk.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('sukses', 'Anda telah keluar.');
    }
}
