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
        Schema::create('paket_details', function (Blueprint $table) {
            $table->id();
            $table->integer('id_paket');
            // $table->foreign('id_paket')->references('id')->on('pakets')->onDelete('cascade');
            $table->boolean('is_show')->default(0);
            $table->boolean('is_check')->default(0);
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
        Schema::dropIfExists('paket_details');
    }
};
