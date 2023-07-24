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
        Schema::create('kelas_pertamuans', function (Blueprint $table) {
            $table->id();
            $table->integer('id_kelas');
            $table->dateTime('datetime_avaiable');
            $table->tinyInteger('status')->comment('0 dibatalkan, 1 avaiable');
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
        Schema::dropIfExists('kelas_pertamuans');
    }
};
