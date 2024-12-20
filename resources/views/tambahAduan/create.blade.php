@extends('template.app')

@section('konten')
<div class="container mt-5">
    <h2>Tambah Pengaduan</h2>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('store-pengaduan') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Provinsi Dropdown -->
        <div class="mb-3">
            <label for="province" class="form-label">Provinsi</label>
            <select id="province" name="province" class="form-control" required>
                <option value="">Pilih Provinsi</option>
                @foreach ($provinces as $province)
                    <option value="{{ $province['id'] }}">{{ $province['name'] }}</option>
                @endforeach
            </select>
        </div>

        <!-- Kabupaten/Kota Dropdown -->
        <div class="mb-3">
            <label for="regency" class="form-label">Kabupaten/Kota</label>
            <select id="regency" name="regency" class="form-control" required>
                <option value="">Pilih Kabupaten/Kota</option>
            </select>
        </div>

        <!-- Kecamatan Dropdown -->
        <div class="mb-3">
            <label for="district" class="form-label">Kecamatan</label>
            <select id="district" name="district" class="form-control" required>
                <option value="">Pilih Kecamatan</option>
            </select>
        </div>

        <!-- Desa/Kelurahan Dropdown -->
        <div class="mb-3">
            <label for="village" class="form-label">Desa/Kelurahan</label>
            <select id="village" name="village" class="form-control" required>
                <option value="">Pilih Desa/Kelurahan</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="type" class="form-label">Tipe Pengaduan</label>
            <select name="type" id="type" class="form-select" required>
                <option value="" disabled selected>Pilih Tipe Pengaduan</option>
                @foreach ($types as $type)
                    <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                @endforeach
            </select>
        </div>

        <!-- Deskripsi Pengaduan -->
        <div class="mb-3">
            <label for="description" class="form-label">Deskripsi Pengaduan</label>
            <textarea id="description" name="description" class="form-control" rows="4" required></textarea>
        </div>

        <!-- Gambar Upload -->
        <div class="mb-3">
            <label for="image" class="form-label">Gambar Pengaduan</label>
            <input type="file" id="image" name="image" class="form-control" accept="image/*" required>
        </div>

        <button type="submit" class="btn btn-primary">Simpan Pengaduan</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Ketika Provinsi dipilih, ambil kabupaten/kota
        $('#province').on('change', function() {
            var provinceId = $(this).val();
            
            // Reset dropdown
            $('#regency').empty().append('<option value="">Pilih Kabupaten/Kota</option>');
            $('#district').empty().append('<option value="">Pilih Kecamatan</option>');
            $('#village').empty().append('<option value="">Pilih Desa/Kelurahan</option>');

            if (provinceId) {
                // Mengambil data kabupaten/kota berdasarkan provinsi
                $.get('https://www.emsifa.com/api-wilayah-indonesia/api/regencies/' + provinceId + '.json', function(data) {
                    $.each(data, function(index, regency) {
                        $('#regency').append('<option value="'+ regency.id +'">' + regency.name + '</option>');
                    });
                });
            }
        });

        // Ketika Kabupaten/Kota dipilih, ambil kecamatan
        $('#regency').on('change', function() {
            var regencyId = $(this).val();

            // Reset dropdown kecamatan dan desa
            $('#district').empty().append('<option value="">Pilih Kecamatan</option>');
            $('#village').empty().append('<option value="">Pilih Desa/Kelurahan</option>');

            if (regencyId) {
                // Mengambil data kecamatan berdasarkan kabupaten/kota
                $.get('https://www.emsifa.com/api-wilayah-indonesia/api/districts/' + regencyId + '.json', function(data) {
                    $.each(data, function(index, district) {
                        $('#district').append('<option value="'+ district.id +'">' + district.name + '</option>');
                    });
                });
            }
        });

        // Ketika Kecamatan dipilih, ambil desa/kelurahan
        $('#district').on('change', function() {
            var districtId = $(this).val();

            // Reset dropdown desa
            $('#village').empty().append('<option value="">Pilih Desa/Kelurahan</option>');

            if (districtId) {
                // Mengambil data desa/kelurahan berdasarkan kecamatan
                $.get('https://www.emsifa.com/api-wilayah-indonesia/api/villages/' + districtId + '.json', function(data) {
                    $.each(data, function(index, village) {
                        $('#village').append('<option value="'+ village.id +'">' + village.name + '</option>');
                    });
                });
            }
        });
    });
</script>
@endsection
