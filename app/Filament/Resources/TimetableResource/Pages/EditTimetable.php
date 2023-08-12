<?php

namespace App\Filament\Resources\TimetableResource\Pages;

use App\Filament\Resources\TimetableResource;
use App\Filament\Resources\TimetableResource\Widgets\CalendarWidget;
use App\Models\KelasActivity;
use Filament\Notifications\Notification;
use Filament\Pages\Actions;
use Filament\Pages\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Contracts\Support\Htmlable;

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
        ];
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
