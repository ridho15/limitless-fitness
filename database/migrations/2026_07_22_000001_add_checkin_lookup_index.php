<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('check_in_outs', fn (Blueprint $table) =>
            $table->index(['id_user', 'id_location', 'status', 'created_at'], 'checkin_dedupe_lookup')
        );
    }

    public function down(): void
    {
        Schema::table('check_in_outs', fn (Blueprint $table) => $table->dropIndex('checkin_dedupe_lookup'));
    }
};
