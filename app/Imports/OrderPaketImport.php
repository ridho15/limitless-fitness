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
            // $kelas = Kelas::where('nama_kelas', $row[4])->first();
            $paket = Paket::where('nama', $row[2])->first();
            $tanggalPembayaran = Date::excelToDateTimeObject($row[1])->format('Y-m-d H:i:s');
            // if (is_string($row[5])) {
            //     $sisa = 0;
            // } else {
            //     $sisa = (int) $row[5];
            // }

            if ($user && $paket) {
                // $paket = Paket::where('id_kelas', $kelas->id)
                //     ->where('jumlah_pertemuan', $row[2])
                //     ->first();
                $expired_date = Carbon::parse($tanggalPembayaran)->addDays($paket ? $paket->valid_day : 0)->isoFormat('YYYY-MM-DD H:m:s');
                $data = [
                    'id_user' => $user->id,
                    'tanggal_pembayaran' => $tanggalPembayaran,
                    'jumlah_pertemuan' => $paket,
                    'harga' => $paket ? $paket->harga : 0,
                    'status' => 1,
                    'id_paket' => $paket ? $paket->id : 0,
                    'is_active' => 1,
                    'expired_date' => $expired_date,
                    'sisa' => $paket->jumlah_pertemuan
                ];

                $orderPaket = OrderPaket::updateOrCreate([
                    'id_user' => $user->id,
                    'jumlah_pertemuan' => $paket ? $paket->jumlah_pertemuan : 0,
                    'tanggal_pembayaran' => $tanggalPembayaran,
                    'sisa' => $paket->jumlah_pertemuan,
                    'id_paket' => $paket ? $paket->id : 0
                ], $data);

                // $orderPaket->update([
                //     'jumlah_pertemuan' => $row[2] + $row[3]
                // ]);
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
                ->where('jumlah_pertemuan', $row['jumlah_pertemuan'])
                ->first();
            $expired_date = Carbon::parse($tanggalPembayaran)->addDays($paket ? $paket->valid_day : 0)->isoFormat('YYYY-MM-DD H:m:s');
            $data = [
                'id_user' => $user->id,
                'tanggal_pembayaran' => $tanggalPembayaran,
                'jumlah_pertemuan' => $row['jumlah_pertemuan'],
                'harga' => $paket ? $paket->harga : 0,
                'status' => 1,
                'id_paket' => $paket ? $paket->id : 0,
                'is_active' => 1,
                'expired_date' => $expired_date,
                'sisa' => $sisa
            ];

            $orderPaket = OrderPaket::updateOrCreate([
                'id_user' => $user->id,
                'jumlah_pertemuan' => $paket ? $paket->jumlah_pertemuan : 0,
                'tanggal_pembayaran' => $tanggalPembayaran,
                'sisa' => $sisa,
                'id_paket' => $paket ? $paket->id : 0
            ], $data);

            $orderPaket->update([
                'jumlah_pertemuan' => $row['jumlah_pertemuan'] + $row['penambahan']
            ]);
        }
    }

    // public function model(array $row)
    // {
    //     $email = Str::slug($row[0]) . '@gmail.com';
    //     $user = User::where('email', $email)->first();
    //     $kelas = Kelas::where('nama_kelas', $row[4])->first();
    //     $tanggalPembayaran = Date::excelToDateTimeObject($row[1])->format('Y-m-d H:i:s');
    //     if (is_string($row[3])) {
    //         $sisa = 0;
    //     } else {
    //         $sisa = (int) $row[3];
    //     }

    //     if ($user && $kelas) {
    //         $paket = Paket::where('id_kelas', $kelas->id)
    //             ->where('jumlah_pertemuan', $row[2])
    //             ->first();
    //         $expired_date = Carbon::parse($tanggalPembayaran)->addDays($paket ? $paket->valid_day : 0)->isoFormat('YYYY-MM-DD H:m:s');
    //         $data = [
    //             'id_user' => $user->id,
    //             'tanggal_pembayaran' => $tanggalPembayaran,
    //             'jumlah_pertemuan' => (int) $row[2],
    //             'harga' => $paket ? $paket->harga : 0,
    //             'status' => 1,
    //             'id_paket' => $paket ? $paket->id : 0,
    //             'is_active' => 1,
    //             'expired_date' => $expired_date,
    //             'sisa' => $sisa
    //         ];

    //         try {
    //             $orderPaket = OrderPaket::updateOrCreate([
    //                 'id_user' => $user->id,
    //                 'tanggal_pembayaran' => $tanggalPembayaran,
    //                 'jumlah_pertemuan' => $paket ? $paket->jumlah_pertemuan : 0,
    //                 'id_paket' => $paket ? $paket->id : 0
    //             ], $data);

    //             return $orderPaket;
    //         } catch (\Throwable $th) {
    //             dd($th->getMessage());
    //         }
    //     }
    // }

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
            'jumlah_pertemuan' => [
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
