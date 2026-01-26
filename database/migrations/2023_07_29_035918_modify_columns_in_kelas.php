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
        // Split modifications into multiple schema operations because SQLite
        // does not support multiple drop/rename operations in a single table
        // modification. Splitting helps local/test environments using SQLite.

        Schema::table('kelas', function (Blueprint $table) {
            $table->dropColumn(['harga_real', 'jumlah_pertemuan', 'diskon', 'foto', 'expired']);
        });

        Schema::table('kelas', function (Blueprint $table) {
            $table->tinyInteger('level')->default(0)->comment('0 = untuk semua level');
            $table->integer('duration')->default(0)->comment('Hitungan menit untuk durasi kelas');
            $table->text('bawaan')->nullable();
        });

        Schema::table('kelas', function (Blueprint $table) {
            // renaming columns should be done in a separate operation
            // note: renameColumn may require doctrine/dbal depending on DB driver
            $table->renameColumn('min_customer', 'min_anggota');
            $table->renameColumn('max_customer', 'max_anggota');
        });

        Schema::table('kelas', function (Blueprint $table) {
            $table->tinyInteger('type')->comment('online / onsite');
            $table->tinyInteger('category')->comment('private / group');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kelas', function (Blueprint $table) {
            //
        });
    }
};
