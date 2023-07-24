<?php

namespace App\Filament\Resources\KelasPertemuanResource\Pages;

use App\Filament\Resources\KelasPertemuanResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateKelasPertemuan extends CreateRecord
{
    protected static string $resource = KelasPertemuanResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Berhasil membuat jadwal pertemuan kelas';
    }
}
