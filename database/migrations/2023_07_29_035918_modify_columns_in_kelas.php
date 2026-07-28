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

        // Drop each column in separate operations to be compatible with SQLite
        Schema::table('kelas', function (Blueprint $table) {
            $table->dropColumn('harga_real');
        });
        Schema::table('kelas', function (Blueprint $table) {
            $table->dropColumn('jumlah_pertemuan');
        });
        Schema::table('kelas', function (Blueprint $table) {
            $table->dropColumn('diskon');
        });
        Schema::table('kelas', function (Blueprint $table) {
            $table->dropColumn('foto');
        });
        Schema::table('kelas', function (Blueprint $table) {
            $table->dropColumn('expired');
        });

        Schema::table('kelas', function (Blueprint $table) {
            $table->tinyInteger('level')->default(0)->comment('0 = untuk semua level');
            $table->integer('duration')->default(0)->comment('Hitungan menit untuk durasi kelas');
            $table->text('bawaan')->nullable();
        });

        // Perform each rename in its own operation for SQLite compatibility
        Schema::table('kelas', function (Blueprint $table) {
            // note: renameColumn may require doctrine/dbal depending on DB driver
            $table->renameColumn('min_customer', 'min_anggota');
        });
        Schema::table('kelas', function (Blueprint $table) {
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
