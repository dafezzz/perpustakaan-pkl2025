<!DOCTYPE html>
<html>
<head>
    <title>Daftar Buku - Perpustakaan Laravel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-5 bg-light">

<div class="container">
    <h1 class="mb-4 text-center fw-bold">Daftar Buku Perpustakaan</h1>

    @if(session('success'))
        <div class="alert alert-success text-center">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger text-center">{{ session('error') }}</div>
    @endif

    <table class="table table-bordered table-striped table-hover shadow-sm">
        <thead class="table-dark text-center">
            <tr>
                <th>No</th>
                <th>Judul</th>
                <th>Kategori</th>
                <th>Stok</th>
                <th>Penerbit</th>
                <th>Tahun Terbit</th>
                <th>Pengarang</th>
                <th>Harga Peminjaman</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($books as $no => $book)
            <tr>
                <td class="text-center">{{ $no + 1 }}</td>
                <td>{{ $book->judul }}</td>
                <td>{{ $book->kategori }}</td>
                <td class="text-center">{{ $book->stok }}</td>
                <td>{{ $book->penerbit }}</td>
                <td class="text-center">{{ $book->tahun_terbit }}</td>
                <td>{{ $book->pengarang }}</td>
                <td>Rp{{ number_format($book->harga_peminjaman, 0, ',', '.') }}</td>
                <td class="text-center">
                    @if($book->stok > 0)
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalAjukan-{{ $book->id }}">
                            Ajukan Peminjaman
                        </button>
                    @else
                        <span class="badge bg-danger">Stok Habis</span>
                    @endif
                </td>
            </tr>

            <!-- Modal Form Pengajuan -->
            <div class="modal fade" id="modalAjukan-{{ $book->id }}" tabindex="-1" aria-labelledby="modalAjukanLabel-{{ $book->id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <form action="{{ route('peminjaman.store') }}" method="POST" class="modal-content">
                        @csrf
                        <input type="hidden" name="book_id" value="{{ $book->id }}">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title" id="modalAjukanLabel-{{ $book->id }}">Ajukan Peminjaman Buku</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p><strong>Judul Buku:</strong> {{ $book->judul }}</p>
                            <div class="mb-2">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" name="nama" class="form-control" required>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Alamat</label>
                                <textarea name="alamat" class="form-control" rows="2" required></textarea>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">No HP</label>
                                <input type="text" name="no_hp" class="form-control" required>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Catatan (Opsional)</label>
                                <textarea name="catatan" class="form-control" rows="2"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary btn-sm">Ajukan Sekarang</button>
                        </div>
                    </form>
                </div>
            </div>
            @empty
            <tr>
                <td colspan="9" class="text-center">Tidak ada data buku.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
