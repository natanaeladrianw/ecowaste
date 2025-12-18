<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showRegister()
    {
        // Redirect ke home dengan modal register terbuka
        return redirect('/')->with('open_modal', 'register');
    }

    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
            ], [
                'name.required' => 'Nama wajib diisi.',
                'name.string' => 'Nama harus berupa teks.',
                'name.max' => 'Nama maksimal 255 karakter.',
                'email.required' => 'Email wajib diisi.',
                'email.email' => 'Format email tidak valid.',
                'email.unique' => 'Email sudah terdaftar.',
                'password.required' => 'Password wajib diisi.',
                'password.min' => 'Password minimal 8 karakter.',
                'password.confirmed' => 'Konfirmasi password tidak cocok.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect('/')
                ->withErrors($e->errors())
                ->with('open_modal', 'register')
                ->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',
            'total_points' => 0,
        ]);

        Auth::login($user);

        return redirect()->route('user.dashboard')->with('success', 'Registrasi berhasil! Selamat datang di EcoWaste.');
    }

    public function showLogin()
    {
        // Redirect ke home dengan modal login terbuka
        return redirect('/')->with('open_modal', 'login');
    }

    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ], [
                'email.required' => 'Email wajib diisi.',
                'email.email' => 'Format email tidak valid.',
                'password.required' => 'Password wajib diisi.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect('/')
                ->withErrors($e->errors())
                ->with('open_modal', 'login')
                ->withInput();
        }

        if (Auth::attempt($request->only('email', 'password'), $request->filled('remember'))) {
            $request->session()->regenerate();
            
            $user = Auth::user();
            if (in_array($user->role, ['admin', 'super_admin'])) {
                return redirect()->intended(route('admin.dashboard'))->with('success', 'Login berhasil! Selamat datang kembali.');
            }
            
            return redirect()->intended(route('user.dashboard'))->with('success', 'Login berhasil! Selamat datang kembali.');
        }

        return redirect('/')->with('error', 'Email atau password salah. Silakan coba lagi.')->with('open_modal', 'login')->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function showForgotPassword()
    {
        // Redirect ke home dengan modal forgot password terbuka
        return redirect('/')->with('open_modal', 'forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        try {
            $request->validate(['email' => 'required|email']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect('/')
                ->withErrors($e->errors())
                ->with('open_modal', 'forgot-password')
                ->withInput();
        }

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return redirect('/')->with(['status' => __($status), 'open_modal' => 'forgot-password']);
        }

        return redirect('/')
            ->withErrors(['email' => __($status)])
            ->with('open_modal', 'forgot-password')
            ->withInput();
    }

    public function showResetPassword(Request $request, $token)
    {
        return view('auth.reset-password', ['token' => $token, 'request' => $request]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));
                $user->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }
}
