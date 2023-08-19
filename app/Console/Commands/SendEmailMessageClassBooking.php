<?php

namespace App\Console\Commands;

use App\Mail\SendKonfirmasiKehadiranMail;
use App\Models\BookingKelas;
use App\Models\KelasActivity;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendEmailMessageClassBooking extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send-email:class-booking';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->sendNotifToEmailAboutJadwalKelas();
    }

    public function sendNotifToEmailAboutJadwalKelas()
    {
        $tanggalBesok = Carbon::now()->addDays(1)->isoFormat('YYYY-MM-DD');
        $listKelasActivity = KelasActivity::with(['bookingKelas' => function ($query) {
            $query->where(function ($query) {
                $query->where('status_kehadiran', '0')
                    ->where('status_kehadiran', '1');
            });
        }])
            ->whereDate('tanggal', $tanggalBesok)
            ->where('status', 1)
            ->get();

        foreach ($listKelasActivity as $item) {
            $jumlahPeserta = 0;
            foreach ($item->bookingKelas as $bookingKelas) {
                if ($bookingKelas->status_kehadiran == 1 || $bookingKelas->status_kehadiran == 0) {
                    $jumlahPeserta++;
                }
            }

            $tidakDitemukan = 0;
            while (($item->kelas->max_anggota > $jumlahPeserta) || $tidakDitemukan >= 3) {
                $listUser = User::whereHas('orderPaket', function ($query) use ($item) {
                    $query->whereHas('paket', function ($query) use ($item) {
                        $query->whereHas('kelas', function ($query) use ($item) {
                            $query->whereHas('kelasActivity', function ($query) use ($item) {
                                $query->where('id', $item->id)
                                    ->doesntHave('bookingKelas');
                            });
                        });
                    });
                })->inRandomOrder()->get();
                if (count($listUser) > 0) {
                    foreach ($listUser as $user) {
                        BookingKelas::create([
                            'id_kelas_activity' => $item->id,
                            'id_user' => $user->id,
                            'is_send_email' => 1,
                            'status_kehadiran' => 1,
                        ]);

                        $jumlahPeserta++;
                    }
                } else {
                    $tidakDitemukan++;
                    break;
                }
            }
        }
        $listBookingKelas = BookingKelas::whereHas('kelasActivity', function ($query) use ($tanggalBesok) {
            $query->whereDate('tanggal', $tanggalBesok);
        })->where(function ($query) {
            $query->where('status_kehadiran', '1')
                ->where('status_kehadiran', '0');
        })->get();

        foreach ($listBookingKelas as $item) {
            Mail::to($item->user->email)->send(new SendKonfirmasiKehadiranMail($item));
        }

        Log::info("sendNotifToEmailAboutJadwalKelas berhasil di jalankan");
    }
}
