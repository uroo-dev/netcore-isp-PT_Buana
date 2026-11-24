<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pelanggan', function (Blueprint $table) {
            $table->index('paket_id', 'plg_paket');
        });

        Schema::table('invoice', function (Blueprint $table) {
            $table->index('user_id', 'inv_user');
            $table->index(['pelanggan_id', 'tanggal', 'status'], 'inv_plg_tgl_sts');
        });

        Schema::table('invoice_detail', function (Blueprint $table) {
            $table->index('invoice_id', 'idet_invoice');
            $table->index('produk_id', 'idet_produk');
        });

        Schema::table('pembayaran', function (Blueprint $table) {
            $table->index('invoice_id', 'byr_invoice');
            $table->index('user_id', 'byr_user');
        });

        Schema::table('kas', function (Blueprint $table) {
            $table->index('user_id', 'kas_user');
            $table->index(['tanggal', 'tipe', 'kategori'], 'kas_tgl_tipe_kat');
        });

        Schema::table('biaya_operasionals', function (Blueprint $table) {
            $table->index('user_id', 'bop_user');
            $table->index('kas_id', 'bop_kas');
            $table->index(['tanggal', 'kategori'], 'bop_tgl_kat');
        });

        Schema::table('absensi', function (Blueprint $table) {
            $table->index('user_id', 'abs_user');
            $table->index('created_by', 'abs_created');
        });

        Schema::table('log_stok', function (Blueprint $table) {
            $table->index('produk_id', 'lstok_produk');
            $table->index('cabang_id', 'lstok_cabang');
            $table->index('user_id', 'lstok_user');
            $table->index(['tanggal', 'aksi'], 'lstok_tgl_aksi');
        });

        Schema::table('log_aktivitas', function (Blueprint $table) {
            $table->index('user_id', 'logakt_user');
            $table->index('created_at', 'logakt_created');
        });

        Schema::table('slip_gaji_detail', function (Blueprint $table) {
            $table->index('karyawan_id', 'sgdet_karyawan');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->index('cabang_id', 'users_cabang');
        });
    }

    public function down(): void
    {
        Schema::table('pelanggan', fn (Blueprint $t) => $t->dropIndex('plg_paket'));
        Schema::table('invoice', fn (Blueprint $t) => $t->dropIndex(['inv_user', 'inv_plg_tgl_sts']));
        Schema::table('invoice_detail', fn (Blueprint $t) => $t->dropIndex(['idet_invoice', 'idet_produk']));
        Schema::table('pembayaran', fn (Blueprint $t) => $t->dropIndex(['byr_invoice', 'byr_user']));
        Schema::table('kas', fn (Blueprint $t) => $t->dropIndex(['kas_user', 'kas_tgl_tipe_kat']));
        Schema::table('biaya_operasionals', fn (Blueprint $t) => $t->dropIndex(['bop_user', 'bop_kas', 'bop_tgl_kat']));
        Schema::table('absensi', fn (Blueprint $t) => $t->dropIndex(['abs_user', 'abs_created']));
        Schema::table('log_stok', fn (Blueprint $t) => $t->dropIndex(['lstok_produk', 'lstok_cabang', 'lstok_user', 'lstok_tgl_aksi']));
        Schema::table('log_aktivitas', fn (Blueprint $t) => $t->dropIndex(['logakt_user', 'logakt_created']));
        Schema::table('slip_gaji_detail', fn (Blueprint $t) => $t->dropIndex('sgdet_karyawan'));
        Schema::table('users', fn (Blueprint $t) => $t->dropIndex('users_cabang'));
    }
};
