@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Tambah Rak</h3>
    <form action="{{ route('rak.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Kode Rak</label>
            <input type="text" name="kode_rak" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Nama Rak</label>
            <input type="text" name="nama_rak" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Keterangan</label>
            <textarea name="keterangan" class="form-control"></textarea>
        </div>
        <button class="btn btn-success">Simpan</button>
    </form>
</div>
@endsection
