<?php

namespace App\Imports;

use App\Models\Kelas;
use App\Models\OrderPaket;
use App\Models\Paket;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Validators\Failure;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class OrderPaketImport implements ToModel, WithValidation, WithHeadingRow, SkipsEmptyRows, SkipsOnError, SkipsOnFailure
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $email = Str::slug($row[0]) . '@gmail.com';
            $user = User::where('email', $email)->first();
            $paket = Paket::where('nama', $row[2])->first();
            $tanggalPembayaran = Date::excelToDateTimeObject($row[1])->format('Y-m-d H:i:s');

            if ($user && $paket) {
                $expired_date = Carbon::parse($tanggalPembayaran)->addDays($paket ? $paket->valid_day : 0)->isoFormat('YYYY-MM-DD H:m:s');
                $data = [
                    'id_user' => $user->id,
                    'tanggal_pembayaran' => $tanggalPembayaran,
                    'harga' => $paket ? $paket->harga : 0,
                    'status' => 1,
                    'id_paket' => $paket ? $paket->id : 0,
                    'is_active' => 1,
                    'expired_date' => $expired_date,
                ];

                $orderPaket = OrderPaket::updateOrCreate([
                    'id_user' => $user->id,
                    'tanggal_pembayaran' => $tanggalPembayaran,
                    'id_paket' => $paket ? $paket->id : 0
                ], $data);
            }
        }
    }

    public function model(array $row)
    {
        $email = Str::slug($row['nama']) . '@gmail.com';
        $user = User::where('email', $email)->first();
        $kelas = Kelas::where('nama_kelas', $row['kelas'])->first();
        // $paket = Paket::where('nama', $row['paket'])->first();
        $tanggalPembayaran = Date::excelToDateTimeObject($row['tanggal'])->format('Y-m-d H:i:s');
        if (is_string($row['sisa'])) {
            $sisa = 0;
        } else {
            $sisa = (int) $row['sisa'];
        }

        if ($user && $kelas) {
            $paket = Paket::where('id_kelas', $kelas->id)
                ->first();
            $expired_date = Carbon::parse($tanggalPembayaran)->addDays($paket ? $paket->valid_day : 0)->isoFormat('YYYY-MM-DD H:m:s');
            $data = [
                'id_user' => $user->id,
                'tanggal_pembayaran' => $tanggalPembayaran,
                'harga' => $paket ? $paket->harga : 0,
                'status' => 1,
                'id_paket' => $paket ? $paket->id : 0,
                'is_active' => 1,
                'expired_date' => $expired_date,
                'sisa' => $sisa
            ];

            $orderPaket = OrderPaket::updateOrCreate([
                'id_user' => $user->id,
                'tanggal_pembayaran' => $tanggalPembayaran,
                'sisa' => $sisa,
                'id_paket' => $paket ? $paket->id : 0
            ], $data);
        }
    }

    public function rules(): array
    {
        return [
            'nama' => [
                'required',
                'string'
            ],
            'tanggal' => [
                'required'
            ],
            'sisa' => [
                'required'
            ],
            'kelas' => [
                'required'
            ]
        ];
    }

    public function onFailure(Failure ...$failures)
    {
    }

    public function onError(\Throwable $e)
    {
    }
}
