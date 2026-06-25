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
    Schema::create('balita', function (Blueprint $table) {
        $table->id();
        $table->foreignId('posyandu_id')->constrained('posyandu')->cascadeOnDelete();
        $table->string('nama');
        $table->string('nik', 16)->nullable()->unique();
        $table->date('tanggal_lahir');
        $table->enum('jenis_kelamin', ['L', 'P']);
        $table->string('nama_ortu');
        $table->string('no_hp_ortu')->nullable();
        $table->string('alamat')->nullable();
        $table->timestamps();
        $table->softDeletes();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('balita');
    }
};
