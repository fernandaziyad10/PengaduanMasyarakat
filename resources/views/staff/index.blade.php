@extends('template.app')

@section('konten')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-6">
            <!-- Menampilkan pesan sukses -->
            @if (Session::get('success'))
            <div class="alert alert-success" role="alert">
                <div class="d-flex gap-2">
                    <span><i class="fa-solid fa-circle-check icon-success"></i> {{Session::get('success')}}</span>
                </div>
            </div>
            @endif

            <!-- Tabel Staff -->
            <h4>Daftar Staff</h4>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Email</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($staffs as $key => $staff)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $staff->email }}</td>
                            <td>
                                <!-- Tombol Edit -->
                                <a class="btn btn-primary btn-sm" href="{{ route('staff-edit', $staff->id) }}">Edit</a> 
                                
                                <!-- Form Delete -->
                                <form action="{{ route('staff-delete', $staff->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus staff ini?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="col-md-6">
            <!-- Form Tambah Staff -->
            <h4>Tambah Staff</h4>
            <form action="{{ route('staff.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" id="email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" id="password" required>
                </div>
                <button type="submit" class="btn btn-primary">Tambah Staff</button>
            </form>
        </div>
    </div>
</div>

<!-- Aturan -->
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="alert" 
                style="background: linear-gradient(to right, #6a11cb, #2575fc); 
                       color: white; 
                       border-radius: 20px; 
                       box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2); 
                       padding: 25px;">
                <div class="d-flex align-items-center mb-3">
                    <i class="fa-solid fa-book fa-lg me-2"></i>
                    <h4 class="font-weight-bold mb-0" style="letter-spacing: 1px;">Aturan</h4>
                </div>
                <ul style="line-height: 1.8; font-size: 16px; margin-left: 20px;">
                    <li>Buat akun dengan benar dan jelas</li>
                    <li>Buat email seperti, contoh <strong>staff_provinsi@example.com</strong></li>
                </ul>
            </div>
        </div>
    </div>
</div>


@endsection
