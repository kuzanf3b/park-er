@extends('layouts.app')

@section('title', 'Register')

@section('content')
    <style>
        .register-container {
            max-width: 460px;
            margin: 0 auto;
            margin-top: 50px;
        }

        .register-brand {
            text-align: center;
            margin-bottom: 28px;
        }

        .register-brand-icon {
            width: 64px;
            height: 64px;
            background: var(--primary);
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            color: #fff;
            margin-bottom: 14px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.25);
        }


        .register-brand h1 {
            font-size: 24px;
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .register-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 28px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        @media (max-width: 520px) {
            .form-row {
                grid-template-columns: 1fr;
            }
        }

        .auth-switch {
            text-align: center;
            color: var(--text-muted);
            font-size: 13px;
            margin-top: 18px;
        }

        .auth-switch a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
        }

        .auth-switch a:hover {
            text-decoration: underline;
        }
    </style>

    <div class="register-container">
        <div class="register-brand">
            <div class="register-brand-icon">
                <i class="fas fa-user-plus"></i>
            </div>
            <h1>Register Akun</h1>
            <p class="text-muted">Buat akun owner kendaraan</p>
        </div>

        <div class="register-card">
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="form-group mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" class="form-control" value="{{ old('nama_lengkap') }}"
                        placeholder="Masukkan nama lengkap" required>
                    @error('nama_lengkap')
                        <div class="form-error text-danger" style="margin-top: 5px; font-size: 0.875em;">{{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" value="{{ old('username') }}"
                        placeholder="Contoh: owner01" required>
                    @error('username')
                        <div class="form-error text-danger" style="margin-top: 5px; font-size: 0.875em;">{{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-row mb-3">
                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter"
                            required>
                        @error('password')
                            <div class="form-error text-danger" style="margin-top: 5px; font-size: 0.875em;">{{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="form-control"
                            placeholder="Ulangi password" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px; margin-top: 12px;">
                    <i class="fas fa-user-check"></i> Daftar
                </button>
            </form>

            <div class="auth-switch">
                Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a>
            </div>
        </div>
    </div>
@endsection
