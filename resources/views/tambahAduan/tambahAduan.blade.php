@extends('template.app')

@section('konten')
<div class="hero-section">
    <div class="hero-content">
        <div class="pengaduan-box">
            <div class="box-header">
                <h3>Petunjuk Menambah Pengaduan</h3>
            </div>
            <div class="box-content">
                <p>- Pengaduan diisi sesuai kejadian. <strong>Jika tidak sesuai maka akan dikenakan sanksi</strong></p>
                <p>- Laporkan masalah atau keluhan Anda di sini untuk kami tindaklanjuti dengan cepat dan tepat.</p>
                <p>- Jika Anda menghadapi kendala, jangan ragu untuk mengajukan pengaduan. Kami siap mendengarkan dan membantu.</p>
                <div class="button-group">
                    <a href="{{ route('create-pengaduan') }}" class="btn-modern primary">+ Tambah Pengaduan</a>
                </div>                              
            </div>
        </div>
    </div>
</div>

<div class="container main-content">
    <h2 class="section-title">Daftar Pengaduan Anda</h2>

    @if(session('success'))
        <div class="alert success-alert">
            <div class="alert-content">
                <span class="alert-icon"><i class="fa-solid fa-circle-check"></i></span>
                <div class="alert-message">{{ session('success') }}</div>
            </div>
        </div>
    @endif

    @if(session('failed'))
        <div class="alert danger-alert">
            <div class="alert-content">
                <span class="alert-icon"><i class="fa-solid fa-circle-exclamation"></i></span>
                <div class="alert-message">
                    <h6>Pengaduan Gagal</h6>
                    <p>{{ session('failed') }}</p>
                </div>
            </div>
        </div>
    @endif

    <div class="table-container">
        <table class="modern-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Pengirim Pengaduan</th>
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
                    <tr class="table-row-animate">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $aduan->user ? $aduan->user->email : 'Tidak Ada Email' }}</td>
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
                                <img class="table-image" src="{{ asset('storage/' . $aduan->image) }}" alt="Image">
                            @else
                                <span class="no-image">No Image</span>
                            @endif
                        </td>
                        <td>{{ $aduan->voting }}</td>
                        <td>
                            <span class="status-badge {{ $aduan->status }}">
                                {{ $aduan->status == 'pending' ? 'Tertunda' : 
                                   ($aduan->status == 'in_progress' ? 'Sedang Dikerjakan' : 
                                   ($aduan->status == 'resolved' ? 'Selesai' : 'Ditolak')) }}
                            </span>
                        </td>
                        <td class="action-buttons">
                           <form action="{{route('delete-pengaduan', $aduan->id)}}" method="POST" class="btn btn-danger">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Hapus Pengaduan</button>
                           </form>
                            <a href="{{ route('edit-pengaduan', $aduans->first()->id ?? '') }}" class="btn btn-warning">Edit Pengaduan</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<style>
/* Base Styles */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Poppins', sans-serif;
    line-height: 1.6;
    color: #333;
    background-color: #f5f7fa;
}

/* Hero Section */
.hero-section {
    background: linear-gradient(135deg, #4f46e5, #6366f1);
    padding: 3rem 0;
    margin-bottom: 2rem;
    animation: gradientAnimation 15s ease infinite;
}

@keyframes gradientAnimation {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

/* Pengaduan Box */
.pengaduan-box {
    max-width: 800px;
    margin: 0 auto;
    background: rgba(255, 255, 255, 0.95);
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    backdrop-filter: blur(10px);
    transform: translateY(0);
    transition: transform 0.3s ease;
    animation: floatAnimation 3s ease-in-out infinite;
}

@keyframes floatAnimation {
    0% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
    100% { transform: translateY(0px); }
}

.action-buttons a {
    color:white;
}

.action-buttons a:hover {
    color:white;
}

.box-header {
    background: linear-gradient(135deg, #4f46e5, #6366f1);
    color: white;
    padding: 1.5rem;
    border-radius: 20px 20px 0 0;
}

.box-header h3 {
    font-size: 1.5rem;
    margin: 0;
}

.box-content {
    padding: 2rem;
}

/* Modern Buttons */
.button-group {
    display: flex;
    gap: 1rem;
    margin-top: 1.5rem;
}

.btn-modern {
    padding: 0.8rem 1.5rem;
    border-radius: 12px;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-modern.primary {
    background: linear-gradient(135deg, #4f46e5, #6366f1);
    color: white;
}

.btn-modern.secondary {
    background: #fff;
    color: #4f46e5;
    border: 2px solid #4f46e5;
}



.btn-modern.danger {
    background: #ef4444;
    color: white;
}

.btn-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

/* Alerts */
.alert {
    margin: 1rem 0;
    padding: 1rem;
    border-radius: 12px;
    animation: slideIn 0.5s ease-out;
}

@keyframes slideIn {
    from {
        transform: translateY(-20px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.success-alert {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
}

.danger-alert {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white;
}

.alert-content {
    display: flex;
    align-items: center;
    gap: 1rem;
}

/* Table Styles */
.table-container {
    background: white;
    border-radius: 20px;
    padding: 1.5rem;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    overflow-x: auto;
}

.modern-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0 0.5rem;
}

.modern-table th {
    background: #f8fafc;
    padding: 1rem;
    text-align: left;
    font-weight: 600;
    color: #1f2937;
}

.table-row-animate {
    animation: fadeIn 0.5s ease-out;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.modern-table td {
    padding: 1rem;
    background: white;
    transition: all 0.3s ease;
}

.modern-table tr:hover td {
    background: #f8fafc;
    transform: scale(1.01);
}

/* Status Badges */
.status-badge {
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: 500;
}

.status-badge.pending {
    background: #fef3c7;
    color: #d97706;
}

.status-badge.in_progress {
    background: #dbeafe;
    color: #2563eb;
}

.status-badge.resolved {
    background: #d1fae5;
    color: #059669;
}

.status-badge.rejected {
    background: #fee2e2;
    color: #dc2626;
}

/* Form Elements */
.status-select {
    padding: 0.5rem;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
    background: white;
    cursor: pointer;
    transition: all 0.3s ease;
}

.status-select:hover {
    border-color: #4f46e5;
}

/* Images */
.table-image {
    width: 50px;
    height: 50px;
    border-radius: 8px;
    object-fit: cover;
}

.no-image {
    color: #9ca3af;
    font-size: 0.875rem;
}

/* Responsive Design */
@media (max-width: 1024px) {
    .pengaduan-box {
        margin: 1rem;
    }
    
    .button-group {
        flex-direction: column;
    }
    
    .table-container {
        margin: 1rem;
    }
}

@media (max-width: 768px) {
    .modern-table {
        font-size: 0.875rem;
    }
    
    .btn-modern {
        padding: 0.6rem 1rem;
    }
}
</style>
@endsection