<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Indexes untuk pelanggan (sering difilter by tipe, status, penanggung_jawab)
        Schema::table('pelanggan', function (Blueprint $table) {
            $table->index('tipe');
            $table->index('status');
            $table->index('penanggung_jawab_id');
            $table->index('nama');
        });

        // Indexes untuk invoice (paling sering di-query)
        Schema::table('invoice', function (Blueprint $table) {
            $table->index('status');
            $table->index('sumber');
            $table->index('tanggal');
            $table->index(['pelanggan_id', 'status']);
            $table->index(['pelanggan_id', 'tanggal']);
        });

        // Indexes untuk kas (sering diagregasi per bulan)
        Schema::table('kas', function (Blueprint $table) {
            $table->index('tanggal');
            $table->index('tipe');
            $table->index(['tanggal', 'tipe']);
            $table->index('kategori');
        });

        // Indexes untuk absensi (sering dicari per tanggal & karyawan)
        Schema::table('absensi', function (Blueprint $table) {
            $table->index('tanggal');
            $table->index('karyawan_id');
            $table->index(['karyawan_id', 'tanggal']);
        });

        // Indexes untuk invoice_detail (sering diagregasi per bulan)
        Schema::table('invoice_detail', function (Blueprint $table) {
            $table->index('created_at');
        });

        // Indexes untuk pembayaran (sering difilter by tanggal)
        Schema::table('pembayaran', function (Blueprint $table) {
            $table->index('tanggal');
        });
    }

    public function down(): void
    {
        Schema::table('pelanggan', function (Blueprint $table) {
            $table->dropIndex(['tipe']);
            $table->dropIndex(['status']);
            $table->dropIndex(['penanggung_jawab_id']);
            $table->dropIndex(['nama']);
        });

        Schema::table('invoice', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['sumber']);
            $table->dropIndex(['tanggal']);
            $table->dropIndex(['pelanggan_id', 'status']);
            $table->dropIndex(['pelanggan_id', 'tanggal']);
        });

        Schema::table('kas', function (Blueprint $table) {
            $table->dropIndex(['tanggal']);
            $table->dropIndex(['tipe']);
            $table->dropIndex(['tanggal', 'tipe']);
            $table->dropIndex(['kategori']);
        });

        Schema::table('absensi', function (Blueprint $table) {
            $table->dropIndex(['tanggal']);
            $table->dropIndex(['karyawan_id']);
            $table->dropIndex(['karyawan_id', 'tanggal']);
        });

        Schema::table('invoice_detail', function (Blueprint $table) {
            $table->dropIndex(['created_at']);
        });

        Schema::table('pembayaran', function (Blueprint $table) {
            $table->dropIndex(['tanggal']);
        });
    }
};
