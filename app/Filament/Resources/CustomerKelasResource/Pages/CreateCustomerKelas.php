<?php

namespace App\Filament\Resources\CustomerKelasResource\Pages;

use App\Filament\Resources\CustomerKelasResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCustomerKelas extends CreateRecord
{
    protected static string $resource = CustomerKelasResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Berhasil menyimpan peserta ke dalam kelas';
    }
}
