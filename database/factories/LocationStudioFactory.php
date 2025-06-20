<?php

namespace Database\Factories;

use App\Models\Location;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LocationStudio>
 */
class LocationStudioFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_location' => Location::inRandomOrder()->first()->id,
            'nama' => $this->faker->name(),
            'equipment' => $this->faker->sentence(),
            'image' => $this->faker->name(),
            'no_hp' => "082163256603"
        ];
    }
}
