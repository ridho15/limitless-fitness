<?php

namespace App\Console\Commands;

use App\Filament\Resources\BookingKelasResource;
use App\Filament\Resources\TimetableResource;
use App\Models\BookingKelas;
use App\Models\KelasActivity;
use App\Models\User;
use Carbon\Carbon;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CronJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:job';

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
        Log::info("Cronjob dijalankan pada " . now());
        $this->autoBookingRepetitifCustomer();
        $this->checkSlotKelasActivity();
        $this->sendNotifToEmailAboutJadwalKelas();
    }

    public function checkSlotKelasActivity()
    {
        $listKelasActivity = KelasActivity::whereDate('tanggal', '>=', now())
            ->whereDate('tanggal', '<=', Carbon::now()->addHours(48)->isoFormat('YYYY-MM-DD'))
            ->where('status', 1)
            ->get();

        $listUser = User::whereHas('roles', function ($query) {
            $query->where(function ($query) {
                $query->where('name', 'Admin')
                    ->orWhere('Super Admin');
            });
        })->get();

        foreach ($listKelasActivity as $item) {
            $jumlahPeserta = 0;
            foreach ($item->bookingKelas as $bookingKelas) {
                if ($bookingKelas->status_kehadiran == '1' || $bookingKelas->status_kehadiran == '0') {
                    $jumlahPeserta++;
                }
            }

            if ($item->kelas->min_anggota <= $jumlahPeserta) {
                foreach ($listUser as $user) {
                    Notification::make()
                        ->actions([
                            Action::make('view')
                                ->icon('heroicon-o-eye')
                                ->button()
                                ->url(TimetableResource::getUrl('view', ['record' => $item]))
                        ])
                        ->title($item->kelas->nama_kelas . ' dengan jadwal ' . $item->tanggal . ' ' . $item->jam_mulai . ' masih belum mencapai minimal jumlah peserta')
                        ->sendToDatabase($user);
                }
            }
        }

        Log::info("checkSlotKelasActivity");
    }

    public function autoBookingRepetitifCustomer()
    {
        $user = User::whereHas('customerJadwalStatic')
            ->with(['orderPaket' => function ($query) {
                $query->whereDate('expired_date', '>=', now())
                    ->where('status', 1)
                    ->where('is_active', 1)
                    ->where('sisa', '>', 0);
            }])->whereHas('orderPaket')
            ->get();

        $listAdmin = User::whereHas('roles', function ($query) {
            $query->where(function ($query) {
                $query->where('name', 'Admin')
                    ->orWhere('name', 'Super Admin');
            });
        })->get();

        foreach ($user as $item) {
            foreach ($item->customerJadwalStatic as $jadwal) {
                $tanggalNextWeek = Carbon::now()->addWeeks(1)
                    ->startOfWeek($jadwal->hari + 1)->isoFormat('YYYY-MM-DD');
                $kelasActivity = KelasActivity::whereDate('tanggal', $tanggalNextWeek)
                    ->where('jam_mulai', $jadwal->jam)->where('status', 1)->first();
                $jumlahPeserta = 0;

                if ($kelasActivity) {
                    foreach ($kelasActivity->bookingKelas as $bookingKelas) {
                        if ($bookingKelas->status_kehadiran == '1' || $bookingKelas->status_kehadiran == '0') {
                            $jumlahPeserta++;
                        }
                    }

                    if ($kelasActivity->kelas->max_anggota > $jumlahPeserta) {
                        $bookingKelas = BookingKelas::updateOrCreate([
                            'id_user' => $item->id,
                            'id_kelas_activity' => $kelasActivity->id,
                        ], [
                            'id_user' => $item->id,
                            'id_kelas_activity' => $kelasActivity->id,
                            'is_send_email' => 1,
                            'status_kehadiran' => 1
                        ]);

                        foreach ($listAdmin as $admin) {
                            Notification::make()
                                ->title("Booking kelas")
                                ->body($kelasActivity->kelas->nama_kelas . " baru saja di booking oleh " . $item->name . " secara automatis melalui sistem")
                                ->actions([
                                    Action::make('view')
                                        ->button()
                                        ->icon('heroicon-o-eye')
                                        ->url(BookingKelasResource::getUrl('view', ['record' => $bookingKelas]))
                                ])->sendToDatabase($admin);
                        }
                    }
                }
            }
        }

        Log::info("autoBookingRepetitifCustomer berhasil di jalankan");
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
