<?php

namespace App\Http\Controllers;

use App\Mail\PeminjamanDisetujuiMail;
use App\Models\Peminjaman;
use App\Models\Book;
use App\Models\Log;
use App\Models\Pengembalian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Helpers\ActivityLogger; // <- import helper

class PeminjamanController extends Controller
{
   public function index()
   {
        if (auth()->user()->role === 'member') {
            $peminjaman = Peminjaman::with('book', 'user')
                ->where('user_id', auth()->id())
                ->whereIn('status', ['pending', 'rejected'])
                ->get();
        } else {
            $peminjaman = Peminjaman::with('book', 'user')
                ->whereIn('status', ['pending', 'rejected'])
                ->get();
        }

        return view('peminjaman.index', compact('peminjaman'));
   }

   public function store(Request $request)
   {
        $request->validate([
            'book_id' => 'required|exists:tbl_books,id',
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|email|max:255',
        ]);

        $book = Book::findOrFail($request->book_id);

        if ($book->stok < 1) {
            return back()->with('error', 'Stok buku tidak tersedia.');
        }

        $peminjaman = Peminjaman::create([
            'user_id' => auth()->id(),
            'book_id' => $book->id,
            'nama_lengkap' => $request->nama_lengkap,
            'email' => $request->email,
            'tanggal_pinjam' => now(),
            'tanggal_kembali' => now()->addDays(7),
            'status' => 'pending',
        ]);

        // --- LOG ACTIVITY ---
        ActivityLogger::log(
            'Ajukan Peminjaman Buku',
            $peminjaman,
            'Buku: ' . $book->judul . ', Status: pending'
        );

        return back()->with('success', 'Pengajuan peminjaman berhasil diajukan, menunggu konfirmasi petugas.');
   }

   public function konfirmasi(Request $request, $id)
   {
        $peminjaman = Peminjaman::findOrFail($id);
        $status = $request->input('status');

        if ($peminjaman->status !== 'pending') {
            return back()->with('error', 'Pengajuan sudah diproses sebelumnya.');
        }

        if ($status === 'approved') {
            $book = Book::findOrFail($peminjaman->book_id);

            if ($book->stok < 1) {
                return back()->with('error', 'Stok buku tidak tersedia.');
            }

            $book->decrement('stok');
            $peminjaman->update(['status' => 'approved']);

            // --- LOG ACTIVITY ---
            ActivityLogger::log(
                'Setujui Peminjaman Buku',
                $peminjaman,
                'Buku: ' . $book->judul . ', Status: approved'
            );

            try {
                Mail::to($peminjaman->email)->send(new PeminjamanDisetujuiMail($peminjaman));
            } catch (\Exception $e) {
                return back()->with('error', 'Pengajuan disetujui, tapi gagal mengirim email: ' . $e->getMessage());
            }

            return back()->with('success', 'Pengajuan disetujui, email dikirim.');
        }

        if ($status === 'rejected') {
            $peminjaman->update(['status' => 'rejected']);

            // --- LOG ACTIVITY ---
            ActivityLogger::log(
                'Tolak Peminjaman Buku',
                $peminjaman,
                'Buku: ' . $peminjaman->book->judul . ', Status: rejected'
            );

            return back()->with('success', 'Pengajuan ditolak.');
        }

        return back()->with('error', 'Status tidak dikenali.');
   }

   public function kembalikan($id)
   {
        $peminjaman = Peminjaman::findOrFail($id);

        if (!in_array($peminjaman->status, ['approved', 'dipinjam'])) {
            return back()->with('error', 'Status tidak valid untuk pengembalian.');
        }

        $tanggalKembali = Carbon::now();
        $jatuhTempo = Carbon::parse($peminjaman->tanggal_kembali);

        $denda = 0;
        if ($tanggalKembali->gt($jatuhTempo)) {
            $denda = $tanggalKembali->diffInDays($jatuhTempo) * 1000;
        }

        $peminjaman->update([
            'status' => 'dikembalikan',
            'tanggal_kembali' => $tanggalKembali,
        ]);

        if ($peminjaman->book) {
            $peminjaman->book->increment('stok');
        }

        Pengembalian::create([
            'peminjaman_id' => $peminjaman->id_peminjaman,
            'tanggal_pengembalian' => $tanggalKembali,
            'denda' => $denda,
            'status_denda' => $denda > 0 ? 'belum' : 'lunas',
        ]);

        // --- LOG ACTIVITY ---
        ActivityLogger::log(
            'Kembalikan Buku',
            $peminjaman,
            'Buku: ' . $peminjaman->book->judul . ', Denda: ' . $denda
        );

        return back()->with('success', 'Buku berhasil dikembalikan.');
   }

   public function destroy($id)
   {
        $peminjaman = Peminjaman::findOrFail($id);

        if (auth()->user()->role === 'member') {
            if ($peminjaman->status !== 'dikembalikan') {
                return back()->with('error', 'Anda hanya dapat menghapus peminjaman yang sudah dikembalikan.');
            }
        } else {
            if ($peminjaman->status !== 'rejected') {
                return back()->with('error', 'Hanya dapat menghapus peminjaman yang sudah ditolak.');
            }
        }

        $peminjaman->delete();

        // --- LOG ACTIVITY ---
        ActivityLogger::log(
            'Hapus Peminjaman',
            $peminjaman,
            'Buku: ' . $peminjaman->book->judul . ', Status sebelum hapus: ' . $peminjaman->status
        );

        return back()->with('success', 'Data peminjaman berhasil dihapus.');
   }

   public function clearAll()
   {
        if (auth()->user()->role === 'member') {
            return back()->with('error', 'Akses ditolak.');
        }

        \App\Models\Peminjaman::truncate();

        // --- LOG ACTIVITY ---
        ActivityLogger::log(
            'Hapus Semua Peminjaman',
            null,
            'Semua data peminjaman dihapus oleh admin'
        );

        return back()->with('success', 'Semua data peminjaman berhasil dihapus.');
   }

   public function riwayat()
   {
        $userId = auth()->id();

        $peminjaman = \App\Models\Peminjaman::where('user_id', $userId)
            ->with('book')
            ->orderBy('tanggal_pinjam', 'desc')
            ->get();

        $pengembalian = \App\Models\Pengembalian::whereHas('peminjaman', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })
        ->with('peminjaman.book')
        ->orderBy('tanggal_kembali', 'desc')
        ->get();

        $denda = \App\Models\Denda::where('user_id', $userId)
            ->with(['pengembalian.peminjaman.book'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('riwayat.index', compact('peminjaman', 'pengembalian', 'denda'));
   }
}
