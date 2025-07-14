<?php

namespace Database\Seeders;

use App\Models\KelasPaket;
use Illuminate\Database\Seeder;

class KelasPaketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        KelasPaket::factory()->count(50)->create();
    }
}
