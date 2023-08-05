<?php

namespace App\Filament\Resources\LocationStudioResource\Pages;

use App\Filament\Resources\LocationStudioResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLocationStudio extends EditRecord
{
    protected static string $resource = LocationStudioResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
