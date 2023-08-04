<?php

namespace App\Filament\Resources\TimetableResource\Pages;

use App\Filament\Resources\TimetableResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

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
}
