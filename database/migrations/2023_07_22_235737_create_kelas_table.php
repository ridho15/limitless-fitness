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
        Schema::create('kelas', function (Blueprint $table) {
            $table->id();
            $table->integer('id_user');
            $table->bigInteger('harga_real')->default(0);
            $table->boolean('is_active')->default(0);
            $table->integer('jumlah_pertemuan');
            $table->integer('min_customer');
            $table->integer('max_customer');
            $table->text('keterangan')->nullable();
            $table->integer('diskon')->comment('persentase');
            $table->string('nama_kelas');
            $table->integer('expired')->comment('jumlah hari');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelas');
    }
};
