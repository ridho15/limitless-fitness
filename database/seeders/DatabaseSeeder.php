<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        $faker = Factory::create();
        $user = User::updateOrCreate([
            'email' => 'ralamzah@gmail.com'
        ], [
            'id' => $faker->ean8(),
            'name' => 'Ridho Al Amzah',
            'email' => 'ralamzah@gmail.com',
            'password' => Hash::make('ridho123'),
            'no_hp' => '082163256603',
            'alamat' => 'Indonesia'
        ]);

        $role = Role::updateOrCreate(['name' => 'Super Admin'], ['name' => 'Super Admin']);
        $user->assignRole($role);

        Role::updateOrCreate([
            'name' => 'Customer'
        ], [
            'name' => 'Customer'
        ]);

        Role::updateOrCreate([
            'name' => 'Admin'
        ], [
            'name' => 'Admin'
        ]);

        Role::updateOrCreate([
            'name' => 'Instruktur'
        ], [
            'name' => 'Instruktur'
        ]);
    }
}
