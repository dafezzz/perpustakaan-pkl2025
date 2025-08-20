@extends('layouts.app')

@section('content')
<!-- Heading -->
<div class="d-flex align-items-center justify-content-between mb-4">
    <h1 class="h4 mb-0 text-gray-800">Edit Data</h1>
</div>

<div class="row">
    <div class="col">
        <form action="{{ route('resident.update', $resident->id) }}" method="post">
            @csrf
            @method('PUT')

            <div class="card">
                <div class="card-body">

                    {{-- Pesan error jika validasi gagal --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>Harap isi dengan benar:</strong>
                            <ul class="mb-0 mt-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Name --}}
                    <div class="form-group mb-3">
                        <label for="name">Name</label>
                        <input 
                            type="text" 
                            name="name" 
                            id="name" 
                            class="form-control" 
                            value="{{ old('name', $resident->name) }}"
                            required
                        >
                    </div>

                    {{-- Email --}}
                    <div class="form-group mb-3">
                        <label for="email">Email</label>
                        <input 
                            type="email" 
                            name="email" 
                            id="email" 
                            class="form-control" 
                            value="{{ old('email', $resident->email) }}"
                            required
                        >
                    </div>

                    {{-- Password --}}
                    <div class="form-group mb-3">
                        <label for="password">
                            Password <small class="text-muted">(Kosongkan jika tidak ingin diubah)</small>
                        </label>
                        <input 
                            type="text" {{-- Ganti type="password" menjadi type="text" agar tidak muncul titik-titik --}}
                            name="password" 
                            id="password" 
                            class="form-control"
                            placeholder="Kosongkan jika tidak ingin diubah"
                            autocomplete="off"
                        >
                    </div>

                </div>

                {{-- Tombol --}}
                <div class="card-footer d-flex justify-content-end" style="gap:10px;">
                    <a href="{{ route('resident.index') }}" class="btn btn-outline-secondary">
                        Kembali
                    </a>
                    <button type="submit" class="btn btn-warning">
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
