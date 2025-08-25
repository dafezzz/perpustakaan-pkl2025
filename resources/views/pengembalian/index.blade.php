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
            <table class="table table-bordered table-hover table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>#ID Peminjaman</th>
                        <th>ID User</th>
                        <th>Nama User</th>
                        <th>Email</th>
                        <th>Judul Buku</th>
                        <th>Tanggal Peminjaman</th>
                        <th>Jatuh Tempo</th>
                        
                 
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($peminjaman as $p)
                        @php
                            $tglKembali = \Carbon\Carbon::parse($p->tanggal_kembali);
                            $tglSekarang = \Carbon\Carbon::now();
                            $denda = $tglSekarang->gt($tglKembali) ? $tglSekarang->diffInDays($tglKembali)*2000 : 0;
                        @endphp
                        <tr>
                            <td>{{ $p->id_peminjaman }}</td>
                            <td>{{ $p->user->id }}</td>
                            <td>{{ $p->user->name }}</td>
                            <td>{{ $p->user->email }}</td>
                            <td>{{ $p->book->judul }}</td>
                            <td>{{ $p->tanggal_pinjam }}</td>
                            <td>{{ $p->tanggal_kembali }}</td>
                           
                                
                            </td>
                            
                            <td>
                                @if(in_array(auth()->user()->role, ['resident','petugas']) && $p->status=='approved')
                                    <form action="{{ route('pengembalian.kembali', $p->id_peminjaman) }}" method="POST" onsubmit="return confirm('Yakin ingin mengembalikan buku ini?')">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm">Kembalikan</button>
                                    </form>
                                @else
                                    <span class="text-muted">Tidak bisa mengembalikan</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center">Tidak ada buku siap dikembalikan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
