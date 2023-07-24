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
        Schema::create('jurnals', function (Blueprint $table) {
            $table->id();
            $table->integer('id_coa');
            $table->string('nama');
            $table->bigInteger('jumlah_uang');
            $table->boolean('tipe')->comment('1 pemasukan, 0 pengeluaran');
            $table->string('struk')->nullable()->comment('bukti_pembayaran');
            $table->dateTime('tanggal')->comment('tanggal melakukan pembayaran');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jurnals');
    }
};
