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
    protected static string $resource = TimetableResource::class;

    protected function getActions(): array
    {
        return [
            DeleteAction::make()
                ->label('Hapus Timetable')
                ->icon('heroicon-o-trash')
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['tanggal'] = $data['jam_mulai'];
        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['jam_mulai'] = date('Y-m-d', strtotime($data['tanggal'])) . ' ' . date('H:i:s', strtotime($data['jam_mulai']));
        $kelasActivity = KelasActivity::where('jam_mulai', $data['jam_mulai'])
        ->where('id_ruangan', $data['id_ruangan'])
        ->first();
        Notification::make()
            ->title('Jadwal sudah ada di dalam ruangan studio')
            ->danger()
            ->send();
        return [];
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

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Berhasil mengupdate data jadwal kelas';
    }

    protected function getHeaderWidgets(): array
    {
        return [
            // CalendarWidget::class
        ];
    }
}
