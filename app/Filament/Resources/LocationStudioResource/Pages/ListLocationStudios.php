<?php

namespace App\Filament\Resources\LocationStudioResource\Pages;

use App\Filament\Resources\LocationStudioResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLocationStudios extends ListRecords
{
    protected static string $resource = LocationStudioResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
