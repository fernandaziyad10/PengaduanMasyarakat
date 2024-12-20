<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStaffsTable extends Migration
{
    public function up()
    {
        Schema::create('staffs', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama pengguna
            $table->string('email')->unique(); // Email unik
            $table->string('password'); // Kata sandi
            $table->enum('role', ['admin', 'staff'])->default('staff'); // Peran pengguna
            $table->boolean('status')->default(true); // Status aktif/nonaktif
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('staffs');
    }
}
