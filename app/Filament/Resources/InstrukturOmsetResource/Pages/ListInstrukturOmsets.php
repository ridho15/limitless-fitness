<?php

namespace App\Filament\Resources\InstrukturOmsetResource\Pages;

use App\Filament\Resources\InstrukturOmsetResource;
use App\Filament\Widgets\PendapatanInstrukturOverview;
use App\Filament\Widgets\PendapatanOverView;
use Filament\Resources\Pages\ListRecords;

class ListInstrukturOmsets extends ListRecords
{
    protected static string $resource = InstrukturOmsetResource::class;

    protected function getActions(): array
    {
        return [];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            // PendapatanInstrukturOverview::class,
        ];
    }
}
