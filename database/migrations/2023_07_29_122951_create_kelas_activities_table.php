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
        Schema::create('kelas_activities', function (Blueprint $table) {
            $table->id();
            $table->integer('id_kelas');
            $table->integer('max_anggota');
            $table->integer('id_location_studio');
            $table->dateTime('jam_latihan');
            $table->integer('id_user')->comment('instruktur');
            $table->tinyInteger('status')->comment('set / sedang berlangsung / dibatalkan / sudah lewat');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelas_activities');
    }
};
