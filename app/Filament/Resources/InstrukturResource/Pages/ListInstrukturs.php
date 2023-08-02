<?php

namespace App\Filament\Resources\InstrukturResource\Pages;

use App\Filament\Resources\InstrukturResource;
use Filament\Pages\Actions;
use Filament\Pages\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\Position;

class ListInstrukturs extends ListRecords
{
    protected static string $resource = InstrukturResource::class;

    protected function getActions(): array
    {
        return [
            CreateAction::make()
                ->label('Buat Instruktur')
                ->icon('heroicon-o-plus-circle')
        ];
    }

    protected function getTableActionsPosition(): ?string
    {
        return Position::BeforeCells;
    }
}
