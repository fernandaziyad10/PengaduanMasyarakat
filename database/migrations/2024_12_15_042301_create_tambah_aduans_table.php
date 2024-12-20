<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tambah_aduans', function (Blueprint $table) {
            $table->id();
            $table->text('description');  
            $table->enum('type', ['KEJAHATAN', 'PEMBANGUNAN', 'SOSIAL']);   
            $table->string('province');  
            $table->string('regency');   
            $table->string('district');  
            $table->string('village');   
            $table->string('image');   
            $table->text('statement')->nullable()->change();  
             $table->integer('voting')->default(0);
            $table->integer('viewers')->default(0);
            $table->text('comments')->nullable();  
            $table->string('status')->default('Belum Diproses');
            $table->timestamps();  
        });
        
        
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tambah_aduans');
    }
};
