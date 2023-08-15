<?php

namespace App\Filament\Resources\InstrukturJadwalResource\Pages;

use App\Filament\Resources\InstrukturJadwalResource;
use App\Models\KelasActivity;
use Carbon\CarbonPeriod;
use Filament\Notifications\Notification;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateInstrukturJadwal extends CreateRecord
{
    protected static string $resource = InstrukturJadwalResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        activity()
            ->causedBy(Auth::user())
            ->log('Membuat jadwal instruktur');
        return 'Berhasil membuat jadwal baru';
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $start = $data['jam_mulai'];
        $end = $data['jam_selesai'];
        $carbonPeriod = CarbonPeriod::since($start)->hours(1)->until($end)->toArray();
        $jam = [];
        $data['id_user'] = Auth::id();
        $data['status'] = 1;
        foreach ($carbonPeriod as $index => $item) {
            // Check Kelas Activity yang sama
            if ($index + 1 == count($carbonPeriod)) {
                $data['jam_mulai'] = $item;
                return $data;
            } else {
                KelasActivity::create([
                    'tanggal' => $data['tanggal'],
                    'jam_mulai' => $item,
                    'nama_latihan' => $data['nama_latihan'],
                    'level' => $data['level'],
                    'id_user' => $data['id_user'],
                    'status' => $data['status']
                ]);
            }
        }

        return $data;
    }
}
