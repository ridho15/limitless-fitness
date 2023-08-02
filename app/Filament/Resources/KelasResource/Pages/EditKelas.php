<?php

namespace App\Filament\Resources\KelasResource\Pages;

use App\Filament\Resources\KelasResource;
use Filament\Pages\Actions;
use Filament\Pages\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditKelas extends EditRecord
{
    protected static string $resource = KelasResource::class;

    protected function getActions(): array
    {
        return [
            DeleteAction::make()
                ->label('Hapus Kelas')
                ->icon('heroicon-o-trash')
        ];
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Berhasil membuat kelas baru';
    }
}
