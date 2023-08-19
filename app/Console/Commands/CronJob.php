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
        $this->autoBookingRepetitifCustomer();
        $this->checkSlotKelasActivity();
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
    }
}
