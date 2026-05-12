@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <style>
        .login-container {
            max-width: 420px;
            margin: 0 auto;
            margin-top: 50px;
        }

        .login-brand {
            text-align: center;
            margin-bottom: 32px;
        }

        .login-brand-icon {
            width: 64px;
            height: 64px;
            background: var(--primary);
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            color: #fff;
            margin-bottom: 16px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.25);
        }


        .login-brand h1 {
            font-size: 24px;
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .login-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 36px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .form-input-wrapper {
            position: relative;
        }

        .form-input-wrapper i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 14px;
        }

        .form-input-wrapper .form-control {
            padding-left: 40px;
        }

        .register-link {
            margin-top: 14px;
            text-align: center;
            font-size: 13px;
            color: var(--text-muted);
        }

        .register-link a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
        }

        .register-link a:hover {
            text-decoration: underline;
        }
    </style>

    <div class="login-container">
        <div class="login-brand">
            <div class="login-brand-icon">
                <i class="fas fa-car"></i>
            </div>
            <h1>Park-Er</h1>
            <p class="text-muted">Sistem Manajemen Parkir</p>
        </div>

        <div class="login-card">
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group mb-4">
                    <label class="form-label">Username</label>
                    <div class="form-input-wrapper">
                        <i class="fas fa-user"></i>
                        <input type="text" name="username" class="form-control" placeholder="Masukkan username"
                            value="{{ old('username') }}" autofocus required>
                    </div>
                    @error('username')
                        <div class="form-error text-danger" style="margin-top: 5px; font-size: 0.875em;">{{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group mb-4">
                    <label class="form-label">Password</label>
                    <div class="form-input-wrapper">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="password" class="form-control" placeholder="Masukkan password"
                            required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px; margin-top: 8px;">
                    <i class="fas fa-sign-in-alt"></i> Masuk
                </button>

                <div class="register-link mt-4">
                    Belum punya akun? <a href="{{ route('register') }}">Daftar di sini</a>
                </div>
            </form>
        </div>
    </div>
@endsection
