@extends('layouts.app')

@section('content')

@if(session('success'))
    <div class="alert alert-success text-center mx-auto mt-3" style="max-width: 500px;">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger text-center mx-auto mt-3" style="max-width: 500px;">
        {{ session('error') }}
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger text-center mx-auto mt-3" style="max-width: 500px;">
        <ul class="mb-0">
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="container">

    <div class="d-flex align-items-center justify-content-between mb-4">
        <h1 class="h4 mb-0 text-gray-800">Daftar Buku</h1>
        @if(auth()->user()->role !== 'member')
        <a href="{{ url('/books/create') }}" class="btn btn-sm btn-primary">
            <i class="fas fa-plus"></i> Tambah
        </a>
        @endif
    </div>

    @if ($books->count())
        <div class="row row-cols-1 row-cols-md-4 g-4">
            @foreach ($books as $book)
                <div class="col">
                    <div class="card h-100 shadow-sm hover-shadow transition" style="cursor: pointer;">
                        @if ($book->cover)
                            <img 
                                src="{{ asset('cover_buku/'.$book->cover) }}" 
                                alt="{{ $book->judul }}" 
                                class="card-img-top img-fluid"
                                style="max-height: 300px; object-fit: contain;"
                            >
                        @else
                            <img 
                                src="{{ asset('default_cover.png') }}" 
                                alt="{{ $book->judul }}" 
                                class="card-img-top img-fluid"
                                style="max-height: 300px; object-fit: contain;"
                            >
                        @endif

                        <div class="card-body d-flex flex-column">
                            <p class="mb-1"><strong>Judul:</strong> {{ $book->judul }}</p>
                            <p class="mb-1"><strong>Kategori:</strong> {{ $book->kategori }}</p>
                            <p class="mb-1"><strong>Stok:</strong>
                                @if($book->stok > 5)
                                    <span class="badge bg-success">{{ $book->stok }}</span>
                                @elseif($book->stok > 0)
                                    <span class="badge bg-warning text-dark">{{ $book->stok }}</span>
                                @else
                                    <span class="badge bg-danger">Habis</span>
                                @endif
                            </p>
                            <p class="mb-1"><strong>Penerbit:</strong> {{ $book->penerbit }}</p>
                            <p class="mb-1"><strong>Tahun:</strong> {{ $book->tahun_terbit }}</p>
                            <p class="mb-1"><strong>Pengarang:</strong> {{ $book->pengarang }}</p>

               {{-- Lokasi Rak --}}
@if($book->rak)
    <div class="border-top mt-2 pt-2">
        <p class="mb-1">
            <strong>Lokasi Rak:</strong> 
            {{ $book->rak->kode_rak }} - {{ $book->rak->nama_rak }}
        </p>
    </div>
@else
    <p class="text-muted small mt-2"><em>Belum ada rak</em></p>
@endif

                            <div class="mt-auto d-flex justify-content-between">
                                @if(auth()->user()->role !== 'member')
                                    <a href="{{ url('/books/'.$book->id.'/edit') }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#modalDelete-{{ $book->id }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                @endif

                                {{-- Semua role bisa mengajukan peminjaman dengan modal form manual --}}
                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalAjukan-{{ $book->id }}">
                                    <i class="fas fa-book"></i> Pinjam
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Modal Delete --}}
                @if(auth()->user()->role !== 'member')
                <div class="modal fade" id="modalDelete-{{ $book->id }}">
                    <div class="modal-dialog modal-sm modal-dialog-centered">
                        <div class="modal-content">
                            <form action="{{ url('/books/'.$book->id) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <div class="modal-header text-center">
                                    <h5 class="modal-title w-100">Konfirmasi Hapus</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body text-center">
                                    Apakah Anda yakin ingin menghapus buku <strong>{{ $book->judul }}</strong>?
                                </div>
                                <div class="modal-footer justify-content-center gap-2">
                                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-danger btn-sm">Ya, Hapus</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Modal Ajukan Peminjaman --}}
                <div class="modal fade" id="modalAjukan-{{ $book->id }}">
                    <div class="modal-dialog">
                        <form action="{{ route('peminjaman.store') }}" method="POST" class="modal-content">
                            @csrf
                            <input type="hidden" name="book_id" value="{{ $book->id }}">
                            <input type="hidden" name="nama_lengkap" value="{{ auth()->user()->name }}">
                            <input type="hidden" name="email" value="{{ auth()->user()->email }}">

                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title">Konfirmasi Pengajuan Peminjaman</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <p>Apakah Anda yakin ingin mengajukan peminjaman buku berikut?</p>
                                <ul>
                                    <li><strong>Judul:</strong> {{ $book->judul }}</li>
                                    <li><strong>Nama:</strong> {{ auth()->user()->name }}</li>
                                    <li><strong>Email:</strong> {{ auth()->user()->email }}</li>
                                </ul>
                            </div>
                            
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary btn-sm">Ajukan</button>
                            </div>
                        </form>
                    </div>
                </div>
                {{-- End Modal Ajukan Peminjaman --}}
            @endforeach
        </div>
    @else
        <div class="alert alert-info text-center">
            Tidak ada data buku.
        </div>
    @endif
</div>

<style>
.card {
    transition: transform 0.2s, box-shadow 0.2s;
}
.card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const toastElList = [].slice.call(document.querySelectorAll('.toast'));
    toastElList.map(function (toastEl) {
        return new bootstrap.Toast(toastEl, { delay: 3000 }).show();
    });
});
</script>
@endsection
