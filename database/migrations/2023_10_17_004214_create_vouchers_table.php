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
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->text('deskripsi')->nullable();
            $table->dateTime('expired');
            $table->string('image')->nullable();
            $table->boolean('status')->default(0)->comment('1 = aktif, 0 = tidak aktif');
            $table->bigInteger('disc_harga')->nullable()->default(0);
            $table->integer('disc_percent')->nullable()->default(0);
            $table->dateTime('tanggal_aktif')->nullable();
            $table->integer('lama_aktif')->comment('jumlah hari');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
