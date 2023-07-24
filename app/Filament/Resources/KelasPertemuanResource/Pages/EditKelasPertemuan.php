<?php

namespace App\Filament\Resources\KelasPertemuanResource\Pages;

use App\Filament\Resources\KelasPertemuanResource;
use Filament\Pages\Actions;
use Filament\Pages\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditKelasPertemuan extends EditRecord
{
    protected static string $resource = KelasPertemuanResource::class;

    protected function getActions(): array
    {
        return [
            DeleteAction::make()
                ->label("Hapus Kelas Pertemuan")
                ->icon('heroicon-o-trash')
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Berhasil mengupdate jadwal pertemuan kelas';
    }
}
