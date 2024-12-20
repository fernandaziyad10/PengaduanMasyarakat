@extends('template.app')

@section('konten')
<div class="container mt-5">
    <h2>Edit Pengaduan</h2>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('update-pengaduan', $pengaduan->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Provinsi Dropdown -->
        <div class="mb-3">
            <label for="province" class="form-label">Provinsi</label>
            <select id="province" name="province" class="form-control" required>
                <option value="">Pilih Provinsi</option>
                @foreach ($provinces as $province)
                    <option value="{{ $province['id'] }}" 
                        {{ old('province', $pengaduan->province) == $province['id'] ? 'selected' : '' }}>
                        {{ $province['name'] }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Kabupaten/Kota Dropdown -->
        <div class="mb-3">
            <label for="regency" class="form-label">Kabupaten/Kota</label>
            <select id="regency" name="regency" class="form-control" required>
                <option value="">Pilih Kabupaten/Kota</option>
                @foreach ($regencies as $regency)
                    <option value="{{ $regency['id'] }}" 
                        {{ old('regency', $pengaduan->regency) == $regency['id'] ? 'selected' : '' }}>
                        {{ $regency['name'] }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Kecamatan Dropdown -->
        <div class="mb-3">
            <label for="district" class="form-label">Kecamatan</label>
            <select id="district" name="district" class="form-control" required>
                <option value="">Pilih Kecamatan</option>
                @foreach ($districts as $district)
                    <option value="{{ $district['id'] }}" 
                        {{ old('district', $pengaduan->district) == $district['id'] ? 'selected' : '' }}>
                        {{ $district['name'] }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Desa/Kelurahan Dropdown -->
        <div class="mb-3">
            <label for="village" class="form-label">Desa/Kelurahan</label>
            <select id="village" name="village" class="form-control" required>
                <option value="">Pilih Desa/Kelurahan</option>
                @foreach ($villages as $village)
                    <option value="{{ $village['id'] }}" 
                        {{ old('village', $pengaduan->village) == $village['id'] ? 'selected' : '' }}>
                        {{ $village['name'] }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Deskripsi -->
        <div class="mb-3">
            <label for="description" class="form-label">Deskripsi Pengaduan</label>
            <textarea id="description" name="description" class="form-control" rows="4" required>{{ old('description', $pengaduan->description) }}</textarea>
        </div>

        <!-- Gambar -->
        <div class="mb-3">
            <label for="image" class="form-label">Gambar Pengaduan</label>
            <input type="file" id="image" name="image" class="form-control" accept="image/*">
            @if ($pengaduan->image)
                <img src="{{ asset('storage/' . $pengaduan->image) }}" alt="Gambar Pengaduan" class="mt-2" width="150">
            @endif
        </div>

        <button type="submit" class="btn btn-primary">Update Pengaduan</button>
        <a href="{{ route('index-pengaduan') }}" class="btn btn-danger">Kembali</a>
    </form>
</div>
@endsection
