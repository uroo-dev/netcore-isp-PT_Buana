<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('log_aktivitas', function (Blueprint $table) {
            $table->string('tipe', 20)->default('aksi')->after('aksi');
            $table->string('ip', 45)->nullable()->after('data_baru');
            $table->unsignedSmallInteger('status_code')->nullable()->after('ip');
        });
    }

    public function down(): void
    {
        Schema::table('log_aktivitas', function (Blueprint $table) {
            $table->dropColumn(['tipe', 'ip', 'status_code']);
        });
    }
};
