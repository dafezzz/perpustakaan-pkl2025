@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="h4 mb-4">Tambah Resident</h1>

    <form action="{{ route('resident.store') }}" method="post" enctype="multipart/form-data">
        @csrf
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
                    <input type="text" name="name" class="form-control" required value="{{ old('name') }}">
                </div>

                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" required value="{{ old('email') }}">
                </div>

                <div class="mb-3">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Telepon</label>
                    <input type="text" name="telp" class="form-control" value="{{ old('telp') }}">
                </div>

                <div class="mb-3">
                    <label>Alamat</label>
                    <textarea name="alamat" class="form-control">{{ old('alamat') }}</textarea>
                </div>

                <div class="mb-3">
                    <label>Cover (Foto)</label>
                    <input type="file" name="cover" class="form-control" onchange="previewCover(event)">
                    <img id="coverPreview" src="#" alt="Preview Cover" class="mt-2" style="display:none;width:120px;border-radius:5px;">
                </div>

            </div>
            <div class="card-footer">
                <button class="btn btn-primary">Simpan</button>
                <a href="{{ route('resident.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </div>
    </form>
</div>

<script>
function previewCover(event) {
    const input = event.target;
    const preview = document.getElementById('coverPreview');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
