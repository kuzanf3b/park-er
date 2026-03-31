<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Aplikasi Parkir</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #0f172a;
            position: relative;
            overflow: auto;
        }

        .login-container {
            width: 420px;
            position: relative;
            z-index: 1;
        }

        .login-brand {
            text-align: center;
            margin-bottom: 32px;
        }

        .login-brand-icon {
            width: 64px;
            height: 64px;
            background: #334155;
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            color: #fff;
            margin-bottom: 16px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.25);
        }

        .login-brand h1 {
            color: #fff;
            font-size: 24px;
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .login-brand p {
            color: #94a3b8;
            font-size: 14px;
            margin-top: 4px;
        }

        .login-card {
            background: rgba(255,255,255,0.03);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 16px;
            padding: 36px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            color: #e2e8f0;
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 8px;
        }

        .form-input-wrapper {
            position: relative;
        }

        .form-input-wrapper i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
            font-size: 14px;
        }

        .form-control {
            width: 100%;
            padding: 12px 14px 12px 40px;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 10px;
            color: #fff;
            font-size: 14px;
            font-family: inherit;
            transition: all 0.2s;
        }

        .form-control:focus {
            outline: none;
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79,70,229,0.15);
            background: rgba(255,255,255,0.08);
        }

        .form-control::placeholder {
            color: #64748b;
        }

        .form-error {
            color: #fca5a5;
            font-size: 12px;
            margin-top: 6px;
        }

        .btn-login {
            width: 100%;
            padding: 14px;
            background: #1d4ed8;
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            font-family: inherit;
            margin-top: 8px;
        }

        .btn-login:hover {
            transform: translateY(-1px);
            background: #1e40af;
            box-shadow: 0 8px 25px rgba(0,0,0,0.28);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .login-footer {
            text-align: center;
            margin-top: 24px;
            color: #64748b;
            font-size: 12px;
        }

        .register-link {
            margin-top: 14px;
            text-align: center;
            font-size: 13px;
            color: #94a3b8;
        }

        .register-link a {
            color: #93c5fd;
            text-decoration: none;
            font-weight: 600;
        }

        .register-link a:hover {
            text-decoration: underline;
        }

        @media (max-width: 480px) {
            .login-container {
                width: calc(100% - 32px);
                margin: 16px;
            }

            .login-card {
                padding: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-brand">
            <div class="login-brand-icon">
                <i class="fas fa-car"></i>
            </div>
            <h1>Parkir App</h1>
            <p>Sistem Manajemen Parkir</p>
        </div>

        <div class="login-card">
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label">Username</label>
                    <div class="form-input-wrapper">
                        <i class="fas fa-user"></i>
                        <input type="text" name="username" class="form-control" placeholder="Masukkan username" value="{{ old('username') }}" autofocus required>
                    </div>
                    @error('username')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Password</label>
                    <div class="form-input-wrapper">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                    </div>
                </div>

                <button type="submit" class="btn-login">
                    <i class="fas fa-sign-in-alt"></i> Masuk
                </button>

                <div class="register-link">
                    Belum punya akun? <a href="{{ route('register') }}">Daftar di sini</a>
                </div>
            </form>
        </div>

        <div class="login-footer">
            &copy; {{ date('Y') }} Parkir App. All rights reserved.
        </div>
    </div>
</body>
</html>
