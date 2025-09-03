@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Edit Rak</h3>
    <form action="{{ route('rak.update', $rak->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Kode Rak</label>
            <input type="text" name="kode_rak" value="{{ $rak->kode_rak }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Nama Rak</label>
            <input type="text" name="nama_rak" value="{{ $rak->nama_rak }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Keterangan</label>
            <textarea name="keterangan" class="form-control">{{ $rak->keterangan }}</textarea>
        </div>

        <button class="btn btn-success">Update</button>
        <a href="{{ route('rak.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
