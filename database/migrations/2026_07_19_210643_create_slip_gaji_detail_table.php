<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('slip_gaji_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('slip_gaji_id')->constrained('slip_gaji')->cascadeOnDelete();
            $table->foreignId('karyawan_id')->constrained('karyawans')->cascadeOnDelete();
            $table->decimal('gaji_pokok', 15, 2)->default(0);
            $table->integer('total_hadir')->default(0);
            $table->integer('total_terlambat')->default(0);
            $table->integer('total_absen')->default(0);
            $table->decimal('total_gaji', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('slip_gaji_detail');
    }
};
