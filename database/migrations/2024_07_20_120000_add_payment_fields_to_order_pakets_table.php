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
        Schema::table('order_pakets', function (Blueprint $table) {
            if (!Schema::hasColumn('order_pakets', 'public_token')) {
                $table->uuid('public_token')->nullable();
            }

            if (!Schema::hasColumn('order_pakets', 'payment_method')) {
                $table->string('payment_method', 25)->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_pakets', function (Blueprint $table) {
            if (Schema::hasColumn('order_pakets', 'payment_method')) {
                $table->dropColumn('payment_method');
            }

            if (Schema::hasColumn('order_pakets', 'public_token')) {
                $table->dropColumn('public_token');
            }
        });
    }
};
