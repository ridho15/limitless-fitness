<?php

namespace App\Filament\Resources\TimetableResource\Pages;

use App\Filament\Resources\TimetableResource;
use App\Filament\Resources\TimetableResource\Widgets\CalendarWidget;
use App\Models\KelasActivity;
use Carbon\CarbonPeriod;
use Filament\Notifications\Notification;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class CreateTimetable extends CreateRecord
{
    protected static string $resource = TimetableResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Berhasil mengupdate data jadwal kelas';
    }

    protected function getTitle(): string
    {
        return "Buat Timetable";
    }

    protected function handleRecordCreation(array $data): Model
    {
        if(count($data) > 0){
            return $this->getModel()::create($data);
        }else{
            return $this->getModel()::first();
        }
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $start = $data['jam_mulai'];
        $end = $data['jam_selesai'];
        $carbonPeriod = CarbonPeriod::since($start)->hours(1)->until($end)->toArray();
        $jam = [];
        foreach ($carbonPeriod as $item) {
            array_push($jam, date('Y-m-d', strtotime($data['jam_mulai'])) . date(' H:i', strtotime($item)));
        }
        foreach ($jam as $index => $item) {
            // Check jadwal studio
            $kelasActivity = KelasActivity::where('jam_mulai', $item)->where('id_location_studio', $data['id_location_studio'])
                ->first();
            if ($kelasActivity) {
                Notification::make()
                    ->title('Jadwal sudah ada di dalam ruangan studio')
                    ->danger()
                    ->send();
                $this->halt();
            }
            if ($index + 1 == count($jam)) {
                $data['jam_mulai'] = $item;
                $data['status'] = 1;
                return $data;
            } else {
                KelasActivity::create([
                    'id_kelas' => $data['id_kelas'],
                    'id_location_studio' => $data['id_location_studio'],
                    'jam_mulai' => $item,
                    'tanggal' => $data['tanggal'],
                    'id_user' => Auth::user()->hasRole('Intruktur') ? Auth::id() : $data['id_user'],
                    'status' => 1,
                ]);
            }
        }
    }

    protected function getHeaderWidgets(): array
    {
        return [
            // CalendarWidget::class
        ];
    }
}
