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
            $table->dropColumn(['id_user', 'image', 'is_show']);
            $table->string('nama');
            $table->renameColumn('jumlah_training','jumlah_pertemuan');
            $table->integer('valid_day')->default(0);
            $table->tinyInteger('kategori_kelas');
            $table->string('eligible');
            $table->string('promote_code')->nullable();
            $table->tinyInteger('type_kelas');
            $table->integer('location')->default(0)->comment('0 = All location');
            $table->integer('diskon')->default(0);
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
