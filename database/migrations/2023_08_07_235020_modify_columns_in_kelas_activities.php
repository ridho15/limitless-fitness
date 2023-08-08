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
        Schema::table('kelas_activities', function (Blueprint $table) {
            $table->integer('id_kelas')->nullable()->change();
            $table->integer('id_ruangan')->nullable()->change();
            $table->dropColumn('cancellation_policy');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kelas_activities', function (Blueprint $table) {
            //
        });
    }
};
