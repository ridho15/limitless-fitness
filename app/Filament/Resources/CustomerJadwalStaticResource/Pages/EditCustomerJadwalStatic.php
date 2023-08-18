<?php

namespace App\Filament\Resources\CustomerJadwalStaticResource\Pages;

use App\Filament\Resources\CustomerJadwalStaticResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCustomerJadwalStatic extends EditRecord
{
    protected static string $resource = CustomerJadwalStaticResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
