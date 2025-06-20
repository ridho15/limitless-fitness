<?php

namespace Database\Factories;

use App\Models\Location;
use App\Models\Paket;
use App\Models\PaketLocation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PaketLocation>
 */
class PaketLocationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        do {
            $id_paket = Paket::inRandomOrder()->first()->id;
            $id_location = Location::inRandomOrder()->first()->id;
            $paketLocation = PaketLocation::where('id_paket', $id_paket)
            ->where('id_location', $id_location)->first();
        } while ($paketLocation);
        return [
            'id_paket' => $id_paket,
            'id_location' => $id_location
        ];
    }
}
