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
        Schema::create('location_studios', function (Blueprint $table) {
            $table->id();
            $table->integer('id_location');
            $table->string('nama');
            $table->tinyInteger('level');
            $table->string('equipment')->nullable();
            $table->string('image');
            $table->integer('max_peserta')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('location_studios');
    }
};
