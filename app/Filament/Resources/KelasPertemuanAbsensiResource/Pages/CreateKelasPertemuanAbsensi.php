<?php

namespace App\Filament\Resources\KelasPertemuanAbsensiResource\Pages;

use App\Filament\Resources\KelasPertemuanAbsensiResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateKelasPertemuanAbsensi extends CreateRecord
{
    protected static string $resource = KelasPertemuanAbsensiResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Berhasil membuat catatan absensi';
    }
}
