<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Pengembalian;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PengembalianController extends Controller
{
    /**
     * Tampilkan daftar buku yang sudah di-approve tapi belum dikembalikan
     */
    public function index()
    {
        $peminjaman = Peminjaman::with('book', 'user')
            ->where('status', 'approved')
            ->get();

        return view('pengembalian.index', compact('peminjaman'));
    }

    /**
     * Proses pengembalian buku
     */
    public function kembali($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        // Hanya admin/petugas
        if (!in_array(auth()->user()->role, ['resident', 'petugas'])) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses.');
        }

        // Hitung denda
        $tgl_harus_kembali = Carbon::parse($peminjaman->tanggal_kembali);
        $tgl_sekarang      = Carbon::now();

        $denda = 0;
        if ($tgl_sekarang->gt($tgl_harus_kembali)) {
            $hariTerlambat = $tgl_sekarang->diffInDays($tgl_harus_kembali);
            $denda = $hariTerlambat * 2000; // contoh: 2000 per hari
        }

        // ✅ Cek dulu apakah sudah ada record pengembalian
        $cek = Pengembalian::where('peminjaman_id', $peminjaman->id)->first();
        if (!$cek) {
            // kalau belum ada → buat baru
            Pengembalian::create([
                'peminjaman_id'        => $peminjaman->id,
                'tanggal_pengembalian' => $tgl_sekarang,
                'denda'                => $denda,
            ]);
        } else {
            // kalau sudah ada → update denda biar gak dobel record
            $cek->update([
                'tanggal_pengembalian' => $tgl_sekarang,
                'denda'                => $denda,
            ]);
        }

        // ✅ Selalu update status peminjaman jadi "dikembalikan"
        $peminjaman->update(['status' => 'dikembalikan']);

        return redirect()->route('pengembalian.index')
            ->with('success', 'Buku berhasil dikembalikan.');
    }

    /**
     * Riwayat pengembalian buku
     */
    public function riwayat()
    {
        $pengembalian = Pengembalian::with(['peminjaman.book', 'peminjaman.user'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pengembalian.riwayat', compact('pengembalian'));
    }

    /**
     * Bayar denda
     */
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
        $pengembalian->update(['denda' => 0]);

        return redirect()->back()->with('success', 'Denda berhasil dibayar.');
    }
}
