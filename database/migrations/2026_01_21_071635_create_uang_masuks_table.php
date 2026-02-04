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
        Schema::create('uang_masuk', function (Blueprint $table) {
            $table->id();
            $table->decimal('nominal', 15, 2);
            $table->string('keterangan');
            $table->date('tanggal_uang_masuk'); 
            
            // Menggunakan saldo_id (standar Laravel: nama_table_singuler_id)
            $table->foreignId('saldo_id')->constrained('saldos')->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Nama tabel harus sama dengan yang di fungsi up()
        Schema::dropIfExists('uang_masuks');
    }
};