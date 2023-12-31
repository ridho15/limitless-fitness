<?php

namespace App\Filament\Resources\InstrukturOmsetResource\Pages;

use App\Filament\Resources\InstrukturOmsetResource;
use App\Filament\Widgets\PendapatanInstrukturOverview;
use App\Filament\Widgets\PendapatanOverView;
use App\Models\Location;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;

class ListInstrukturOmsets extends ListRecords
{
    protected static string $resource = InstrukturOmsetResource::class;

    public $listLocation = [];
    public function mount(): void
    {
        $this->listLocation = Location::get();
    }

    protected function getActions(): array
    {
        return [];
    }

    protected function getHeaderWidgets(): array
    {
        $pendapatanInstruktur = PendapatanInstrukturOverview::class;
        return [
            $pendapatanInstruktur,
        ];
    }
}
