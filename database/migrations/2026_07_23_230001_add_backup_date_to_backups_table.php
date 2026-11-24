<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('backups', function (Blueprint $table) {
            $table->date('backup_date')->nullable()->after('status');
            $table->integer('retention_days')->default(30)->after('backup_date');
            $table->index('backup_date');
        });
    }

    public function down(): void
    {
        Schema::table('backups', function (Blueprint $table) {
            $table->dropIndex(['backup_date']);
            $table->dropColumn(['backup_date', 'retention_days']);
        });
    }
};
