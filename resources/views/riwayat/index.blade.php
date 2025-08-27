@extends('layouts.app')

@section('content')
<div class="container py-4">

    {{-- Judul --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark"> Riwayat Perpustakaan</h2>
    </div>

    {{-- Navigasi Tab --}}
    <ul class="nav nav-pills mb-3" id="riwayatTab" role="tablist">
        <li class="nav-item">
            <button class="nav-link active" id="peminjaman-tab" data-bs-toggle="tab" data-bs-target="#peminjaman" type="button">
                <i class="bi bi-journal-plus"></i> Peminjaman
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link" id="pengembalian-tab" data-bs-toggle="tab" data-bs-target="#pengembalian" type="button">
                <i class="bi bi-journal-check"></i> Pengembalian
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link" id="denda-tab" data-bs-toggle="tab" data-bs-target="#denda" type="button">
                <i class="bi bi-cash-coin"></i> Denda
            </button>
        </li>
    </ul>

    {{-- Konten Tab --}}
    <div class="tab-content" id="riwayatTabContent">

        {{-- =================== PEMINJAMAN =================== --}}
        <div class="tab-pane fade show active" id="peminjaman" role="tabpanel">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-primary text-white fw-bold d-flex justify-content-between align-items-center">
                    Daftar Peminjaman Buku
                    <input type="text" class="form-control w-25" id="searchPeminjaman" placeholder="🔍 Cari...">
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover align-middle text-center mb-0" id="tablePeminjaman">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>ID User</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Buku</th>
                                <th>Tgl Pinjam</th>
                                <th>Jatuh Tempo</th>
                                <th>Status</th>
                                <th>Detail</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($peminjaman as $i => $item)
                                <tr>
                                    <td>{{ $i+1 }}</td>
                                    <td>{{ $item->user->id ?? '-' }}</td>
                                    <td>{{ $item->user->name ?? '-' }}</td>
                                    <td>{{ $item->user->email ?? '-' }}</td>
                                    <td>{{ $item->book->judul ?? '-' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d M Y') }}</td>
                                    <td>{{ $item->tanggal_kembali ? \Carbon\Carbon::parse($item->tanggal_kembali)->format('d M Y') : '-' }}</td>
                                    <td>
                                        @if($item->status == 'pending')
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @elseif($item->status == 'dipinjam')
                                            <span class="badge bg-info text-dark">Dipinjam</span>
                                        @else
                                            <span class="badge bg-success">Selesai</span>
                                        @endif
                                    </td>
                                    <td>
                                         <button class="btn btn-sm btn-outline-success rounded-circle"  data-bs-toggle="modal" data-bs-target="#detailPeminjaman{{ $item->id }}">
                                            <i class="bi bi-eye">Detail</i>
                                        </button>
                                    </td>
                                </tr>

                                {{-- Modal Detail Peminjaman --}}
                                <div class="modal fade" id="detailPeminjaman{{ $item->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                        <div class="modal-content shadow-lg rounded-4">
                                            <div class="modal-header bg-primary text-white">
                                                <h5 class="modal-title">Detail Peminjaman</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <ul class="list-group list-group-flush">
                                                    <li class="list-group-item"><strong>ID User:</strong> {{ $item->user->id ?? '-' }}</li>
                                                     <li class="list-group-item"><strong>ID Peminjaman:</strong> {{ $item->id_peminjaman ?? '-' }}</li>
                                                    <li class="list-group-item"><strong>Nama:</strong> {{ $item->user->name ?? '-' }}</li>
                                                    <li class="list-group-item"><strong>Email:</strong> {{ $item->user->email ?? '-' }}</li>
                                                    <li class="list-group-item"><strong>Buku:</strong> {{ $item->book->judul ?? '-' }}</li>
    
                                                    <li class="list-group-item"><strong>Tanggal Pinjam:</strong> {{ \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d M Y') }}</li>
                                                    <li class="list-group-item"><strong>Jatuh Tempo:</strong> {{ $item->tanggal_kembali ? \Carbon\Carbon::parse($item->tanggal_kembali)->format('d M Y') : '-' }}</li>
                                                    <li class="list-group-item"><strong>Status:</strong> {{ ucfirst($item->status) }}</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-3">Belum ada peminjaman.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- =================== PENGEMBALIAN =================== --}}
<div class="tab-pane fade" id="pengembalian" role="tabpanel">
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-header bg-success text-white fw-bold d-flex justify-content-between align-items-center">
            Daftar Pengembalian Buku
            <input type="text" class="form-control w-25" id="searchPengembalian" placeholder="🔍 Cari...">
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0" id="tablePengembalian">
                    <thead class="table-light">
                        <tr class="text-center">
                            <th style="width: 5%;">No</th>
                            <th style="width: 8%;">ID User</th>
                            <th style="width: 12%;">Nama</th>
                            <th style="width: 18%;">Email</th>
                            <th style="width: 20%;">Buku</th>
                            <th style="width: 15%;"> Tgl Pengembalian</th>
                            <th style="width: 7%;">Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pengembalian as $i => $item)
                            <tr class="text-center">
                                <td>{{ $i+1 }}</td>
                                <td>{{ $item->peminjaman->user->id ?? '-' }}</td>
                                <td class="text-start">{{ $item->peminjaman->user->name ?? '-' }}</td>
                                <td class="text-start">{{ $item->peminjaman->user->email ?? '-' }}</td>
                                <td class="text-start">{{ $item->peminjaman->book->judul ?? '-' }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->tanggal_pengembalian)->format('d M Y') }}</td>
                                
<td>
                                                                
                       
                                    <button class="btn btn-sm btn-outline-success rounded-circle" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#detailPengembalian{{ $item->id_pengembalian }}">
                                        <i class="bi bi-eye">Detail</i>
                                    </button>
                                </td>
                            </tr>

                            {{-- Modal Detail Pengembalian --}}
                            <div class="modal fade" id="detailPengembalian{{ $item->id_pengembalian }}" tabindex="-1">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content shadow-lg rounded-4 border-0">
                                        <div class="modal-header bg-success text-white">
                                            <h5 class="modal-title">📖 Detail Pengembalian</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <ul class="list-group list-group-flush">
                                                <li class="list-group-item"><strong>ID User:</strong> {{ $item->peminjaman->user->id ?? '-' }}</li>
                                      <li class="list-group-item"><strong>ID Peminjaman:</strong> {{ $item->peminjaman->id_peminjaman ?? '-' }}</li>

                                                <li class="list-group-item"><strong>Nama:</strong> {{ $item->peminjaman->user->name ?? '-' }}</li>
                                                <li class="list-group-item"><strong>Email:</strong> {{ $item->peminjaman->user->email ?? '-' }}</li>
                                                <li class="list-group-item"><strong>Buku:</strong> {{ $item->peminjaman->book->judul ?? '-' }}</li>
                                                <li class="list-group-item"><strong>Tanggal Pengembalian:</strong> {{ \Carbon\Carbon::parse($item->tanggal_pengembalian)->format('d M Y') }}</li>
                                                <li class="list-group-item"><strong>Denda:</strong> Rp {{ number_format($item->denda, 0, ',', '.') }}</li>
                                                <li class="list-group-item">
                                                    <strong>Status Denda:</strong> 
                                                    @if($item->status_denda == 'belum')
                                                        <span class="badge bg-danger">Belum Lunas</span>
                                                    @else
                                                        <span class="badge bg-success">Lunas</span>
                                                    @endif
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-3">Belum ada pengembalian.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


        {{-- =================== DENDA =================== --}}
        <div class="tab-pane fade" id="denda" role="tabpanel">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-danger text-white fw-bold d-flex justify-content-between align-items-center">
                    Daftar Denda
                    <input type="text" class="form-control w-25" id="searchDenda" placeholder="🔍 Cari...">
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover align-middle text-center mb-0" id="tableDenda">
                        <thead class="table-light">
                            <tr>
                             
                                <th>ID User</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Buku</th>
                                <th>Tanggal Pengembalian</th>
                                <th>Jumlah Denda</th>
                                <th>Status</th>
                                <th>Detail</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($denda as $i => $item)
                                <tr>    <td>{{ $item->peminjaman->user->id ?? '-' }}</td>
                                    <td>{{ $item->peminjaman->user->name ?? '-' }}</td>
                                    <td>{{ $item->peminjaman->user->email ?? '-' }}</td>
                                    <td>{{ $item->peminjaman->book->judul ?? '-' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->tanggal_pengembalian)->format('d M Y') }}</td>
                                    <td class="fw-bold text-danger">Rp {{ number_format($item->denda, 0, ',', '.') }}</td>
                                    <td>
                                        @if($item->status_denda == 'belum')
                                            <span class="badge bg-danger">Belum Lunas</span>
                                        @else
                                            <span class="badge bg-success">Lunas</span>
                                        @endif
                                    </td>
                                    <td>
                                        
                                        <button class="btn btn-sm btn-outline-success rounded-circle"  data-bs-toggle="modal" data-bs-target="#detailDenda{{ $item->id }}">
                                            <i class="bi bi-eye">Detail</i>
                                        </button>
                                    </td>
                                </tr>

                                {{-- Modal Detail Denda --}}
                                <div class="modal fade" id="detailDenda{{ $item->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                        <div class="modal-content shadow-lg rounded-4">
                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title">Detail Denda</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <ul class="list-group list-group-flush">
                                                    <li class="list-group-item"><strong>ID User:</strong> {{ $item->peminjaman->user->id ?? '-' }}</li>
                                                <li class="list-group-item"><strong>ID Peminjaman:</strong> {{ $item->peminjaman->id_peminjaman ?? '-' }}</li>

                                                    <li class="list-group-item"><strong>Nama:</strong> {{ $item->peminjaman->user->name ?? '-' }}</li>
                                                    <li class="list-group-item"><strong>Email:</strong> {{ $item->peminjaman->user->email ?? '-' }}</li>
                                                    <li class="list-group-item"><strong>Buku:</strong> {{ $item->peminjaman->book->judul ?? '-' }}</li>
                                                    <li class="list-group-item"><strong>Tanggal Pengembalian:</strong> {{ \Carbon\Carbon::parse($item->tanggal_pengembalian)->format('d M Y') }}</li>
                                                    <li class="list-group-item"><strong>Denda:</strong> Rp {{ number_format($item->denda, 0, ',', '.') }}</li>

                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-3">Tidak ada denda.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- Script Search --}}
<script>
function setupSearch(inputId, tableId) {
    document.getElementById(inputId).addEventListener("keyup", function () {
        let filter = this.value.toLowerCase();
        let rows = document.querySelectorAll(`#${tableId} tbody tr`);
        rows.forEach(row => {
            row.style.display = row.innerText.toLowerCase().includes(filter) ? "" : "none";
        });
    });
}

setupSearch("searchPeminjaman", "tablePeminjaman");
setupSearch("searchPengembalian", "tablePengembalian");
setupSearch("searchDenda", "tableDenda");
</script>
@endsection
