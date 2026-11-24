<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengaturan', function (Blueprint $table) {
            $table->string('backup_email', 255)->nullable()->after('alamat');
            $table->integer('backup_retention_days')->default(30)->after('backup_email');
            $table->integer('backup_max_storage_mb')->default(5000)->after('backup_retention_days');
        });
    }

    public function down(): void
    {
        Schema::table('pengaturan', function (Blueprint $table) {
            $table->dropColumn(['backup_email', 'backup_retention_days', 'backup_max_storage_mb']);
        });
    }
};
