<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            
            // 1. Pastikan tabel 'users' sudah ada sebelum menjalankan ini
            // 2. foreignId harus sesuai dengan tipe data id di tabel users (biasanya bigIncrements)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); 
            
            $table->date('tanggal');
            $table->string('keterangan');
            $table->enum('kategori', ['Pemasukan', 'Pengeluaran']);
            $table->bigInteger('nominal');
            $table->string('status')->default('Success');
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};