<?php

namespace App\Filament\Resources\JurnalResource\Pages;

use App\Filament\Resources\JurnalResource;
use App\Models\Coa;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateJurnal extends CreateRecord
{
    protected static string $resource = JurnalResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Berhasil menyimpan data jurnal baru';
    }
}
