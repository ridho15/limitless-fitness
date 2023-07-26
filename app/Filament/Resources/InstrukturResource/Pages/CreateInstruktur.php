<?php

namespace App\Filament\Resources\InstrukturResource\Pages;

use App\Filament\Resources\InstrukturResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateInstruktur extends CreateRecord
{
    protected static string $resource = InstrukturResource::class;

    protected function getCreatedNotificationTitle(): ?string
    {
        return "Berhasil membuat instruktur baru";
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
