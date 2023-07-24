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
        Schema::create('customer_kelas', function (Blueprint $table) {
            $table->id();
            $table->integer('id_user')->comment('User customer');
            $table->integer('id_kelas');
            $table->tinyInteger('status')->comment('1 = avaiable, 0 dibatalkan, 2 belum bayar');
            $table->dateTime('tanggal_bayar')->nullable();
            $table->string('bukti_bayar')->nullable();
            $table->integer('jumlah_pertemuan')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_kelas');
    }
};
