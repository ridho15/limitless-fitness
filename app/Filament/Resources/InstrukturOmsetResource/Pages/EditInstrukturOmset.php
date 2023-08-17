<?php

namespace App\Filament\Resources\InstrukturOmsetResource\Pages;

use App\Filament\Resources\InstrukturOmsetResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInstrukturOmset extends EditRecord
{
    protected static string $resource = InstrukturOmsetResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
