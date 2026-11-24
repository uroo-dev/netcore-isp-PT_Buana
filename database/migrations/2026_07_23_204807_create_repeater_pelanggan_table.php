<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('repeater_pelanggan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('repeater_id')->constrained('repeaters')->cascadeOnDelete();
            $table->integer('bulan');
            $table->integer('tahun');
            $table->string('nama_pelanggan', 100);
            $table->string('paket', 100)->nullable();
            $table->decimal('harga', 15, 2)->default(0);
            $table->decimal('dpp', 15, 2)->default(0);
            $table->decimal('ppn', 15, 2)->default(0);
            $table->decimal('bhp_uso', 15, 2)->default(0);
            $table->timestamps();

            $table->index(['repeater_id', 'bulan', 'tahun'], 'rp_bulan_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('repeater_pelanggan');
    }
};
