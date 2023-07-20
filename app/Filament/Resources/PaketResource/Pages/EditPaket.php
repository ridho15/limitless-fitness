<?php

namespace App\Filament\Resources\PaketResource\Pages;

use App\Filament\Resources\PaketResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPaket extends EditRecord
{
    protected static string $resource = PaketResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
