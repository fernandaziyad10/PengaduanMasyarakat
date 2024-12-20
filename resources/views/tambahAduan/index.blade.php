@extends('template.app')

@section('konten')
    <style>
        /* Animasi muncul dan hilang */
        .alert-success, .alert-danger {
            opacity: 0;
            transform: translateY(-20px);
            animation: slideIn 0.5s forwards;
        }

        @keyframes slideIn {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-out {
            animation: fadeOut 0.5s forwards;
        }

        @keyframes fadeOut {
            to {
                opacity: 0;
                transform: translateY(20px);
            }
        }

        /* Desain untuk alert */
        .alert {
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0px 8px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .alert-success {
            background: linear-gradient(135deg, #4caf50, #81c784);
            color: white;
        }

        .alert-danger {
            background: linear-gradient(135deg, #f44336, #e57373);
            color: white;
        }

        /* Tombol dan input */
        .btn-custom {
            background-color: #1d6f42;
            border-radius: 20px;
            color: white;
            padding: 10px 20px;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }

        .btn-custom:hover {
            background-color: #145c34;
        }

        .form-control-custom {
            border-radius: 30px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 10px 20px;
            font-size: 14px;
        }

        /* Tabel */
        .table-wrapper {
            max-width: 90%;
            margin: 0 auto;
            overflow-x: auto;
        }

        .table {
            font-size: 14px;
            text-align: center;
        }

        .table th, .table td {
            padding: 8px;
            vertical-align: middle;
        }

        .table img {
            border-radius: 8px;
        }

        .table-striped tbody tr:nth-child(odd) {
            background-color: #f9f9f9;
        }

        .table-striped tbody tr:hover {
            background-color: #f1f1f1;
        }

        .badge {
            padding: 5px 10px;
            border-radius: 12px;
            font-size: 12px;
        }

        .container {
            margin-top: 30px;
            padding: 20px;
        }

        .text-end {
            margin-bottom: 15px;
        }
    </style>

    <div class="container">
        <h2 class="mb-4 text-center">Daftar Pengaduan</h2>

        <!-- Menampilkan Alert -->
        @if(session('success'))
            <div class="alert alert-success">
                <div class="d-flex gap-4">
                    <span><i class="fa-solid fa-circle-check icon-success"></i></span>
                    <div>{{ session('success') }}</div>
                </div>
            </div>
        @endif

        @if(session('failed'))
            <div class="alert alert-danger">
                <div class="d-flex gap-4">
                    <span><i class="fa-solid fa-circle-exclamation icon-danger"></i></span>
                    <div>
                        <h6 class="mb-0">Pengaduan Gagal</h6>
                        <p class="mb-0">{{ session('failed') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Tombol Export Semua -->
        <form method="GET" action="{{ route('aduan.export') }}" class="mb-3">
            <button type="submit" class="btn btn-primary">Export Semua Data</button>
        </form>
        
        <!-- Tabel Pengaduan -->
        <div class="table-wrapper">
            <table class="table table-striped table-bordered table-hover">
                <thead class="table-primary">
                    <tr>
                        <th>#</th>
                        <th>Deskripsi</th>
                        <th>Provinsi</th>
                        <th>Kabupaten</th>
                        <th>Kecamatan</th>
                        <th>Desa</th>
                        <th>Tipe</th>
                        <th>Gambar</th>
                        <th>Vote</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($aduans as $aduan)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $aduan->description }}</td>
                            <td>
                                @php
                                    $province = json_decode(file_get_contents('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json'), true);
                                    $province_name = collect($province)->firstWhere('id', $aduan->province)['name'] ?? 'N/A';
                                @endphp
                                {{ $province_name }}
                            </td>
                            <td>
                                @php
                                    $regency = json_decode(file_get_contents("https://www.emsifa.com/api-wilayah-indonesia/api/regencies/{$aduan->province}.json"), true);
                                    $regency_name = collect($regency)->firstWhere('id', $aduan->regency)['name'] ?? 'N/A';
                                @endphp
                                {{ $regency_name }}
                            </td>
                            <td>
                                @php
                                    $district = json_decode(file_get_contents("https://www.emsifa.com/api-wilayah-indonesia/api/districts/{$aduan->regency}.json"), true);
                                    $district_name = collect($district)->firstWhere('id', $aduan->district)['name'] ?? 'N/A';
                                @endphp
                                {{ $district_name }}
                            </td>
                            <td>
                                @php
                                    $village = json_decode(file_get_contents("https://www.emsifa.com/api-wilayah-indonesia/api/villages/{$aduan->district}.json"), true);
                                    $village_name = collect($village)->firstWhere('id', $aduan->village)['name'] ?? 'N/A';
                                @endphp
                                {{ $village_name }}
                            </td>
                            <td>{{ $aduan->type }}</td>
                            <td>
                                @if($aduan->image)
                                    <img src="{{ asset('storage/' . $aduan->image) }}" alt="Image" width="50" height="50">
                                @else
                                    <span>No Image</span>
                                @endif
                            </td>
                            <td>{{ $aduan->voting }}</td>
                            <td>
                                <span class="badge 
                                    {{ $aduan->status == 'pending' ? 'bg-warning' : 
                                       ($aduan->status == 'in_progress' ? 'bg-info' : 
                                       ($aduan->status == 'resolved' ? 'bg-success' : 'bg-danger')) }}">
                                    {{ $aduan->status == 'pending' ? 'Tertunda' : 
                                       ($aduan->status == 'in_progress' ? 'Sedang Dikerjakan' : 
                                       ($aduan->status == 'resolved' ? 'Selesai' : 'Ditolak')) }}
                                </span>
                            </td>
                            <td>
                                <!-- Dropdown untuk Ubah Status -->
                                <form action="{{ route('update-status', $aduan->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('PUT')
                                    <select name="status" onchange="this.form.submit()" class="form-select form-select-sm" style="width: auto; display: inline-block;">
                                        <option value="pending" {{ $aduan->status == 'pending' ? 'selected' : '' }}>Tertunda</option>
                                        <option value="in_progress" {{ $aduan->status == 'in_progress' ? 'selected' : '' }}>Sedang Dikerjakan</option>
                                        <option value="resolved" {{ $aduan->status == 'resolved' ? 'selected' : '' }}>Selesai</option>
                                        <option value="rejected" {{ $aduan->status == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                                    </select>
                                </form>
                                <!-- Fitur Edit -->
                                {{-- <a href="{{ route('edit-pengaduan', $aduan->id) }}" class="btn btn-warning btn-sm">Edit</a> --}}

                                <!-- Fitur Hapus -->
                                <form action="{{ route('delete-pengaduan', $aduan->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus pengaduan ini?')">Hapus</button>
                                </form>
                                <a href="{{ route('aduan.export.single', $aduan->id) }}" class="btn btn-success btn-sm">Export</a>

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection 