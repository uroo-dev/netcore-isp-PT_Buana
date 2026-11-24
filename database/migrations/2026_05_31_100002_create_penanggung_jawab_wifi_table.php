<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penanggung_jawab_wifi', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100);
            $table->string('telepon', 20)->nullable();
            $table->string('wilayah', 100)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penanggung_jawab_wifi');
    }
};
