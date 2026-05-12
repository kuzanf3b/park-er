<?php

namespace App\Http\Controllers;

use App\Services\LogService;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    protected LogService $logService;

    public function __construct(LogService $logService)
    {
        $this->logService = $logService;
    }

    public function showLogin()
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user && $user->role === 'petugas') {
                return redirect()->route('operasional.kilat');
            }

            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function showRegister()
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user && $user->role === 'petugas') {
                return redirect()->route('operasional.kilat');
            }

            return redirect()->route('dashboard');
        }

        return view('auth.register');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if (!$user->status_aktif) {
                Auth::logout();
                return back()->withErrors(['username' => 'Akun Anda tidak aktif.'])->withInput();
            }

            $request->session()->regenerate();
            $this->logService->log($user->id_user, 'Login ke sistem');

            $homeRoute = $user->role === 'petugas'
                ? route('operasional.kilat')
                : route('dashboard');

            return redirect()->intended($homeRoute);
        }

        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ])->withInput();
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        $this->logService->log($user->id_user, 'Logout dari sistem');

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:tb_user,username',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:admin,petugas,owner',
        ]);

        $user = User::create([
            'nama_lengkap' => $request->nama_lengkap,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'status_aktif' => true,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        $this->logService->log($user->id_user, 'Registrasi akun baru');
        $this->logService->log($user->id_user, 'Login ke sistem');

        $homeRoute = $user->role === 'petugas' ? 'operasional.kilat' : 'dashboard';
        return redirect()->route($homeRoute)->with('success', 'Registrasi berhasil. Selamat datang!');
    }
}
