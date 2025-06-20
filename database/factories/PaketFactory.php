<?php

namespace Database\Factories;

use App\Models\Kelas;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Paket>
 */
class PaketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama' => $this->faker->word(),
            'harga' => random_int(100000, 1000000),
            'valid_month' => array_rand([1, 3, 6, 12]),
            'diskon' => random_int(0, 10),
            'promote_code' => Str::upper($this->faker->word()),
            'id_kelas' => Kelas::inRandomOrder()->first()->id,
            'is_show_public' => true,
            'price_per_additional' => 0,
        ];
    }
}
