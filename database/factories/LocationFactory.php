<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Location>
 */
class LocationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama' => $this->faker->name(),
            'deskripsi' => $this->faker->sentence(),
            'no_hp' => "082163256603",
            'alamat' => $this->faker->address(),
            'email' => $this->faker->email(),
            'url_location' => $this->faker->url(),
            'image' => $this->faker->imageUrl(),
            'nama_bank' => $this->faker->name(),
            'no_rekening' => $this->faker->randomNumber(),
            'atas_nama' => $this->faker->name()
        ];
    }
}
