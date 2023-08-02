<?php

namespace App\Filament\Resources\CoaResource\Pages;

use App\Filament\Resources\CoaResource;
use Filament\Pages\Actions;
use Filament\Pages\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\Position;

class ListCoas extends ListRecords
{
    protected static string $resource = CoaResource::class;

    protected function getActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tambah Coa')
                ->icon('heroicon-o-plus-circle')
        ];
    }

    protected function getTableActionsPosition(): ?string
    {
        return Position::BeforeCells;
    }
}
