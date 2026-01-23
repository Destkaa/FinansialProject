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
    Schema::create('uang_masuks', function (Blueprint $table) {
        $table->id();
        
        // Menggunakan decimal untuk perhitungan uang yang akurat
        $table->decimal('nominal', 15, 2);
        
        $table->string('keterangan');
        
        // Menggunakan date agar mudah difilter (contoh: cari data bulan ini saja)
        $table->date('tanggal_uang_masuk');
        
        // Menghubungkan ke tabel saldos (Foreign Key)
        // constrained() otomatis mencari tabel 'saldos' berdasarkan nama kolom
        $table->foreignId('id_saldo')->constrained('saldos')->onDelete('cascade');
        
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('uang_masuks');
    }
};
