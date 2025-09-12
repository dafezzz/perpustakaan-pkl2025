@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="h4 mb-4">Edit Resident</h1>

    <form action="{{ route('resident.update', $resident->id) }}" method="post" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="card">
            <div class="card-body">

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="mb-3">
                    <label>Nama</label>
                    <input type="text" name="name" class="form-control" required value="{{ old('name', $resident->name) }}">
                </div>

                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" required value="{{ old('email', $resident->email) }}">
                </div>

                <div class="mb-3">
                    <label>Password <small>(Kosongkan jika tidak diubah)</small></label>
                    <input type="password" name="password" class="form-control">
                </div>

                <div class="mb-3">
                    <label>Telepon</label>
                    <input type="text" name="telepon" class="form-control" value="{{ old('telepon', $resident->telepon) }}">
                </div>

                <div class="mb-3">
                    <label>Alamat</label>
                    <textarea name="alamat" class="form-control">{{ old('alamat', $resident->alamat) }}</textarea>
                </div>

                <div class="mb-3">
                    <label>Cover (Foto)</label><br>
                    @if($resident->cover)
                        <img src="{{ asset('storage/'.$resident->cover) }}" width="80" class="mb-2 rounded">
                    @endif
                    <input type="file" name="cover" class="form-control">
                </div>

            </div>
            <div class="card-footer">
                <button class="btn btn-warning">Update</button>
                <a href="{{ route('resident.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </div>
    </form>
</div>
@endsection
