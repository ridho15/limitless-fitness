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
            'nama' => $this->faker->words(2, true),
            'harga' => $this->faker->numberBetween(100000, 1000000),
            'valid_month' => $this->faker->numberBetween(1, 12),
            'promote_code' => strtoupper($this->faker->bothify('PROMO###')),
            'diskon' => $this->faker->randomFloat(2, 0, 50),
            'is_many_times' => $this->faker->boolean(),
            'is_show_public' => $this->faker->boolean(),
            'price_per_additional' => $this->faker->numberBetween(10000, 100000),
            'additional_member' => $this->faker->numberBetween(0, 5),
            'pro_rata' => $this->faker->randomFloat(2, 0, 1),
            'signup_fee' => $this->faker->numberBetween(0, 200000),
        ];
    }
}
