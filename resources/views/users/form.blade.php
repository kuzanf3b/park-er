@extends('layouts.app')

@section('title', isset($user) ? 'Edit User' : 'Tambah User')

@section('content')
    <div class="card" style="max-width:500px">
        <div class="card-header">
            <h3>{{ isset($user) ? 'Edit' : 'Tambah' }} User</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ isset($user) ? route('users.update', $user->id_user) : route('users.store') }}">
                @csrf
                @if (isset($user))
                    @method('PUT')
                @endif

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Username *</label>
                        <input type="text" name="username" class="form-control"
                            value="{{ old('username', $user->username ?? '') }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nama Lengkap *</label>
                        <input type="text" name="nama_lengkap" class="form-control"
                            value="{{ old('nama_lengkap', $user->nama_lengkap ?? '') }}" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Password
                            {{ isset($user) ? '(kosongkan jika tidak diubah)' : '*' }}</label>
                        <input type="password" name="password" class="form-control" {{ isset($user) ? '' : 'required' }}>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Role *</label>
                        <select name="role" class="form-control" required>
                            <option value="">-- Pilih --</option>
                            @foreach (['admin', 'petugas', 'owner'] as $role)
                                <option value="{{ $role }}"
                                    {{ old('role', $user->role ?? '') === $role ? 'selected' : '' }}>
                                    {{ ucfirst($role) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @if (isset($user))
                        <div class="form-group">
                            <label class="form-label">Status *</label>
                            <select name="status_aktif" class="form-control" required>
                                <option value="1" {{ old('status_aktif', $user->status_aktif) ? 'selected' : '' }}>
                                    Aktif</option>
                                <option value="0" {{ !old('status_aktif', $user->status_aktif) ? 'selected' : '' }}>
                                    Non-aktif</option>
                            </select>
                        </div>
                    @endif
                </div>

                <div class="btn-group">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
                    <a href="{{ route('users.index') }}" class="btn btn-outline">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
