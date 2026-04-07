@extends('layouts.app')

@section('title', 'Log Aktivitas')

@section('content')
    <div class="card mb-6" style="margin-bottom:24px">
        <div class="card-body" style="padding:16px 24px">
            <form method="GET" action="{{ route('log.index') }}" style="display:flex;gap:12px;align-items:end">
                <div style="flex:1">
                    <label class="form-label">Cari Aktivitas</label>
                    <input type="text" name="search" class="form-control" placeholder="Cari aktivitas..."
                        value="{{ request('search') }}">
                </div>
                <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search"></i> Cari</button>
                <a href="{{ route('log.index') }}" class="btn btn-outline btn-sm">Reset</a>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>User</th>
                        <th>Aktivitas</th>
                        <th>Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td>{{ $log->id_log }}</td>
                            <td>
                                <strong>{{ $log->user->nama_lengkap ?? '-' }}</strong>
                                <div style="font-size:11px;color:var(--text-muted)">{{ $log->user->role ?? '' }}</div>
                            </td>
                            <td>
                                @if (str_contains($log->aktivitas, 'Login'))
                                    <span class="badge badge-success"><i class="fas fa-sign-in-alt"></i></span>
                                @elseif(str_contains($log->aktivitas, 'Logout'))
                                    <span class="badge badge-danger"><i class="fas fa-sign-out-alt"></i></span>
                                @elseif(str_contains($log->aktivitas, 'masuk'))
                                    <span class="badge badge-info"><i class="fas fa-arrow-right"></i></span>
                                @elseif(str_contains($log->aktivitas, 'keluar'))
                                    <span class="badge badge-warning"><i class="fas fa-arrow-left"></i></span>
                                @else
                                    <span class="badge badge-primary"><i class="fas fa-cog"></i></span>
                                @endif
                                {{ $log->aktivitas }}
                            </td>
                            <td style="white-space:nowrap">{{ $log->waktu_aktivitas->format('d/m/Y H:i:s') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">
                                <div class="empty-state">
                                    <i class="fas fa-history"></i>
                                    <p>Belum ada log aktivitas</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">{{ $logs->links() }}</div>
@endsection
