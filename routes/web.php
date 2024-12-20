<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TambahAduanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StaffController;
use App\Exports\AduanExport;
use Maatwebsite\Excel\Facades\Excel;

// Non-Role Routes (Akses Bebas)
Route::get('/register', [UserController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [UserController::class, 'register']);
Route::get('/login', [UserController::class, 'showLoginForm'])->name('login');
Route::post('/login', [UserController::class, 'login']);
Route::post('/logout', [UserController::class, 'logout'])->name('logout');

// Guest Routes (Landing Page & Tambah Pengaduan)
Route::middleware(['auth', 'guest.role'])->group(function () {
    Route::get('/', [TambahAduanController::class, 'LandingPage'])->name('landingPage-pengaduan');
    Route::get('/create/pengaduan', [TambahAduanController::class, 'create'])->name('create-pengaduan');
    Route::post('/store/pengaduan', [TambahAduanController::class, 'store'])->name('store-pengaduan');
    Route::get('/TmabahAduan/guest', [TambahAduanController::class, 'AduanGuest'])->name('tambahAduanGuest-pengaduan');
    Route::post('/aduans/{id}/vote', [TambahAduanController::class, 'updateVoting'])->name('aduan.vote');
    Route::post('/aduans/{id}/comment', [TambahAduanController::class, 'addComment'])->name('aduan.comment');
    Route::get('/aduan/{id}/view', [TambahAduanController::class, 'updateViewCount'])->name('aduan.updateViewCount');
});

// Staff Routes (Data Pengaduan & Status Pengaduan)
Route::middleware(['auth', 'staff.role'])->group(function () {
    Route::get('/index/pengaduan', [TambahAduanController::class, 'index'])->name('index-pengaduan');
    Route::put('/pengaduan/update-status/{id}', [TambahAduanController::class, 'updateStatus'])->name('update-status');
    Route::get('/edit/pengaduan/{id}', [TambahAduanController::class, 'edit'])->name('edit-pengaduan');
    Route::put('/edit/pengaduan2/{id}', [TambahAduanController::class, 'update'])->name('update-pengaduan');
    Route::delete('/hapus/dataPengaduan/{id}', [TambahAduanController::class, 'destroy'])->name('delete-pengaduan');
});

// Head Staff Routes (Kelola Akun)
Route::middleware(['auth', 'head_staff.role'])->group(function () {
    Route::get('/staff/akun', [StaffController::class, 'index'])->name('staff-index');
    Route::post('/staff/akun', [StaffController::class, 'store'])->name('staff.store');
    Route::get('/edit/akun/{id}', [StaffController::class, 'edit'])->name('staff-edit');
    Route::put('/update/akun/{id}', [StaffController::class, 'update'])->name('staff-update');
    Route::delete('/hapus/{id}', [StaffController::class, 'destroy'])->name('staff-delete');
});

// Export Routes (khusus Staff atau Admin)
Route::middleware(['auth', 'staff.role'])->group(function () {
    Route::get('/export-pengaduan', function (Illuminate\Http\Request $request) {
        $date = $request->input('date'); // Ambil tanggal dari input form
        return Excel::download(new AduanExport($date), 'data-pengaduan.xlsx');
    })->name('aduan.export');
    Route::get('/export-pengaduan/{id}', function ($id) {
        return Excel::download(new \App\Exports\SingleAduanExport($id), 'pengaduan-' . $id . '.xlsx');
    })->name('aduan.export.single');
});

// API Routes (Umum)
Route::get('/wilayah/provinsi', [TambahAduanController::class, 'getProvinces']);
Route::get('/wilayah/kabupaten/{province_id}', [TambahAduanController::class, 'getRegencies']);
Route::get('/wilayah/kecamatan/{regency_id}', [TambahAduanController::class, 'getDistricts']);
Route::get('/wilayah/desa/{district_id}', [TambahAduanController::class, 'getVillages']);
