<?php

namespace App\Filament\Resources\KelasRatingResource\Pages;

use App\Filament\Resources\KelasRatingResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateKelasRating extends CreateRecord
{
    protected static string $resource = KelasRatingResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Berhasil membuat rating kelas';
    }
}
