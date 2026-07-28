<?php

namespace Database\Factories;

use App\Models\OrderPaket;
use App\Models\User;
use App\Models\Paket;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderPaket>
 */
class OrderPaketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_user' => User::factory(),
            'id_paket' => Paket::factory(),
            'harga' => $this->faker->numberBetween(100000, 1000000),
            'status' => $this->faker->randomElement([1, 2, 3]), // 1=aktif, 2=belum lunas, 3=cancelled
            'is_active' => $this->faker->boolean(),
            'expired_date' => $this->faker->dateTimeBetween('now', '+1 year'),
            'created_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ];
    }

    /**
     * Indicate that the order is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 1,
            'is_active' => 1,
        ]);
    }

    /**
     * Indicate that the order is pending payment.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 2,
            'is_active' => 0,
        ]);
    }

    /**
     * Indicate that the order is cancelled.
     */
    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 3,
            'is_active' => 0,
        ]);
    }
}