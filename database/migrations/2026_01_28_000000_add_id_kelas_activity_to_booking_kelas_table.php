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
        if (! Schema::hasColumn('booking_kelas', 'id_kelas_activity')) {
            Schema::table('booking_kelas', function (Blueprint $table) {
                $table->integer('id_kelas_activity')->nullable()->after('id_user');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('booking_kelas', 'id_kelas_activity')) {
            Schema::table('booking_kelas', function (Blueprint $table) {
                $table->dropColumn('id_kelas_activity');
            });
        }
    }
};
