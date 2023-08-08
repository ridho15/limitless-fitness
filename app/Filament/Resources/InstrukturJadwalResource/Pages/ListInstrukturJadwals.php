<?php

namespace App\Filament\Resources\InstrukturJadwalResource\Pages;

use App\Filament\Resources\InstrukturJadwalResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInstrukturJadwals extends ListRecords
{
    protected static string $resource = InstrukturJadwalResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Atur Jadwal Baru')
                ->icon('heroicon-o-plus-circle'),
        ];
    }
}
