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
    Schema::create('pengukuran', function (Blueprint $table) {
        $table->id();
        $table->foreignId('balita_id')->constrained('balita')->cascadeOnDelete();
        $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
        $table->date('tanggal_ukur');
        $table->decimal('berat_badan', 5, 2);  // kg, contoh: 12.50
        $table->decimal('tinggi_badan', 5, 1);  // cm, contoh: 85.5
        $table->integer('usia_bulan');           // dihitung otomatis saat input
        $table->decimal('zscore_bbu', 5, 3)->nullable();  // BB/U
        $table->decimal('zscore_tbu', 5, 3)->nullable();  // TB/U
        $table->decimal('zscore_bbtb', 5, 3)->nullable(); // BB/TB
        $table->enum('status_gizi', [
            'normal',
            'gizi_kurang',
            'gizi_buruk',
            'gizi_lebih',
            'obesitas',
            'stunting',
            'severely_stunting',
        ]);
        $table->text('catatan')->nullable();
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengukuran');
    }
};
