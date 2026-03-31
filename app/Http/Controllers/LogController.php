<?php

namespace App\Http\Controllers;

use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::user()?->role !== 'admin') {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        $query = LogAktivitas::with('user');

        if ($request->filled('search')) {
            $query->where('aktivitas', 'like', '%' . $request->search . '%');
        }

        $logs = $query->latest('waktu_aktivitas')->paginate(20)->withQueryString();

        return view('log.index', compact('logs'));
    }
}
