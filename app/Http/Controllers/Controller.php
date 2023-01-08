<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    // Menampilkan form login
    public function showLoginForm()
    {
        return view('login');
    }

    // Mengirim request login
    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Cek login user
        if (Auth::attempt([
            'username' => $request->username,
            'password' => $request->password,
        ])) {
            // Jika login berhasil, redirect ke halaman dashboard
            return redirect()->route('dashboard');
        } else {
            // Jika login gagal, tampilkan pesan error
            return redirect()->back()->with('error', 'Username atau password salah');
        }
    }

    // Menampilkan form reset password
    public function showResetPasswordForm()
    {
        return view('reset_password');
    }

    // Mengirim request reset password
    public function resetPassword(Request $request)
    {
        // Validasi input
        $request->validate([
            'email' => 'required|string|email',
        ]);

        // Cari user dengan email yang sesuai
        $user = User::where('email', $request->email)->first();

        if ($user) {
            // Jika user ditemukan, kirim email reset password
            // ...

            return redirect()->back()->with('success', 'Silakan cek email Anda untuk reset password');
        } else {
            // Jika user tidak ditemukan, tampilkan pesan error
            return redirect()->back()->with('error', 'Email tidak terdaftar');
        }
    }

    // Menampilkan form register
    public function showRegisterForm()
    {
        return view('register');
    }

    // Mengirim request register
    public function register(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:10|confirmed',
        ]);

        // Buat regex untuk validasi password
        $passwordRegex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*])(?=.{10,})/';


    }
}