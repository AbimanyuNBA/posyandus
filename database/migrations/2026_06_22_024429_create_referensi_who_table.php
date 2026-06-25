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
        Schema::create('referensi_who', function (Blueprint $table) {
            $table->id();
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->integer('usia_bulan');           // 0–60
            $table->enum('indikator', ['BB/U', 'TB/U', 'BB/TB']);
            $table->decimal('l_value', 10, 7);       // Lambda
            $table->decimal('m_value', 10, 7);       // Median
            $table->decimal('s_value', 10, 7);       // CV
            $table->index(['jenis_kelamin', 'usia_bulan', 'indikator']);
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referensi_who');
    }
};
