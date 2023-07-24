<?php

namespace App\Filament\Resources\KelasPertemuanResource\Pages;

use App\Filament\Resources\KelasPertemuanResource;
use Filament\Pages\Actions;
use Filament\Pages\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListKelasPertemuans extends ListRecords
{
    protected static string $resource = KelasPertemuanResource::class;

    protected function getActions(): array
    {
        return [
            CreateAction::make()
            ->label('Buat Kelas Pertemuan')
            ->icon('heroicon-o-plus-circle')
        ];
    }
}
