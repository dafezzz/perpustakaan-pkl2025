@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-4">Pengembalian Buku</h3>

    {{-- Card ringkasan --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h5>Total Pinjaman</h5>
                    <h3>{{ $peminjaman->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <h5>Buku Terlambat</h5>
                    <h3>{{ $peminjaman->filter(fn($p) => \Carbon\Carbon::now()->gt(\Carbon\Carbon::parse($p->tanggal_kembali)))->count() }}</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- Alert --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <!-- Filter pencarian -->
    <form method="GET" action="{{ route('pengembalian.index') }}" class="mb-3 row g-2">
        <div class="col-md-4">
            <input type="text" name="nama" class="form-control" placeholder="Cari nama peminjam..." value="{{ request('nama') }}">
        </div>
        <div class="col-md-4">
            <input type="text" name="judul" class="form-control" placeholder="Cari judul buku..." value="{{ request('judul') }}">
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary w-100">Cari</button>
        </div>
        <div class="col-md-2">
            <a href="{{ route('pengembalian.index') }}" class="btn btn-secondary w-100">Reset</a>
        </div>
    </form>

    {{-- Tabel pengembalian --}}
    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover table-striped text-nowrap">
                <thead class="table-primary">
                    <tr>
                        <th style="width: 5%;">#ID Peminjaman</th>
                        <th style="width: 5%;">ID User</th>
                        <th style="width: 15%;">Nama User</th>
                        <th style="width: 20%;">Email</th>
                        <th style="width: 15%;">Judul Buku</th>
                        <th style="width: 10%;">Tanggal Peminjaman</th>
                        <th style="width: 10%;">Jatuh Tempo</th>
                        <th style="width: 20%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($peminjaman as $p)
                        @php
                            $tglKembali = \Carbon\Carbon::parse($p->tanggal_kembali);
                            $tglSekarang = \Carbon\Carbon::now();
                            $denda = $tglSekarang->gt($tglKembali) ? $tglSekarang->diffInDays($tglKembali) * 2000 : 0;
                        @endphp
                        <tr>
                            <td>{{ $p->id_peminjaman }}</td>
                            <td>{{ $p->user->id }}</td>
                            <td>{{ $p->user->name }}</td>
                            <td style="max-width: 200px; word-break: break-word;">{{ $p->user->email }}</td>
                            <td>{{ $p->book->judul }}</td>
                            <td>{{ $p->tanggal_pinjam }}</td>
                            <td>{{ $p->tanggal_kembali }}</td>
                            <td>
                                @if(in_array(auth()->user()->role, ['resident','petugas']) && $p->status=='approved')
                                    <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modal{{ $p->id_peminjaman }}">
                                        Konfirmasi
                                    </button>

                                    <!-- Modal -->
                                    <div class="modal fade" id="modal{{ $p->id_peminjaman }}" tabindex="-1" aria-labelledby="modalLabel{{ $p->id_peminjaman }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="modalLabel{{ $p->id_peminjaman }}">Konfirmasi Pengembalian</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    @if($denda == 0)
                                                        <p>Apakah Anda yakin ingin mengembalikan buku <strong>{{ $p->book->judul }}</strong> dari <strong>{{ $p->user->name }}</strong>?</p>
                                                    @else
                                                        <p>Buku <strong>{{ $p->book->judul }}</strong> dari <strong>{{ $p->user->name }}</strong> terlambat <strong>{{ $tglSekarang->diffInDays($tglKembali) }} hari</strong>.</p>
                                                        <p>Total denda: <strong>Rp {{ number_format($denda,0,',','.') }}</strong></p>
                                                        <p>Silakan lakukan pembayaran sebelum mengembalikan buku.</p>
                                                    @endif
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    @if($denda == 0)
                                                        <form action="{{ route('pengembalian.kembali', $p->id_peminjaman) }}" method="POST">
                                                            @csrf
                                                            <button type="submit" class="btn btn-success">Konfirmasi Pengembalian</button>
                                                        </form>
                                                    @else
                                                        <a href="{{ route('denda.bayar', $p->id_peminjaman) }}" class="btn btn-danger">Bayar Denda</a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted">Tidak bisa mengembalikan</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">Tidak ada buku siap dikembalikan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
