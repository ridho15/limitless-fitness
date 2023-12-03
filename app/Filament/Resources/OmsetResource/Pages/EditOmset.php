<?php

namespace App\Filament\Resources\OmsetResource\Pages;

use App\Filament\Resources\OmsetResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOmset extends EditRecord
{
    protected static string $resource = OmsetResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
