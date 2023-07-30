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
        Schema::create('booking_orders', function (Blueprint $table) {
            $table->id();
            $table->integer('id_user');
            $table->integer('id_paket');
            $table->bigInteger('harga');
            $table->tinyInteger('status')->comment('lunas / batal / belum lunas');
            $table->string('tanda_tangan')->nullable();
            $table->boolean('is_active')->comment('Aktif / Tidak Aktif');
            $table->dateTime('expired_date');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_orders');
    }
};
