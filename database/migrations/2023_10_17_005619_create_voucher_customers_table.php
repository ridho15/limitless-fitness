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
        Schema::create('voucher_customers', function (Blueprint $table) {
            $table->id();
            $table->integer('id_voucher');
            $table->integer('id_customer');
            $table->boolean('status')->comment('1 = aktif, 0 = tidak aktif');
            $table->dateTime('expired');
            $table->text('deskripsi')->nullable();
            $table->boolean('status_digunakan')->default(0)->comment('1 = digunakan , 0 = belum di gunakan');
            $table->dateTime('tanggal_digunakan')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('voucher_customers');
    }
};
