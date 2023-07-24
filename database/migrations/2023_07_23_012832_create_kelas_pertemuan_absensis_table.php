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
        Schema::create('kelas_pertemuan_absensis', function (Blueprint $table) {
            $table->id();
            $table->integer('id_kelas_pertemuan');
            $table->integer('id_user');
            $table->dateTime('waktu_masuk')->nullable();
            $table->tinyInteger('status')->comment('1 masuk, 2 izin, 0 tidak hadir');
            $table->text('keterangan')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelas_pertemuan_absensis');
    }
};
