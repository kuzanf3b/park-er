@extends('layouts.app')

@section('title', 'Manajemen User')

@section('content')
<div class="flex justify-between items-center mb-4" style="margin-bottom:24px">
    <div></div>
    <a href="{{ route('users.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah User
    </a>
</div>

<div class="card">
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Username</th>
                    <th>Nama Lengkap</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $u)
                <tr>
                    <td>{{ $u->id_user }}</td>
                    <td><strong>{{ $u->username }}</strong></td>
                    <td>{{ $u->nama_lengkap }}</td>
                    <td>
                        @if($u->role === 'admin')
                            <span class="badge badge-danger">Admin</span>
                        @elseif($u->role === 'petugas')
                            <span class="badge badge-info">Petugas</span>
                        @else
                            <span class="badge badge-warning">Owner</span>
                        @endif
                    </td>
                    <td>
                        @if($u->status_aktif)
                            <span class="badge badge-success">Aktif</span>
                        @else
                            <span class="badge badge-danger">Non-aktif</span>
                        @endif
                    </td>
                    <td>
                        <div class="btn-group">
                            <a href="{{ route('users.edit', $u->id_user) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            @if($u->id_user !== auth()->user()->id_user)
                            <form method="POST" action="{{ route('users.destroy', $u->id_user) }}" style="display:inline" onsubmit="return confirm('Hapus user ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">{{ $users->links() }}</div>
@endsection
