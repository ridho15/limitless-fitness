<?php

namespace App\Http\Controllers;

use App\Models\BookingKelas;
use App\Models\KelasActivity;
use App\Models\OrderPaket;
use App\Models\User;
use Carbon\Carbon;

class HelperController extends Controller
{
    public function updateData()
    {
        $jadwal = Carbon::now()->addDays(2);
        $listKelasActivity = KelasActivity::whereDate('jam_mulai', $jadwal)->get();
        foreach ($listKelasActivity as $kelasActivity) {
            // Check jumlah anggota booking kelas
            $jumlahAnggotaMax = $kelasActivity->kelas->max_anggota;

            $bookingKelas = BookingKelas::where('id_kelas_activity', $kelasActivity->id)
                ->where(function ($query) {
                    $query->where('status_kehadiran', 1)
                        ->orWhere('status_kehadiran', null);
                })->count();
            if ($bookingKelas < $jumlahAnggotaMax) {
                $listUser = User::whereHas('roles', function ($query) {
                    $query->where('name', 'Customer');
                })->whereHas('orderPaket', function ($query) use ($kelasActivity) {
                    $query->whereHas('paket', function ($query) use ($kelasActivity) {
                        $query->whereHas('kelasPaket', function ($query) use ($kelasActivity) {
                            $query->where('id_kelas', $kelasActivity->id_kelas);
                        });
                    });
                })->whereHas('bookingKelas', function ($query) use ($kelasActivity) {
                    $query->where('id_kelas_activity', '!=', $kelasActivity->id);
                })->inRandomOrder()->get();

                $sisa = $jumlahAnggotaMax - $bookingKelas;
                foreach ($listUser as $user) {
                    $bookingKelas = BookingKelas::create([
                        'id_user' => $user->id,
                        'id_kelas_activity' => $kelasActivity->id,
                        'is_send_email' => true,
                        'status_kehadiran' => null,
                        'keterangan' => 'Dibooking secara automatis',
                    ]);
                    $sisa++;
                    if ($sisa == $jumlahAnggotaMax) {
                        break;
                    }
                }
            }
        }
        return $listKelasActivity;
    }

    public static function generateUnixKode($harga)
    {
        $pin = mt_rand(100, 999);

        $kode = str_shuffle($pin);

        $jumlahKode = OrderPaket::where('unix_code', $kode)->whereMonth('created_at', now())->count();
        if ($jumlahKode > 2) {
            return static::generateUnixKode($harga);
        }

        return $kode;
    }

    public static function termsConditions()
    {
        return [
            "Keanggotaan" => [
                "Semua data member harus di isi dengan lengkap tanpa terkecuali", "Semua anggota baru harus mendapatkan persetujuan dari management. Management memiliki hak untuk menolak permohonan keanggotaan dengan alasan apapun",
                "Jika disetujui, anggota akan diberikan kartu keanggotaan dan tidak dapat dipindah tangankan.",
                "Jika kartu keanggotaan hilnag, anggota wajib memiliki kartu pengganti dengan biaya sesuai dengan peraturan yang berlaku.",
                "Setiap anggota dilarang masuk ke club tanpa kartu keanggotaan yang aktif.",
                "Jika tidak membawa kartu keanggotaan / belum mendapat, dapat menggunakan KTP / ID Card.",
                "Jika salah satu klub kami berhenti beroperasi, anggota bersedia dialkihkan ke klub kami yang terdekat.",
                "Member dilarang untuk menggunakan fasilitas LIMITLESS FITNESS sebelum startdate dimulai. Durasi membership akan segera diaktifkan apabila member sudah memasuki gate."
            ],
            "Biaya Keanggotaan" => [
                "Semua transaksi pembayaran wajib melalui channel pembayaran resmi LIMITLESS FITNESS Gym.",
                "LIMITLESS FITNESS Gym tidak bertanggung jawab atas transaksi yang dilakukan selain melalui channel pembayaran resmi LIMITLESS FITNESS.",
                "Semua anggota wajib membayar biaya keanggotaan yang sudah ditentukan.",
                "Pembelian Membership / Personal Trainer Packages yang sudah berhasil dibeli, dianggap TELAH MENYETUJUI seluruh perjanjian dan TIDAK DAPAT DIREFUND apapun alasannya.",
                "Bonus hanya dapat di isi sesuai dengan persyaratan dan ketentuan yang berlaku."
            ],
        ];
    }
}
