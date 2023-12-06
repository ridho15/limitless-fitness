<?php

namespace App\Filament\Resources\AktifitasKelasInstrukturResource\Pages;

use App\Filament\Resources\AktifitasKelasInstrukturResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAktifitasKelasInstrukturs extends ListRecords
{
    protected static string $resource = AktifitasKelasInstrukturResource::class;

    protected function getActions(): array
    {
        return [
        ];
    }
}
