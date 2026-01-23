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
    Schema::create('uang_keluars', function (Blueprint $table) {
        $table->id();
        
        $table->decimal('nominal', 15, 2);
        $table->string('keterangan');
        
        // Diganti jadi tanggal_uang_keluar agar sesuai fungsinya
        $table->date('tanggal_uang_keluar'); 
        
        // Disamakan jadi saldo_id agar konsisten dengan tabel uang_masuks
        $table->foreignId('id_saldo')->constrained('saldos')->onDelete('cascade');
        
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('uang_keluars');
    }
};
