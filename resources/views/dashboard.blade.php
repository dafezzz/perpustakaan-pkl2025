@extends('layouts.app')

@section('content')
<div class="container py-4">

    <!-- Heading -->
    <div class="card shadow border-0 rounded-4 text-center p-5 welcome-glow mb-4">
        <h2 class="fw-bold mb-2 glowing-text">Selamat Datang, {{ auth()->user()->name }}!</h2>
        <p class="text-light mb-0">
            @if(auth()->user()->role === 'resident')
                Anda login sebagai <strong>Admin</strong>.
            @elseif(auth()->user()->role === 'petugas')
                Anda login sebagai <strong>Petugas</strong>.
            @elseif(auth()->user()->role === 'member')
                Anda login sebagai <strong>Member</strong>.
            @endif
        </p>
    </div>

    <!-- Grid Dashboard -->
    <div class="row g-4">

        @if(auth()->user()->role === 'resident')
            <!-- Admin dapat kelola admin, petugas, buku -->
            <div class="col-md-4">
                <a href="{{ route('resident.index') }}" class="text-decoration-none">
                    <div class="card shadow-sm border-0 text-center p-4 h-100 hover-glow rounded-4">
                        <i class="fas fa-user-graduate fa-3x text-primary mb-3"></i>
                        <h5 class="fw-semibold text-dark mb-1">Kelola Admin</h5>
                        <p class="text-muted small mb-0">Lihat dan kelola data admin dengan mudah.</p>
                    </div>
                </a>
            </div>

            <div class="col-md-4">
                <a href="{{ route('petugas.index') }}" class="text-decoration-none">
                    <div class="card shadow-sm border-0 text-center p-4 h-100 hover-glow rounded-4">
                        <i class="fas fa-user-shield fa-3x text-success mb-3"></i>
                        <h5 class="fw-semibold text-dark mb-1">Kelola Petugas</h5>
                        <p class="text-muted small mb-0">Atur data petugas dengan efisien.</p>
                    </div>
                </a>
            </div>
        @endif

        @if(auth()->user()->role === 'resident' || auth()->user()->role === 'petugas')
            <!-- Admin dan petugas dapat kelola buku -->
            <div class="col-md-4">
                <a href="{{ route('books.index') }}" class="text-decoration-none">
                    <div class="card shadow-sm border-0 text-center p-4 h-100 hover-glow rounded-4">
                        <i class="fas fa-book fa-3x text-warning mb-3"></i>
                        <h5 class="fw-semibold text-dark mb-1">Kelola Buku</h5>
                        <p class="text-muted small mb-0">Tambah dan kelola koleksi buku.</p>
                    </div>
                </a>
            </div>
        @endif

        @if(auth()->user()->role === 'member')
            <!-- Member dapat akses peminjaman -->
            <div class="col-md-4">
                <a href="{{ route('peminjaman.index') }}" class="text-decoration-none">
                    <div class="card shadow-sm border-0 text-center p-4 h-100 hover-glow rounded-4">
                        <i class="fas fa-book-reader fa-3x text-info mb-3"></i>
                        <h5 class="fw-semibold text-dark mb-1">Peminjaman Buku</h5>
                        <p class="text-muted small mb-0">Lihat dan kelola peminjaman buku Anda.</p>
                    </div>
                </a>
            </div>

            <!-- Member dapat lihat daftar buku -->
            <div class="col-md-4">
                <a href="{{ route('books.index') }}" class="text-decoration-none">
                    <div class="card shadow-sm border-0 text-center p-4 h-100 hover-glow rounded-4">
                        <i class="fas fa-book fa-3x text-warning mb-3"></i>
                        <h5 class="fw-semibold text-dark mb-1">Lihat Buku</h5>
                        <p class="text-muted small mb-0">Telusuri koleksi buku yang tersedia di perpustakaan.</p>
                    </div>
                </a>
            </div>

            <!-- Motivasi Membaca -->
            <div class="col-md-4">
                <div class="card shadow-sm border-0 text-center p-4 h-100 hover-glow rounded-4">
                    <i class="fas fa-lightbulb fa-3x text-success mb-3"></i>
                    <h5 class="fw-semibold text-dark mb-1">Tetap Semangat Membaca!</h5>
                    <p class="text-muted small mb-0">Buku adalah jendela dunia. Pinjam dan baca setiap hari untuk masa depanmu.</p>
                </div>
            </div>
        @endif

    </div>

</div>

<!-- Style -->
<style>
.welcome-glow {
    background: linear-gradient(135deg, #3f51b5, #5c6bc0);
    color: white;
    border-radius: 1rem;
}

.glowing-text {
    font-weight: 700;
    font-size: 2rem;
    color: white;
    animation: glow-text 3s ease-in-out infinite;
}

@keyframes glow-text {
    0% { text-shadow: 0 0 5px rgba(255,255,255,0.5); }
    50% { text-shadow: 0 0 20px rgba(255,255,255,0.8); }
    100% { text-shadow: 0 0 5px rgba(255,255,255,0.5); }
}

.hover-glow {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.hover-glow:hover {
    transform: translateY(-5px) scale(1.03);
    box-shadow: 0 0 25px rgba(63, 81, 181, 0.4);
}
</style>
@endsection
