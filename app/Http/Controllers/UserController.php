<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\LogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    protected LogService $logService;

    public function __construct(LogService $logService)
    {
        $this->logService = $logService;
    }

    public function index()
    {
        $users = User::latest('created_at')->paginate(15);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:50|unique:tb_user,username',
            'password' => 'required|string|min:6|confirmed',
            'nama_lengkap' => 'required|string|max:100',
            'role' => 'required|in:admin,petugas,owner',
        ]);

        $user = User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'nama_lengkap' => $request->nama_lengkap,
            'role' => $request->role,
            'status_aktif' => true,
        ]);

        $this->logService->log(auth()->user()->id_user, 'Menambah user: ' . $user->username);

        return redirect()->route('users.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(int $id)
    {
        $user = User::findOrFail($id);
        return view('users.form', compact('user'));
    }

    public function update(Request $request, int $id)
    {
        $user = User::findOrFail($id);

        $rules = [
            'username' => 'required|string|max:50|unique:tb_user,username,' . $id . ',id_user',
            'nama_lengkap' => 'required|string|max:100',
            'role' => 'required|in:admin,petugas,owner',
            'status_aktif' => 'required|boolean',
        ];

        if ($request->filled('password')) {
            $rules['password'] = 'string|min:6|confirmed';
        }

        $request->validate($rules);

        $data = [
            'username' => $request->username,
            'nama_lengkap' => $request->nama_lengkap,
            'role' => $request->role,
            'status_aktif' => $request->status_aktif,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        $this->logService->log(auth()->user()->id_user, 'Mengubah user: ' . $user->username);

        return redirect()->route('users.index')
            ->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        $user = User::findOrFail($id);

        if ($user->id_user === auth()->user()->id_user) {
            return back()->with('error', 'Tidak dapat menghapus akun sendiri.');
        }

        $username = $user->username;
        $user->delete();

        $this->logService->log(auth()->user()->id_user, 'Menghapus user: ' . $username);

        return redirect()->route('users.index')
            ->with('success', 'User berhasil dihapus.');
    }
}
