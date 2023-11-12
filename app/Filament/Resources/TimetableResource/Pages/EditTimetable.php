<?php

namespace App\Filament\Resources\TimetableResource\Pages;

use App\Filament\Resources\TimetableResource;
use App\Filament\Resources\TimetableResource\Widgets\CalendarWidget;
use App\Models\KelasActivity;
use App\Models\LocationStudio;
use Filament\Notifications\Notification;
use Filament\Pages\Actions;
use Filament\Pages\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Auth;

class EditTimetable extends EditRecord
{
    public $isSuccess = false;

    protected static string $resource = TimetableResource::class;

    protected function getActions(): array
    {
        return [
            DeleteAction::make()
                ->label('Hapus Timetable')
                ->icon('heroicon-o-trash')
                ->after(function () {
                    activity()
                        ->causedBy(Auth::user())
                        ->log('Menghapus data timetable');
                })
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $locationStudio = LocationStudio::find($data['id_location_studio']);
        if($locationStudio){
            $data['id_location'] = $locationStudio->id_location;
        }
        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $kelasActivity = KelasActivity::where('id', '!=', $this->getRecord()->id)
            ->where('jam_mulai', $data['jam_mulai'])
            ->where('tanggal', $data['tanggal'])
            ->where('id_location_studio', $data['id_location_studio'])
            ->first();
        if ($kelasActivity) {
            $this->isSuccess = false;
            $this->halt();
            // return $this->getRecord()->toArray();
        }
        $this->isSuccess = true;
        return $data;
    }

    protected function getTitle(): string|Htmlable
    {
        return 'Edit Timetable';
    }

    protected function getRedirectUrl(): ?string
    {
        activity()
            ->causedBy(Auth::user())
            ->log('Melakukan update data timetable');
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotification(): ?Notification
    {
        if ($this->isSuccess == true) {
            return Notification::make()
                ->success()
                ->title("Berhasil mengupdate data jadwal kelas");
        } else {
            return Notification::make()
                ->danger()
                ->title("Jadwal sudah ada di dalam ruangan studio");
        }
    }

    protected function getHeaderWidgets(): array
    {
        return [
            // CalendarWidget::class
        ];
    }
}
