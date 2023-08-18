<?php

namespace App\Imports;

use App\Models\BookingKelas;
use App\Models\KelasActivity;
use App\Models\LocationStudio;
use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Validators\Failure;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ImportBookingKelas implements ToModel, WithValidation, WithHeadingRow, SkipsEmptyRows, SkipsOnError, SkipsOnFailure
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
    }

    public function model(array $row)
    {
        $email = Str::slug($row['nama']) . '@gmail.com';
        $user = User::where('email', $email)->first();
        $instruktur = User::where('name', '-')->first();
        $locationStudio = LocationStudio::first();
        for ($i = 1; $i <= 24; $i++) {
            if ($user != null && array_key_exists($i, $row) && $row[$i] != null) {
                $tanggal = Date::excelToDateTimeObject($row[$i])->format('Y-m-d H:i:s');
                $kelasActivity = KelasActivity::updateOrCreate([
                    'tanggal' => $tanggal,
                    'id_user' => $instruktur->id
                ], [
                    'tanggal' => $tanggal,
                    'id_user', $instruktur->id,
                    'id_location_studio' => $locationStudio->id,
                    'jam_mulai' => '08:00:00',
                    'status' => 3,
                    'level' => 0,
                    'nama_latihan' => '-'
                ]);

                BookingKelas::updateOrCreate([
                    'id_user' => $user->id,
                    'id_kelas_activity' => $kelasActivity->id
                ], [
                    'id_user' => $user->id,
                    'id_kelas_activity' => $kelasActivity->id,
                    'is_send_email' => 1,
                    'status_kehadiran' => 1,
                ]);
            } else {
                break;
            }
        }
    }

    public function rules(): array
    {
        return [
            'nama' => 'required|string'
        ];
    }

    public function onFailure(Failure ...$failures)
    {
    }

    public function onError(\Throwable $e)
    {
    }
}
