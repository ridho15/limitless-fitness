<?php

namespace App\Filament\Resources\KelasPertemuanAbsensiResource\Pages;

use App\Filament\Resources\KelasPertemuanAbsensiResource;
use Filament\Pages\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListKelasPertemuanAbsensis extends ListRecords
{
    protected static string $resource = KelasPertemuanAbsensiResource::class;

    protected function getActions(): array
    {
        return [
            CreateAction::make()
            ->label('Buat Absensi')
            ->icon('heroicon-o-plus-circle')
        ];
    }
}
