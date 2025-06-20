<?php

namespace Database\Seeders;

use App\Models\LocationStudio;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LocationStudioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LocationStudio::factory()->count(10)->create();
    }
}
