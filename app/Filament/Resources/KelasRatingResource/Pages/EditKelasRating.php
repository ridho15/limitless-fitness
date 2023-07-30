<?php

namespace App\Filament\Resources\KelasRatingResource\Pages;

use App\Filament\Resources\KelasRatingResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKelasRating extends EditRecord
{
    protected static string $resource = KelasRatingResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
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
