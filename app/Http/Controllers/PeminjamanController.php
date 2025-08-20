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

class PeminjamanController extends Controller
{
    public function index()
    {
        if (auth()->user()->role === 'member') {
            $peminjaman = Peminjaman::with('book', 'user')
                ->where('user_id', auth()->id())
                ->get();
        } else {
            $peminjaman = Peminjaman::with('book', 'user')->get();
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

        Peminjaman::create([
            'user_id' => auth()->id(),
            'book_id' => $book->id,
            'nama_lengkap' => $request->nama_lengkap,
            'email' => $request->email,
            'tanggal_pinjam' => now(),
            'tanggal_kembali' => now()->addDays(7),
            'status' => 'pending',
        ]);

        return back()->with('success', 'Pengajuan peminjaman berhasil diajukan, menunggu konfirmasi petugas.');
    }
public function konfirmasi(Request $request, $id)
{
    // cari berdasarkan id_peminjaman (sudah otomatis karena di model di-set)
    $peminjaman = Peminjaman::findOrFail($id);

    // ambil status baru dari request
    $status = $request->input('status');

    // hanya bisa diproses kalau masih pending
    if ($peminjaman->status !== 'pending') {
        return back()->with('error', 'Pengajuan sudah diproses sebelumnya.');
    }

    if ($status === 'approved') {
        $book = Book::findOrFail($peminjaman->book_id);

        if ($book->stok < 1) {
            return back()->with('error', 'Stok buku tidak tersedia.');
        }

        // kurangi stok
        $book->decrement('stok');

        // ubah status
        $peminjaman->update(['status' => 'approved']);

        
        // kirim email
        try {
            Mail::to($peminjaman->email)->send(new PeminjamanDisetujuiMail($peminjaman));
        } catch (\Exception $e) {
            return back()->with('error', 'Pengajuan disetujui, tapi gagal mengirim email: ' . $e->getMessage());
        }

        return back()->with('success', 'Pengajuan disetujui, email dikirim.');
    }

    if ($status === 'rejected') {
        $peminjaman->update(['status' => 'rejected']);

        return back()->with('success', 'Pengajuan ditolak.');
    }

    return back()->with('error', 'Status tidak dikenali.');
}


    public function kembalikan($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
    
        if ($peminjaman->status !== 'approved' && $peminjaman->status !== 'dipinjam') {
            return back()->with('error', 'Status tidak valid untuk pengembalian.');
        }
    
        $tanggalKembali = Carbon::now();
        $jatuhTempo = Carbon::parse($peminjaman->tanggal_kembali);
    
        if ($tanggalKembali->gte($jatuhTempo)) {
            $terlambat = $tanggalKembali->diffInDays($jatuhTempo);
            $denda = $terlambat * 1000;
        } else {
            $denda = 0;
        }
    
        $peminjaman->update([
            'tanggal_kembali' => $tanggalKembali,
            'status' => 'dikembalikan',
        ]);
    
        $book = Book::find($peminjaman->book_id);
        if ($book) {
            $book->increment('stok');
        }
    
        Pengembalian::create([
            'peminjaman_id' => $peminjaman->id_peminjaman,
            'tanggal_kembali' => $tanggalKembali,
            'denda' => $denda,
        ]);
    
        Log::create([
            'user_id' => $peminjaman->user_id,
            'book_id' => $peminjaman->book_id,
            'peminjaman_id' => $peminjaman->id_peminjaman,
            'keterangan' => 'Buku dikembalikan oleh ' . auth()->user()->name,
        ]);
    
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

        return back()->with('success', 'Data peminjaman berhasil dihapus.');
    }

    public function clearAll()
    {
        if (auth()->user()->role === 'member') {
            return back()->with('error', 'Akses ditolak.');
        }

        \App\Models\Peminjaman::truncate();
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
