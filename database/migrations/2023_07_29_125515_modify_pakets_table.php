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
        Schema::table('pakets', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->integer('jumlah_pertemuan');
            $table->bigInteger('harga');
            $table->integer('valid_day')->default(0);
            $table->tinyInteger('kategori_kelas');
            $table->string('eligible');
            $table->string('promote_code')->nullable();
            $table->tinyInteger('type_kelas');
            $table->integer('location')->default(0)->comment('0 = All location');
            $table->integer('diskon')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pakets', function (Blueprint $table) {
            //
        });
    }
};
