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
        Schema::create('booking_kelas', function (Blueprint $table) {
            $table->id();
            $table->integer('id_user');
            $table->integer('id_kelas_activity');
            $table->integer('set_jam')->comment('contoh konfirmasi email 1 jam sebelum kehadiran');
            $table->boolean('is_send_email')->comment('status pengiriman pesan ke email / nomor hp');
            $table->tinyInteger('status_kehadiran')->comment('hadis / izin / tida hadir');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_kelas');
    }
};
