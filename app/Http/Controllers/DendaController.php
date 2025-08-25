<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Pengembalian;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DendaController extends Controller
{
    public function bayar($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        $tglKembali = Carbon::parse($peminjaman->tanggal_kembali);
        $tglSekarang = Carbon::now();
        $denda = $tglSekarang->gt($tglKembali) ? $tglSekarang->diffInDays($tglKembali)*2000 : 0;

        if($denda == 0){
            return redirect()->route('pengembalian.index')->with('success', 'Tidak ada denda, langsung bisa dikembalikan.');
        }

        Pengembalian::create([
            'peminjaman_id' => $peminjaman->id_peminjaman,
            'tanggal_pengembalian' => $tglSekarang,
            'denda' => $denda,
        ]);

        $peminjaman->update(['status' => 'dikembalikan']);

        return redirect()->route('pengembalian.index')->with('success', 'Denda dibayar & buku berhasil dikembalikan.');
    }
}
