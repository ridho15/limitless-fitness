<?php

namespace App\Filament\Resources\KelasRatingResource\Pages;

use App\Filament\Resources\KelasRatingResource;
use Filament\Pages\Actions;
use Filament\Pages\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditKelasRating extends EditRecord
{
    protected static string $resource = KelasRatingResource::class;

    protected function getActions(): array
    {
        return [
            DeleteAction::make()
                ->label('Hapus Kelas Rating')
                ->icon('heroicon-o-trash')
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Berhasil membuat rating kelas';
    }
}
