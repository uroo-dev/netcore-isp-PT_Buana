<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pelanggan', function (Blueprint $table) {
            $table->foreignId('paket_id')->nullable()->constrained('paket_wifi')->nullOnDelete();
            $table->foreignId('penanggung_jawab_id')->nullable()->constrained('penanggung_jawab_wifi')->nullOnDelete();
            $table->enum('status', ['on', 'off'])->default('on');
            $table->date('tanggal_off')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('pelanggan', function (Blueprint $table) {
            $table->dropForeign(['paket_id']);
            $table->dropForeign(['penanggung_jawab_id']);
            $table->dropColumn(['paket_id', 'penanggung_jawab_id', 'status', 'tanggal_off']);
        });
    }
};
