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
        Schema::rename('booking_orders', 'order_pakets');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booking_order', function (Blueprint $table) {
            //
        });
    }
};
