<?php

namespace Database\Factories;

use App\Models\Kelas;
use App\Models\KelasPaket;
use App\Models\Paket;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\KelasPaket>
 */
class KelasPaketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        do {
            $id_kelas = Kelas::inRandomOrder()->first()->id;
            $id_paket = Paket::inRandomOrder()->first()->id;
            $kelasPaket = KelasPaket::where('id_kelas', $id_kelas)->where('id_paket', $id_paket)->first();
        } while ($kelasPaket);
        return [
            'id_kelas' => $id_kelas,
            'id_paket' => $id_paket
        ];
    }
}
