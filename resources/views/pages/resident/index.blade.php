@extends('layouts.app')

@section('content')
<div class="container">

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif


    <!-- Heading dan tombol tambah -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h1 class="h4 mb-0 text-gray-800">Data admin</h1>
        <a href="{{ url('/resident/create') }}" class="btn btn-sm btn-primary">
            <i class="fas fa-plus"></i> Tambah
        </a>
    </div>

    <!-- Table responsif -->
    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-primary">
                        <tr>
                            <th width="10%">No</th>
                            <th width="25%">Name</th>
                            <th width="25%">Email</th>
                            <th width="20%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($residents as $item)
                            <tr>
                            <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->email }}</td>
                                <td class="text-center">
                                    <a href="{{ url('/resident/'.$item->id.'/edit') }}" class="btn btn-sm btn-warning me-1">
                                        <i class="fas fa-pen"></i> Edit
                                    </a>
                                    <!-- Tombol hapus trigger modal -->
                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#modalDelete-{{ $item->id }}">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>

                                    <!-- Modal Delete -->
                                    <div class="modal fade" id="modalDelete-{{ $item->id }}" tabindex="-1" aria-labelledby="modalDeleteLabel-{{ $item->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                        <form action="{{ route('resident.destroy', $item->id) }}" method="post">

                                                @csrf
                                                @method('DELETE')
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="modalDeleteLabel-{{ $item->id }}">Konfirmasi Hapus</h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Apakah anda yakin ingin menghapus data <strong>{{ $item->name }}</strong>?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <!-- End Modal Delete -->

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">Tidak ada data User.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
