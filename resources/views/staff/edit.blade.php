@extends('template.app')

@section('konten')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <h4>Edit Staff</h4>
            <form action="{{ route('staff-update', $staff->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" id="email" value="{{ $staff->email }}" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password (Opsional)</label>
                    <input type="password" name="password" class="form-control" id="password" placeholder="Kosongkan jika tidak ingin mengubah">
                </div>
                <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                <a href="{{ route('staff-index') }}" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
</div>
@endsection
