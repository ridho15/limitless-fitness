<?php

namespace App\Filament\Resources\PaketResource\Pages;

use App\Filament\Resources\PaketResource;
use Filament\Pages\Actions;
use Filament\Pages\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPakets extends ListRecords
{
    protected static string $resource = PaketResource::class;

    protected function getActions(): array
    {
        return [
            CreateAction::make()
            ->icon('heroicon-o-plus-circle')
            ->label('Tambah Paket')
        ];
    }
}
