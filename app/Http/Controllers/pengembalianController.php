<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Pengembalian;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PengembalianController extends Controller
{
    // Tampilkan daftar buku yang sudah di-approve tapi belum dikembalikan
    public function index()
    {
        $peminjaman = Peminjaman::with('book', 'user')
            ->where('status', 'approved')
            ->get();

        return view('pengembalian.index', compact('peminjaman'));
    }

    // Proses pengembalian buku
    public function kembali($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        // Hanya admin/petugas
        if (!in_array(auth()->user()->role, ['resident', 'petugas'])) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses.');
        }

     

        // Hitung denda
        $tgl_harus_kembali = Carbon::parse($peminjaman->tanggal_kembali);
        $tgl_sekarang = Carbon::now();
        $denda = 0;
        if ($tgl_sekarang->gt($tgl_harus_kembali)) {
            $hariTerlambat = $tgl_sekarang->diffInDays($tgl_harus_kembali);
            $denda = $hariTerlambat * 2000; 
        }

        // Simpan ke tabel pengembalian
        Pengembalian::create([
            'peminjaman_id'       => $peminjaman->id_peminjaman,
            'tanggal_pengembalian'=> $tgl_sekarang,
            'denda'               => $denda,
        ]);

        // Update status peminjaman
        $peminjaman->update(['status' => 'dikembalikan']);

        return redirect()->route('pengembalian.index')->with('success', 'Buku berhasil dikembalikan.');
    }

    // Bayar denda
    public function bayarDenda($id)
    {
        $pengembalian = Pengembalian::where('peminjaman_id', $id)->first();

        if (!$pengembalian) {
            return redirect()->back()->with('error', 'Data pengembalian tidak ditemukan.');
        }

        // Validasi: cek apakah denda sudah dibayar
        if ($pengembalian->denda == 0) {
            return redirect()->back()->with('info', 'Denda sudah dibayar atau tidak ada denda.');
        }

        // Bayar denda: anggap langsung lunas dengan menghapus nominal denda
        $pengembalian->denda = 0;
        $pengembalian->save();

        return redirect()->back()->with('success', 'Denda berhasil dibayar.');
    }
}
