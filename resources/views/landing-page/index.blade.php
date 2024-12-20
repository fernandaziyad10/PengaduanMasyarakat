@extends('template.app')

@section('konten')
<style>
:root {
    --primary-color: #007bff; /* Biru utama */
    --secondary-color: #28a745; /* Hijau untuk aksi */
    --background-color: #f8f9fc; /* Latar belakang lebih terang */
    --card-background: #ffffff; /* Latar belakang kartu putih */
    --text-primary: #343a40; /* Warna teks utama */
    --text-secondary: #6c757d; /* Warna teks sekunder */
    --border-radius: 8px; /* Radius sudut kartu */
    --box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1); /* Shadow lembut */
}

* {
    box-sizing: border-box;
    transition: all 0.3s ease;
}

body {
    font-family: 'Inter', 'Poppins', sans-serif;
    background-color: var(--background-color);
    color: var(--text-primary);
    line-height: 1.6;
    margin: 0;
    padding: 0;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 40px 15px;
}

.text-center {
    text-align: center;
    margin-bottom: 30px;
}

.heading {
    font-size: 2.5rem;
    color: var(--primary-color);
    font-weight: 700;
}

.subheading {
    font-size: 1.1rem;
    color: var(--text-secondary);
    margin: 0 auto;
    max-width: 600px;
}

.aduan-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
}

.aduan-card {
    background-color: var(--card-background);
    border-radius: var(--border-radius);
    box-shadow: var(--box-shadow);
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.aduan-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.1);
}

.aduan-card-image {
    position: relative;
}

.aduan-card-image img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    transition: transform 0.4s ease;
}

.aduan-card-image:hover img {
    transform: scale(1.1);
}

.view-count {
    position: absolute;
    top: 15px;
    right: 15px;
    background-color: rgba(255, 255, 255, 0.7);
    padding: 5px 12px;
    border-radius: 20px;
    font-weight: 600;
    color: var(--text-primary);
}

.aduan-details {
    padding: 15px;
}

.location {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
    color: var(--text-secondary);
}

.location i {
    margin-right: 8px;
    color: var(--primary-color);
    font-size: 1.1rem;
}

.description {
    background-color: #f8f9fa;
    border-radius: 8px;
    padding: 15px;
    margin-top: 15px;
}

.description h3 {
    color: var(--primary-color);
    margin-bottom: 10px;
}

.aduan-footer {
    display: flex;
    justify-content: space-between;
    padding: 15px;
    background-color: #f1f3f5;
}

.vote-button {
    background-color: var(--secondary-color);
    color: white;
    border: none;
    padding: 10px 18px;
    border-radius: 20px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
}

.vote-button:hover {
    background-color: #218838;
    transform: translateY(-3px);
}

.comments {
    padding: 20px;
    background-color: #f8f9fa;
}

textarea {
    width: 100%;
    padding: 12px;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    margin-bottom: 10px;
    transition: border-color 0.3s ease;
}

textarea:focus {
    border-color: var(--primary-color);
    outline: none;
}

.btn-primary {
    background-color: var(--primary-color);
    color: white;
    border: none;
    padding: 12px 25px;
    border-radius: 25px;
    font-weight: 600;
    box-shadow: 0 5px 15px rgba(52, 152, 219, 0.3);
}

.btn-primary:hover {
    background-color: #0056b3;
}

.alert-success, .alert-danger {
    border-radius: 12px;
    padding: 15px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 15px;
}

.alert-success {
    background-color: #e8f5e9;
    color: #2e7d32;
}

.alert-danger {
    background-color: #ffebee;
    color: #d32f2f;
}

@media (max-width: 768px) {
    .aduan-grid {
        grid-template-columns: 1fr;
    }

    .heading {
        font-size: 2rem;
    }
}

</style>

@if (Session::get('success'))
    <div class="alert alert-success">
        <p>
            <i class="fas fa-check-circle"></i>
            {{ Session::get('success') }}
        </p>
    </div>
@endif

@if(Session::get('failed'))
    <div class="alert alert-danger" role="alert">
        <div class="d-flex gap-4">
            <span><i class="fa-solid fa-circle-exclamation icon-danger"></i></span>
            <div class="d-flex flex-column gap-2">
                <h6 class="mb-0">{{Session::get('failed')}}</h6>
            </div>
        </div>
    </div>
@endif



<div class="container">
    <div class="text-center mb-5">
        <h2 class="heading">Daftar Pengaduan</h2>
        <p class="subheading">
            Setiap suara bermakna, setiap pengaduan berarti
        </p>
    </div>

    <div class="aduan-grid">
        @foreach ($aduans as $aduan)
        <div class="aduan-card">
            <div class="aduan-card-image">
                <a href="#" data-bs-toggle="collapse" data-bs-target="#aduanDetails-{{ $aduan->id }}" onclick="incrementViewCount({{ $aduan->id }})">
                    <img src="{{ $aduan->image ? asset('storage/' . $aduan->image) : 'https://via.placeholder.com/400x300' }}" alt="Gambar Aduan">
                </a>
                <div class="view-count">
                    <i class="fas fa-eye"></i> <span id="view-count-{{ $aduan->id }}">{{ $aduan->viewers }}</span>
                </div>
            </div>

            <div class="collapse" id="aduanDetails-{{ $aduan->id }}">
                <div class="card card-body">
                    <div class="aduan-details">
                        <div class="location">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>
                                @php
                                $province = json_decode(file_get_contents('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json'), true);
                                $province_name = collect($province)->firstWhere('id', $aduan->province)['name'] ?? 'N/A';
                                @endphp
                                {{ $province_name }}
                            </span>
                        </div>
                        <div class="location">
                            <i class="fas fa-city"></i>
                            @php
                            $regency = json_decode(file_get_contents("https://www.emsifa.com/api-wilayah-indonesia/api/regencies/{$aduan->province}.json"), true);
                            $regency_name = collect($regency)->firstWhere('id', $aduan->regency)['name'] ?? 'N/A';
                            @endphp
                            <span>{{ $regency_name }}</span>
                        </div>
                        <div class="location">
                            <i class="fas fa-building"></i>
                            @php
                            $district = json_decode(file_get_contents("https://www.emsifa.com/api-wilayah-indonesia/api/districts/{$aduan->regency}.json"), true);
                            $district_name = collect($district)->firstWhere('id', $aduan->district)['name'] ?? 'N/A';
                            @endphp
                            <span>{{ $district_name }}</span>
                        </div>
                        <div class="location">
                            <i class="fas fa-home"></i>
                            @php
                            $village = json_decode(file_get_contents("https://www.emsifa.com/api-wilayah-indonesia/api/villages/{$aduan->district}.json"), true);
                            $village_name = collect($village)->firstWhere('id', $aduan->village)['name'] ?? 'N/A';
                            @endphp
                            <span>{{ $village_name }}</span>
                        </div>
                        <div class="location">
                            <i class="fa-regular fa-face-tired"></i>
                            {{ $aduan->type }}
                        </div>

                        <div>
                            <p>Status Pengerjaan:</p>
                            <span class="badge
                                {{ $aduan->status == 'pending' ? 'bg-warning' : 
                                   ($aduan->status == 'in_progress' ? 'bg-info' : 
                                   ($aduan->status == 'resolved' ? 'bg-success' : 'bg-danger')) }}">
                                {{ $aduan->status == 'pending' ? 'Tertunda' : 
                                   ($aduan->status == 'in_progress' ? 'Sedang Dikerjakan' : 
                                   ($aduan->status == 'resolved' ? 'Selesai' : 'Ditolak')) }}
                            </span>
                        </div>

                        <div class="description">
                            <h3>Deskripsi</h3>
                            <p>{{ $aduan->description }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="aduan-footer">
                <form action="{{ route('aduan.vote', $aduan->id) }}" method="POST" class="vote-form">
                    @csrf
                    <button type="submit" class="vote-button">
                        <i class="fas fa-heart"></i>
                        <span>{{ $aduan->voting }}</span>
                    </button>
                </form>
            </div>

            <div class="comments">
                <h4>Komentar</h4>
                
                <!-- Display existing comments -->
                @foreach (explode("\n", $aduan->comments) as $comment)
                    <div class="comment">
                        <p>{{ $comment }}</p>
                    </div>
                @endforeach
            
                <!-- Comment form -->
                <form action="{{ route('aduan.comment', $aduan->id) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <textarea name="comment" rows="4" placeholder="Tulis komentar..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Kirim Komentar</button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
</div>

<script>
function incrementViewCount(aduanId) {
    let viewCountElement = document.getElementById(`view-count-${aduanId}`);
    let currentCount = parseInt(viewCountElement.textContent);
    viewCountElement.textContent = currentCount + 1; 

    $.ajax({
        url: '/update-view-count/' + aduanId, 
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}' 
        },
        success: function(response) {
            if (response.success) {
                console.log("View count updated successfully!");
            } else {
                console.error("Failed to update view count");
            }
        },
        error: function(error) {
            console.error("Error occurred while updating view count", error);
        }
    });
}
</script>

@endsection