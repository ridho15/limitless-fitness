<?php

namespace App\Filament\Resources\CustomerKelasResource\Pages;

use App\Filament\Resources\CustomerKelasResource;
use Filament\Pages\Actions;
use Filament\Pages\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCustomerKelas extends ListRecords
{
    protected static string $resource = CustomerKelasResource::class;

    protected function getActions(): array
    {
        return [
            CreateAction::make()
            ->label('Tambah Peserta')
            ->icon('heroicon-o-plus-circle')
        ];
    }
}
