<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Aplikasi Parkir</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #0f172a;
            padding: 24px;
        }

        .register-container {
            width: 460px;
        }

        .register-brand {
            text-align: center;
            margin-bottom: 28px;
        }

        .register-brand-icon {
            width: 64px;
            height: 64px;
            background: #334155;
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            color: #fff;
            margin-bottom: 14px;
        }

        .register-brand h1 {
            color: #fff;
            font-size: 24px;
            font-weight: 700;
        }

        .register-brand p {
            color: #94a3b8;
            font-size: 14px;
            margin-top: 6px;
        }

        .register-card {
            background: #111827;
            border: 1px solid #334155;
            border-radius: 16px;
            padding: 28px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-label {
            display: block;
            color: #e2e8f0;
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 8px;
        }

        .form-control {
            width: 100%;
            padding: 11px 12px;
            background: #0b1220;
            border: 1px solid #334155;
            border-radius: 10px;
            color: #fff;
            font-size: 14px;
            font-family: inherit;
            transition: 0.2s;
        }

        .form-control:focus {
            outline: none;
            border-color: #64748b;
            box-shadow: 0 0 0 3px rgba(148, 163, 184, 0.15);
        }

        .form-control::placeholder {
            color: #64748b;
        }

        .form-error {
            color: #fca5a5;
            font-size: 12px;
            margin-top: 6px;
        }

        .btn-register {
            width: 100%;
            margin-top: 6px;
            padding: 12px;
            background: #1d4ed8;
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.2s;
            font-family: inherit;
        }

        .btn-register:hover {
            background: #1e40af;
        }

        .auth-switch {
            text-align: center;
            color: #94a3b8;
            font-size: 13px;
            margin-top: 18px;
        }

        .auth-switch a {
            color: #93c5fd;
            text-decoration: none;
            font-weight: 600;
        }

        .auth-switch a:hover {
            text-decoration: underline;
        }

        @media (max-width: 520px) {
            .register-container {
                width: 100%;
            }

            .register-card {
                padding: 20px;
            }

            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <div class="register-container">
        <div class="register-brand">
            <div class="register-brand-icon">
                <i class="fas fa-user-plus"></i>
            </div>
            <h1>Register Akun</h1>
            <p>Buat akun admin, petugas, atau owner kendaraan</p>
        </div>

        <div class="register-card">
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="form-group">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" class="form-control" value="{{ old('nama_lengkap') }}"
                        placeholder="Masukkan nama lengkap" required>
                    @error('nama_lengkap')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" value="{{ old('username') }}"
                            placeholder="Contoh: petugas01" required>
                        @error('username')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Role</label>
                        <select name="role" class="form-control" required>
                            <option value="">Pilih role</option>
                            <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="petugas" {{ old('role') === 'petugas' ? 'selected' : '' }}>Petugas</option>
                            <option value="owner" {{ old('role') === 'owner' ? 'selected' : '' }}>Owner Kendaraan
                            </option>
                        </select>
                        @error('role')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter"
                            required>
                        @error('password')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="form-control"
                            placeholder="Ulangi password" required>
                    </div>
                </div>

                <button type="submit" class="btn-register">
                    <i class="fas fa-user-check"></i> Daftar
                </button>
            </form>

            <div class="auth-switch">
                Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a>
            </div>
        </div>
    </div>
</body>

</html>
