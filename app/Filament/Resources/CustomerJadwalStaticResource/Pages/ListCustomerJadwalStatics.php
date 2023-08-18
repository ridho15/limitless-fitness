<?php

namespace App\Filament\Resources\CustomerJadwalStaticResource\Pages;

use App\Filament\Resources\CustomerJadwalStaticResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCustomerJadwalStatics extends ListRecords
{
    protected static string $resource = CustomerJadwalStaticResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Jadwal')
                ->icon('heroicon-o-plus-circle'),
        ];
    }
}
