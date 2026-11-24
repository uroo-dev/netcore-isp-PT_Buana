<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('absensi', function (Blueprint $table) {
            $table->index(['tanggal', 'status', 'karyawan_id'], 'absensi_tanggal_status_karyawan');
        });

        Schema::table('slip_gaji', function (Blueprint $table) {
            $table->index(['bulan', 'tahun'], 'slip_gaji_bulan_tahun');
            $table->index('created_at', 'slip_gaji_created_at');
        });
    }

    public function down(): void
    {
        Schema::table('absensi', function (Blueprint $table) {
            $table->dropIndex('absensi_tanggal_status_karyawan');
        });

        Schema::table('slip_gaji', function (Blueprint $table) {
            $table->dropIndex('slip_gaji_bulan_tahun');
            $table->dropIndex('slip_gaji_created_at');
        });
    }
};
