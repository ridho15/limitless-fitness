<?php

namespace Database\Seeders;

use App\Models\PaketLocation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaketLocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PaketLocation::factory()->count(50)->create();
    }
}
