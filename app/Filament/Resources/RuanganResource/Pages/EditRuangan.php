<?php

namespace App\Filament\Resources\RuanganResource\Pages;

use App\Filament\Resources\RuanganResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRuangan extends EditRecord
{
    protected static string $resource = RuanganResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
