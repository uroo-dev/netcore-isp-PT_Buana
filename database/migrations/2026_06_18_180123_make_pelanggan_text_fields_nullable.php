<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pelanggan', function (Blueprint $table) {
            $table->string('nama', 100)->nullable()->change();
            $table->string('telepon', 20)->nullable()->change();
            $table->text('alamat')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pelanggan', function (Blueprint $table) {
            $table->string('nama', 100)->nullable(false)->change();
            $table->string('telepon', 20)->nullable(false)->change();
            $table->text('alamat')->nullable(false)->change();
        });
    }
};
