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
        // Pastikan baris ini ada di sini
        $table->foreignId('id_user')->constrained('users')->onDelete('cascade');
        $table->foreignId('id_saldo')->constrained('saldos')->onDelete('cascade');
        $table->integer('nominal');
        $table->string('keterangan');
        $table->date('tanggal_uang_keluar');
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
