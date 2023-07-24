<?php

namespace App\Filament\Resources\CustomerKelasResource\Pages;

use App\Filament\Resources\CustomerKelasResource;
use Filament\Pages\Actions;
use Filament\Pages\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCustomerKelas extends EditRecord
{
    protected static string $resource = CustomerKelasResource::class;

    protected function getActions(): array
    {
        return [
            DeleteAction::make()
                ->label('Hapus')
                ->icon('heroicon-o-trash')
        ];
    }

    protected function getRedirectUrl(): ?string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Berhasil mengupdate data peserta kelas';
    }
}
