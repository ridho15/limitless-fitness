<?php

namespace App\Http\Controllers;

use App\Models\BookingKelas;
use App\Models\KelasActivity;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

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
}
