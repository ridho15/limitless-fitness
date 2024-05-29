<?php

namespace App\Imports;

use App\Http\Controllers\HelperController;
use App\Models\User;
use Faker\Factory;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Validators\Failure;

class UsersImport implements ToModel, SkipsEmptyRows, WithValidation, SkipsOnFailure
{
    use Importable;
    public function model(array $row)
    {
        $email = Str::slug($row[0]) . '@gmail.com';
        $no_hp = str_replace('-', '', $row[1]);
        $no_hp = strlen($no_hp) < 12 ? '0' . $no_hp : $no_hp;

        $user = User::where('email', $email)
            ->first();

        if ($user) {
            $no_hp = $user->no_hp;
            $user->delete();
        }
        $id = HelperController::generateIdUser();
        return User::updateOrCreate([
            'email' => $email
        ], [
            'id' => $id,
            'name' => $row[0],
            'password' => Hash::make('12345678'),
            'email' => $email,
            'no_hp' => $no_hp,
            'alamat' => '-',
            'status' => 1
        ])->assignRole('Customer');
    }

    public function rules(): array
    {
        return [
            '0' => [
                'required',
                'string'
            ],
            '1' => [
                'required'
            ]
        ];
    }

    public function onFailure(Failure ...$failures)
    {
        // Handle the failures how you'd like.
    }
}
