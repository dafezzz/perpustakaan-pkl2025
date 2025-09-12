@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="h4 mb-4">Data Resident</h1>

    <a href="{{ route('resident.create') }}" class="btn btn-primary mb-3">Tambah Resident</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-primary">
                        <tr>
                            <th>No</th>
                            <th>Cover</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Telepon</th>
                            <th>Alamat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($residents as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
    @if($item->cover)
        <img src="{{ asset('storage/'.$item->cover) }}" width="60" class="rounded">
    @else
        <span class="text-muted">-</span>
    @endif
</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->email }}</td>
                                <td>{{ $item->telepon ?? '-' }}</td>
                                <td>{{ $item->alamat ?? '-' }}</td>
                                <td>
                                    <a href="{{ route('resident.edit', $item->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('resident.destroy', $item->id) }}" method="post" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button onclick="return confirm('Yakin hapus?')" class="btn btn-sm btn-danger">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center">Tidak ada data.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
