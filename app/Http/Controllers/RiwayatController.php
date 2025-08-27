<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use Illuminate\Support\Facades\Auth;

class RiwayatController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Kalau role = resident atau petugas → bisa lihat semua
        if (in_array($user->role, ['resident', 'petugas'])) {
            $peminjaman = Peminjaman::with(['book', 'user'])
                ->orderBy('created_at', 'desc')
                ->get();

            $pengembalian = Pengembalian::with(['peminjaman.book', 'peminjaman.user'])
                ->whereHas('peminjaman', function ($q) {
                    $q->where('status', 'dikembalikan'); // filter hanya yang sudah dikembalikan
                })
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            // Kalau member → hanya miliknya
            $peminjaman = Peminjaman::with('book')
                ->where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();

            $pengembalian = Pengembalian::with(['peminjaman.book'])
                ->whereHas('peminjaman', function ($q) use ($user) {
                    $q->where('user_id', $user->id)
                      ->where('status', 'dikembalikan'); // filter hanya yang sudah dikembalikan
                })
                ->orderBy('created_at', 'desc')
                ->get();
        }

        // Filter data denda (semua yang denda > 0)
        $denda = $pengembalian->filter(function ($item) {
            return $item->denda > 0;
        });

        return view('riwayat.index', compact('peminjaman', 'pengembalian', 'denda'));
    }
}
