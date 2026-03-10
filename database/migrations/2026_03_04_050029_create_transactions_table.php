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