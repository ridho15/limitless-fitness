<?php

namespace App\Filament\Resources\InstrukturResource\Pages;

use App\Filament\Resources\InstrukturResource;
use App\Filament\Widgets\InstrukturWidgetOverview;
use Filament\Pages\Actions;
use Filament\Pages\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewInstruktur extends ViewRecord
{
    protected static string $resource = InstrukturResource::class;

    protected static ?string $title = 'View Instruktur';

    protected function getActions(): array
    {
        return [
            EditAction::make()
                ->icon('heroicon-o-pencil-alt')
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            InstrukturWidgetOverview::class
        ];
    }
}
