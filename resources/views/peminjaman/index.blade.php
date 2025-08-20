
@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-4">Pengajuan Peminjaman Buku</h3>

    @if(session('success'))
    <div class="alert alert-success text-center">{{ session('success') }}</div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger text-center">{{ session('error') }}</div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger text-center">
        <ul class="mb-0">
            @foreach($errors->all() as $err)
            <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- @if(auth()->user()->role !== 'member')
    <div class="mb-3 text-end">
        <form action="{{ route('peminjaman.clear') }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus semua data peminjaman?');">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger">🗑️ Clear All</button>
        </form>
    </div>
@endif -->


    @if($peminjaman->count())
    <div class="row row-cols-1 row-cols-md-3 g-4">
        @foreach($peminjaman as $p)
        <div class="col">
            <div class="card h-100 shadow-sm">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">{{ $p->book->judul ?? 'Buku Tidak Ditemukan' }}</h5>
                    <p class="mb-1"><strong>Nama Peminjam:</strong> {{ $p->nama_lengkap ?? '-' }}</p>
                    <p class="mb-1"><strong>Email:</strong> {{ $p->email ?? '-' }}</p>
                    
                    <p class="mb-2">
                        <strong>Status:</strong>
                        @if($p->status === 'pending')
                        <span class="badge bg-warning text-dark">Pending</span>
                        @elseif($p->status === 'approved' || $p->status === 'dipinjam')
                        <span class="badge bg-success">Disetujui</span>
                        @elseif($p->status === 'rejected')
                        <span class="badge bg-danger">Ditolak</span>
                        @elseif($p->status === 'dikembalikan')
                        <span class="badge bg-secondary">Dikembalikan</span>
                        @else
                        <span class="badge bg-light text-dark">{{ ucfirst($p->status) }}</span>
                        @endif
                    </p>

                    <div class="mt-auto d-flex flex-wrap gap-2">
                        @if(auth()->user()->role !== 'member' && $p->status === 'pending')
                        <button type="button" class="btn btn-sm btn-primary flex-fill" data-bs-toggle="modal" data-bs-target="#modalKonfirmasi-{{ $p->id_peminjaman }}">
                            Konfirmasi
                        </button>
                        @endif

                        @if($p->status === 'dipinjam' && (auth()->user()->role !== 'member' || $p->user_id == auth()->id()))
                        <button type="button" class="btn btn-sm btn-success flex-fill" data-bs-toggle="modal" data-bs-target="#modalKembalikan-{{ $p->id_peminjaman }}">
                            Kembalikan
                        </button>
                        
                        @endif

                        @if(auth()->user()->role !== 'member' && $p->status === 'rejected')
                        <button type="button" class="btn btn-sm btn-danger flex-fill" data-bs-toggle="modal" data-bs-target="#modalDelete-{{ $p->id_peminjaman }}">
                            Hapus
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal Konfirmasi --}}
        <div class="modal fade" id="modalKonfirmasi-{{ $p->id_peminjaman }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <form method="POST" action="{{ route('peminjaman.konfirmasi', $p->id_peminjaman) }}">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Konfirmasi Pengajuan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            Setujui pengajuan peminjaman <strong>{{ $p->book->judul ?? '-' }}</strong> oleh <strong>{{ $p->nama_lengkap ?? '-' }}</strong>?
                            <br><small>Email: {{ $p->email ?? '-' }}</small>
                        
                        </div>
                        <div class="modal-footer">
                            <button type="submit" name="status" value="approved" class="btn btn-success">Setujui</button>
                            <button type="submit" name="status" value="rejected" class="btn btn-danger">Tolak</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Modal Kembalikan --}}
        <div class="modal fade" id="modalKembalikan-{{ $p->id_peminjaman }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <form method="POST" action="{{ route('peminjaman.kembalikan', $p->id_peminjaman) }}">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Konfirmasi Pengembalian</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            Yakin ingin mengembalikan buku <strong>{{ $p->book->judul ?? '-' }}</strong>?
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">Ya, Kembalikan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Modal Delete --}}
        <div class="modal fade" id="modalDelete-{{ $p->id_peminjaman }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <form method="POST" action="{{ route('peminjaman.destroy', $p->id_peminjaman) }}">
                    @csrf
                    @method('DELETE')
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Hapus Data Peminjaman</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            Apakah Anda yakin ingin menghapus peminjaman buku <strong>{{ $p->book->judul ?? '-' }}</strong> oleh <strong>{{ $p->nama_lengkap ?? '-' }}</strong>?
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        @endforeach
    </div>
    @else
    <div class="alert alert-info text-center">Belum ada pengajuan peminjaman.</div>
    @endif

</div>
@endsection

<style>
.card {
    transition: transform 0.2s, box-shadow 0.2s;
}
.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.15);
}
</style>