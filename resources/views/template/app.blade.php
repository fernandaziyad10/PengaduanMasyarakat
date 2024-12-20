<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Title</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    {{-- <script src="https://cdn.tailwindcss.com"></script> --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">    

    <style>
        /* Your existing styles... */
    </style>

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">AduanKita</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    @if(Auth::check()) <!-- Check if user is logged in -->
                        @if(auth()->user()->role == 'guest') <!-- Guest Role -->
                            <li class="nav-item">
                                <a class="nav-link active" href="{{route('landingPage-pengaduan')}}">Landing-Page</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{route('tambahAduanGuest-pengaduan')}}">Tambah Pengaduan</a>
                            </li>
                        @elseif(auth()->user()->role == 'staff') <!-- Staff Role -->
                            <li class="nav-item">
                                <a class="nav-link" href="{{route('index-pengaduan')}}">Data Pengaduan</a>
                            </li>
                        @elseif(auth()->user()->role == 'head_staff') <!-- Head Staff Role -->
                            <li class="nav-item">
                                <a class="nav-link" href="{{route('staff-index')}}">Kelola Akun Staff</a>
                            </li>
                        @endif
                    @else <!-- If the user is not logged in (Guest) -->
                        <li class="nav-item">
                            <a class="nav-link active" href="{{route('landingPage-pengaduan')}}">Landing-Page</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{route('tambahAduanGuest-pengaduan')}}">Tambah Pengaduan</a>
                        </li>
                    @endif

                    <!-- Always visible for logged-in users -->
                    @auth
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('login')}}">Logout</a>
                    </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    @yield('konten');
</head>
</html>
