<?php

namespace Database\Factories;

use App\Models\Location;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Kelas>
 */
class KelasFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'is_active' => true,
            'min_anggota' => random_int(1, 5),
            'max_anggota' => random_int(10, 20),
            'keterangan' => $this->faker->sentence(),
            'nama_kelas' => $this->faker->word(),
            'honor' => 0,
            'id_location' => Location::inRandomOrder()->first()->id,
            'waktu_buka' => $this->faker->time(),
            'waktu_tutup' => $this->faker->time(),
        ];
    }
}
