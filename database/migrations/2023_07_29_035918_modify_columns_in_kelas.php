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
        Schema::table('kelas', function (Blueprint $table) {
            $table->dropColumn(['harga_real', 'jumlah_pertemuan', 'diskon', 'foto', 'expired']);
            $table->tinyInteger('level')->default(0)->comment('0 = untuk semua level');
            $table->integer('duration')->default(0)->comment('Hitungan menit untuk durasi kelas');
            $table->text('bawaan')->nullable();
            $table->renameColumn('min_customer', 'min_anggota');
            $table->renameColumn('max_customer', 'max_anggota');
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
