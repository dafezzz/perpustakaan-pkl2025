@extends('layouts.app')

@section('content')
<div class="container py-4">

    <!-- Hero Header -->
    <div class="hero-header position-relative overflow-hidden mb-5">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="fw-bold text-primary display-5">Perpustakaan dalam Genggaman</h1>
                <p class="text-muted fs-5">
                    Akses buku, ajukan peminjaman, dan kelola koleksi dengan mudah dimanapun berada.
                </p>
            </div>
            <div class="col-md-6 text-center hero-image-wrapper">
                <img src="{{ asset('cover_buku/Tanpa Judul.png') }}" alt="Perpus Mobile" class="img-fluid hero-img parallax-img">
            </div>
        </div>
    </div>

    <!-- Dashboard Menu -->
    <div class="row g-4 mb-5">
        <div class="col-md-6">
            <div class="card shadow-sm border-0 p-4 h-100 hover-shadow bg-gradient-blue text-white rounded-4">
                <h4 class="fw-bold">Buku Populer Minggu Ini</h4>
                <p class="mb-0">Lihat koleksi buku yang paling banyak dibaca minggu ini. Temukan rekomendasi bacaanmu!</p>
                <div class="mt-3">
                    <a href="{{ route('books.index') }}" class="btn btn-light btn-sm">Lihat Buku</a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm border-0 p-4 h-100 hover-shadow bg-gradient-blue text-white rounded-4">
                <h4 class="fw-bold">Motivasi Membaca</h4>
                <p class="mb-0">“Membaca adalah jendela dunia.” Jangan lewatkan tips dan motivasi untuk menambah koleksi bacaanmu setiap hari.</p>
                <div class="mt-3">
                    <a href="#" class="btn btn-light btn-sm">Baca Sekarang</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistik Tahunan -->
    
  @unless(auth()->user()->role === 'member')
<section class="realtime-section mb-5">
    <h2 class="fw-bold mb-4 text-center">📊 Grafik Peminjaman</h2>

    <div class="card shadow-sm border-0 rounded-4 p-4">
        <canvas id="myChart"></canvas>

       
    </div>
</section>
@endunless

    <!-- Kontak Kami Section -->
    <section class="contact-section py-5">
        <h2 class="text-center fw-bold mb-5">Kontak Kami</h2>
        <div class="row g-5">
            <!-- Form Kontak -->
            <div class="col-md-6">
                <form action="{{ route('contact.send') }}" method="POST" class="contact-form p-4 rounded-4 shadow-sm bg-light">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold">Nama</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Nama lengkap" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold">Email</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="email@example.com" required>
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label fw-semibold">Pesan</label>
                        <textarea class="form-control" id="message" name="message" rows="5" placeholder="Tulis pesan Anda..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Kirim Pesan</button>
                </form>
            </div>

            <!-- Info Kontak -->
            <div class="col-md-6">
                <div class="contact-info p-4 rounded-4 shadow-sm bg-white">
                    <h4 class="fw-bold mb-4">Info Kami</h4>
                    <p><i class="fas fa-map-marker-alt me-2"></i>Kiaracondong No.191, Kota Bandung</p>
                    <p><i class="fas fa-envelope me-2"></i>alibamadaveiz2@gmail.com</p>
                    <p><i class="fas fa-phone me-2"></i>+62 895 0860 7809</p>

                    <h5 class="fw-semibold mt-4 mb-3">Media Sosial</h5>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-decoration-none text-primary"><i class="fab fa-facebook fa-lg"></i></a>
                        <a href="#" class="text-decoration-none text-info"><i class="fab fa-twitter fa-lg"></i></a>
                        <a href="#" class="text-decoration-none text-danger"><i class="fab fa-instagram fa-lg"></i></a>
                        <a href="#" class="text-decoration-none text-primary"><i class="fab fa-linkedin fa-lg"></i></a>
                    </div>

                    <div class="map mt-4">
                        <iframe 
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3960.123456789!2d107.6440!3d-6.9180!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68e8f123456789%3A0xabcdef123456789!2sJalan%20Lebak%2C%20Kiaracondong%2C%20Bandung!5e0!3m2!1sen!2sid!4v1690000000000!5m2!1sen!2sid" 
                            width="100%" 
                            height="250" 
                            style="border:0; border-radius:12px;" 
                            allowfullscreen="" 
                            loading="lazy">
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>

<style>
.hover-shadow { transition: transform 0.2s, box-shadow 0.2s; }
.hover-shadow:hover { transform: translateY(-3px); box-shadow: 0 5px 20px rgba(0,0,0,0.1); }

.bg-gradient-blue { background: linear-gradient(135deg, #4e73df, #224abe); }
.hero-header { background: #f8f9fa; padding: 50px 0; }
.hero-img { width: 100%; max-width: 500px; border-radius: 50% 10% 50% 10%; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }

.contact-section { background:#f1f5f9; }
.contact-form input, .contact-form textarea { border-radius:12px; }
.contact-info i { color:#4e73df; }

.realtime-section h2 { font-size:2rem; }

.parallax-img { transition: transform 0.2s; }

.table-hover tbody tr:hover { background-color: #f1f5ff; }

.bg-gradient-blue { color: #fff; font-weight: bold; }
</style>

<script>
window.addEventListener('scroll', function() {
    const img = document.querySelector('.parallax-img');
    if(img){
        const offset = window.pageYOffset;
        img.style.transform = 'translateY(' + offset * 0.2 + 'px)';
    }
});
</script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('myChart');

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: @json($months),
        datasets: [{
            label: 'Peminjaman 2025',
            data: @json($peminjamanData),
            backgroundColor: 'rgba(78, 115, 223, 0.7)',
            borderColor: 'rgba(78, 115, 223, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    precision:0
                }
            }
        }
    }
});
</script>
@endsection
