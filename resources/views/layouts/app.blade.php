<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Aplikasi Parkir') - {{ config('app.name') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        *,
        *::before,
        *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #4f46e5;
            --primary-light: #6366f1;
            --primary-dark: #4338ca;
            --accent: #06b6d4;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --surface: #ffffff;
            --surface-2: #f8fafc;
            --surface-3: #f1f5f9;
            --border: #e2e8f0;
            --text: #1e293b;
            --text-secondary: #64748b;
            --text-muted: #94a3b8;
            --sidebar-bg: #0f172a;
            --sidebar-hover: #1e293b;
            --sidebar-active: #4f46e5;
            --shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.05);
            --shadow: 0 1px 3px rgba(0, 0, 0, 0.1), 0 1px 2px rgba(0, 0, 0, 0.06);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --radius: 8px;
            --radius-lg: 12px;
            --transition: 0.2s ease;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--surface-2);
            color: var(--text);
            line-height: 1.6;
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 260px;
            background: var(--sidebar-bg);
            color: #e2e8f0;
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            z-index: 50;
            transition: transform 0.3s ease;
        }

        .sidebar-brand {
            padding: 20px 24px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .sidebar-brand-icon {
            width: 40px;
            height: 40px;
            background: #334155;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            color: #fff;
        }

        .sidebar-brand h2 {
            font-size: 16px;
            font-weight: 700;
            color: #fff;
            letter-spacing: -0.3px;
        }

        .sidebar-brand small {
            font-size: 11px;
            color: var(--text-muted);
            display: block;
            margin-top: 2px;
        }

        .sidebar-nav {
            flex: 1;
            padding: 16px 12px;
            overflow-y: auto;
        }

        .sidebar-label {
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            color: var(--text-muted);
            padding: 8px 12px 6px;
            margin-top: 8px;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 12px;
            color: #94a3b8;
            text-decoration: none;
            border-radius: var(--radius);
            font-size: 14px;
            font-weight: 400;
            transition: all var(--transition);
            margin-bottom: 2px;
        }

        .sidebar-link:hover {
            background: var(--sidebar-hover);
            color: #e2e8f0;
        }

        .sidebar-link.active {
            background: var(--sidebar-active);
            color: #fff;
            font-weight: 500;
        }

        .sidebar-link i {
            width: 20px;
            text-align: center;
            font-size: 15px;
        }

        .sidebar-footer {
            padding: 16px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
        }

        .sidebar-user {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px;
        }

        .sidebar-avatar {
            width: 36px;
            height: 36px;
            background: #334155;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 14px;
            color: #fff;
        }

        .sidebar-user-info {
            flex: 1;
            min-width: 0;
        }

        .sidebar-user-name {
            font-size: 13px;
            font-weight: 600;
            color: #e2e8f0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sidebar-user-role {
            font-size: 11px;
            color: var(--text-muted);
            text-transform: capitalize;
        }

        /* Main content */
        .main-content {
            margin-left: 260px;
            flex: 1;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .topbar {
            background: var(--surface);
            padding: 16px 32px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 40;
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .menu-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 20px;
            color: var(--text);
            cursor: pointer;
            padding: 8px;
        }

        .page-title {
            font-size: 20px;
            font-weight: 700;
            color: var(--text);
            letter-spacing: -0.3px;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .btn-logout {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            background: transparent;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            color: var(--text-secondary);
            font-size: 13px;
            cursor: pointer;
            transition: all var(--transition);
            font-family: inherit;
        }

        .btn-logout:hover {
            background: var(--danger);
            color: #fff;
            border-color: var(--danger);
        }

        .content-area {
            flex: 1;
            padding: 32px;
        }

        /* Alert */
        .alert {
            padding: 14px 18px;
            border-radius: var(--radius);
            font-size: 14px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-success {
            background: #ecfdf5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .alert-error {
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .alert-warning {
            background: #fffbeb;
            color: #92400e;
            border: 1px solid #fde68a;
        }

        /* Cards */
        .card {
            background: var(--surface);
            border-radius: var(--radius-lg);
            border: 1px solid var(--border);
            box-shadow: var(--shadow-sm);
        }

        .card-header {
            padding: 20px 24px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-header h3 {
            font-size: 16px;
            font-weight: 600;
        }

        .card-body {
            padding: 24px;
        }

        /* Tables */
        .table-container {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th {
            background: var(--surface-3);
            padding: 12px 16px;
            text-align: left;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--text-secondary);
            white-space: nowrap;
        }

        table td {
            padding: 12px 16px;
            border-bottom: 1px solid var(--border);
            font-size: 14px;
            vertical-align: middle;
        }

        table tr:hover td {
            background: var(--surface-2);
        }

        table tr:last-child td {
            border-bottom: none;
        }

        /* Badges */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 10px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: 600;
            white-space: nowrap;
        }

        .badge-success {
            background: #ecfdf5;
            color: #065f46;
        }

        .badge-danger {
            background: #fef2f2;
            color: #991b1b;
        }

        .badge-warning {
            background: #fffbeb;
            color: #92400e;
        }

        .badge-info {
            background: #eff6ff;
            color: #1e40af;
        }

        .badge-primary {
            background: #eef2ff;
            color: #3730a3;
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 18px;
            border-radius: var(--radius);
            font-size: 14px;
            font-weight: 500;
            border: none;
            cursor: pointer;
            transition: all var(--transition);
            text-decoration: none;
            font-family: inherit;
            white-space: nowrap;
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 13px;
        }

        .btn-primary {
            background: var(--primary);
            color: #fff;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }

        .btn-success {
            background: var(--success);
            color: #fff;
        }

        .btn-success:hover {
            background: #059669;
        }

        .btn-warning {
            background: var(--warning);
            color: #fff;
        }

        .btn-warning:hover {
            background: #d97706;
        }

        .btn-danger {
            background: var(--danger);
            color: #fff;
        }

        .btn-danger:hover {
            background: #dc2626;
        }

        .btn-outline {
            background: transparent;
            color: var(--text-secondary);
            border: 1px solid var(--border);
        }

        .btn-outline:hover {
            background: var(--surface-3);
        }

        .btn-group {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        /* Forms */
        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--text);
            margin-bottom: 6px;
        }

        .form-control {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            font-size: 14px;
            font-family: inherit;
            color: var(--text);
            background: var(--surface);
            transition: border-color var(--transition), box-shadow var(--transition);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        select.form-control {
            cursor: pointer;
        }

        .form-error {
            color: var(--danger);
            font-size: 12px;
            margin-top: 4px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        /* Grid */
        .grid-2 {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .grid-3 {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .grid-4 {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }

        /* Stat Cards */
        .stat-card {
            background: var(--surface);
            border-radius: var(--radius-lg);
            padding: 24px;
            border: 1px solid var(--border);
            box-shadow: var(--shadow-sm);
            display: flex;
            align-items: flex-start;
            gap: 16px;
            transition: all var(--transition);
        }

        .stat-card:hover {
            box-shadow: var(--shadow-md);
            transform: translateY(-2px);
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: var(--radius);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }

        .stat-icon.blue {
            background: #eff6ff;
            color: #2563eb;
        }

        .stat-icon.green {
            background: #ecfdf5;
            color: #059669;
        }

        .stat-icon.yellow {
            background: #fffbeb;
            color: #d97706;
        }

        .stat-icon.purple {
            background: #f5f3ff;
            color: #7c3aed;
        }

        .stat-info h4 {
            font-size: 12px;
            font-weight: 500;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }

        .stat-value {
            font-size: 28px;
            font-weight: 700;
            color: var(--text);
            letter-spacing: -1px;
        }

        /* Progress bar */
        .progress-bar {
            height: 8px;
            background: var(--surface-3);
            border-radius: 50px;
            overflow: hidden;
            margin-top: 8px;
        }

        .progress-fill {
            height: 100%;
            border-radius: 50px;
            transition: width 0.5s ease;
        }

        .progress-fill.green {
            background: var(--success);
        }

        .progress-fill.yellow {
            background: var(--warning);
        }

        .progress-fill.red {
            background: var(--danger);
        }

        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            gap: 4px;
            padding: 20px 0;
            list-style: none;
        }

        .pagination li a,
        .pagination li span {
            padding: 8px 14px;
            border-radius: var(--radius);
            font-size: 13px;
            text-decoration: none;
            color: var(--text-secondary);
            border: 1px solid var(--border);
            transition: all var(--transition);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 42px;
        }

        .pagination li.active span {
            background: var(--primary);
            color: #fff;
            border-color: var(--primary);
        }

        .pagination li a:hover {
            background: var(--surface-3);
        }

        .pagination li.disabled span {
            color: var(--text-muted);
            background: var(--surface-2);
            cursor: not-allowed;
        }

        /* Utility */
        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .text-muted {
            color: var(--text-muted);
        }

        .text-success {
            color: var(--success);
        }

        .text-danger {
            color: var(--danger);
        }

        .text-warning {
            color: var(--warning);
        }

        .font-mono {
            font-family: 'JetBrains Mono', monospace;
        }

        .mt-2 {
            margin-top: 8px;
        }

        .mt-4 {
            margin-top: 16px;
        }

        .mt-6 {
            margin-top: 24px;
        }

        .mb-4 {
            margin-bottom: 16px;
        }

        .mb-6 {
            margin-bottom: 24px;
        }

        .flex {
            display: flex;
        }

        .items-center {
            align-items: center;
        }

        .justify-between {
            justify-content: space-between;
        }

        .gap-2 {
            gap: 8px;
        }

        .gap-4 {
            gap: 16px;
        }

        /* Overlay for mobile sidebar */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 45;
        }

        .sidebar-overlay.active {
            display: block;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .menu-toggle {
                display: block;
            }

            .content-area {
                padding: 16px;
            }

            .grid-2,
            .grid-3,
            .grid-4 {
                grid-template-columns: 1fr;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .topbar {
                padding: 12px 16px;
            }

            .stat-value {
                font-size: 22px;
            }
        }

        @media (max-width: 1024px) {
            .grid-4 {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        /* Receipt specific */
        .receipt-card {
            max-width: 480px;
            margin: 0 auto;
            background: var(--surface);
            border-radius: var(--radius-lg);
            border: 1px solid var(--border);
            box-shadow: var(--shadow-lg);
            overflow: hidden;
        }

        .receipt-header {
            background: #1e293b;
            color: #fff;
            padding: 24px;
            text-align: center;
        }

        .receipt-body {
            padding: 24px;
        }

        .receipt-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px dashed var(--border);
            font-size: 14px;
        }

        .receipt-row:last-child {
            border-bottom: none;
        }

        .receipt-total {
            display: flex;
            justify-content: space-between;
            padding: 16px 0;
            border-top: 2px solid var(--text);
            font-size: 18px;
            font-weight: 700;
            margin-top: 8px;
        }

        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 48px 24px;
            color: var(--text-muted);
        }

        .empty-state i {
            font-size: 48px;
            margin-bottom: 16px;
            opacity: 0.5;
        }

        .empty-state p {
            font-size: 15px;
        }
    </style>
</head>

<body>
    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <div class="sidebar-brand-icon">
                <i class="fas fa-car"></i>
            </div>
            <div>
                <h2>Parkir App</h2>
                <small>Management System</small>
            </div>
        </div>

        <nav class="sidebar-nav">
            <div class="sidebar-label">Menu Utama</div>
            <a href="{{ route('dashboard') }}"
                class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fas fa-th-large"></i>
                <span>Dashboard</span>
            </a>

            @if (auth()->user()->role === 'admin')
                <div class="sidebar-label">Operasional</div>
                <a href="{{ route('transaksi.index') }}"
                    class="sidebar-link {{ request()->routeIs('transaksi.index') ? 'active' : '' }}">
                    <i class="fas fa-exchange-alt"></i>
                    <span>Transaksi</span>
                </a>
            @endif

            @if (auth()->user()->role === 'admin' || auth()->user()->role === 'owner')
                <a href="{{ route('kendaraan.index') }}"
                    class="sidebar-link {{ request()->routeIs('kendaraan.*') ? 'active' : '' }}">
                    <i class="fas fa-motorcycle"></i>
                    <span>{{ auth()->user()->role === 'owner' ? 'Kendaraan Saya' : 'Kendaraan' }}</span>
                </a>
            @endif

            @if (auth()->user()->role === 'admin')
                <div class="sidebar-label">Pengaturan</div>
                <a href="{{ route('area-parkir.index') }}"
                    class="sidebar-link {{ request()->routeIs('area-parkir.*') ? 'active' : '' }}">
                    <i class="fas fa-parking"></i>
                    <span>Area Parkir</span>
                </a>
                <a href="{{ route('tarif.index') }}"
                    class="sidebar-link {{ request()->routeIs('tarif.*') ? 'active' : '' }}">
                    <i class="fas fa-tags"></i>
                    <span>Tarif</span>
                </a>
                <a href="{{ route('users.index') }}"
                    class="sidebar-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i>
                    <span>Manajemen User</span>
                </a>
            @endif

            @if (auth()->user()->role === 'admin' || auth()->user()->role === 'owner')
                <div class="sidebar-label">Laporan</div>
                <a href="{{ route('laporan.index') }}"
                    class="sidebar-link {{ request()->routeIs('laporan.*') ? 'active' : '' }}">
                    <i class="fas fa-chart-bar"></i>
                    <span>{{ auth()->user()->role === 'owner' ? 'Riwayat Parkir' : 'Laporan' }}</span>
                </a>
            @endif

            @if (auth()->user()->role === 'admin')
                <div class="sidebar-label">Monitoring</div>
                <a href="{{ route('log.index') }}"
                    class="sidebar-link {{ request()->routeIs('log.*') ? 'active' : '' }}">
                    <i class="fas fa-history"></i>
                    <span>Log Aktivitas</span>
                </a>
            @endif
        </nav>

        <div class="sidebar-footer">
            <div class="sidebar-user">
                <div class="sidebar-avatar">
                    {{ strtoupper(substr(auth()->user()->nama_lengkap, 0, 1)) }}
                </div>
                <div class="sidebar-user-info">
                    <div class="sidebar-user-name">{{ auth()->user()->nama_lengkap }}</div>
                    <div class="sidebar-user-role">{{ auth()->user()->role }}</div>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <header class="topbar">
            <div class="topbar-left">
                <button class="menu-toggle" onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
                <h1 class="page-title">@yield('title', 'Dashboard')</h1>
            </div>
            <div class="topbar-right">
                <form action="{{ route('logout') }}" method="POST" style="display:inline">
                    @csrf
                    <button type="submit" class="btn-logout">
                        <i class="fas fa-sign-out-alt"></i>
                        Keluar
                    </button>
                </form>
            </div>
        </header>

        <div class="content-area">
            @if (session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <div>
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('open');
            document.getElementById('sidebarOverlay').classList.toggle('active');
        }

        // Auto-dismiss alerts
        document.querySelectorAll('.alert').forEach(alert => {
            setTimeout(() => {
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-10px)';
                alert.style.transition = '0.3s ease';
                setTimeout(() => alert.remove(), 300);
            }, 5000);
        });
    </script>

    @yield('scripts')
</body>

</html>
