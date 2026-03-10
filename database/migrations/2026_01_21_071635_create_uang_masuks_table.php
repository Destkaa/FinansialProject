<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   // Di dalam file xxxx_create_uang_masuks_table.php
public function up(): void
{
    Schema::create('uang_masuks', function (Blueprint $table) {
        $table->id();
        // Langsung buat di sini:
        $table->foreignId('id_user')->constrained('users')->onDelete('cascade');
        $table->foreignId('id_saldo')->constrained('saldos')->onDelete('cascade');
        $table->integer('nominal');
        $table->string('keterangan');
        $table->date('tanggal_uang_masuk');
        $table->timestamps();
    });
}

    public function down(): void
    {
        Schema::table('uang_masuks', function (Blueprint $table) {
            $table->dropForeign(['id_user']);
            $table->dropColumn('id_user');
        });

        Schema::table('uang_keluars', function (Blueprint $table) {
            $table->dropForeign(['id_user']);
            $table->dropColumn('id_user');
        });
    }
};