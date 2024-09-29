<?php

namespace App\Filament\Resources\OrderPaketResource\Pages;

use App\Filament\Resources\OrderPaketResource;
use Filament\Pages\Actions;
use Filament\Pages\Actions\Action;
use Filament\Pages\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\Position;

class ListOrderPakets extends ListRecords
{
    protected static string $resource = OrderPaketResource::class;

    protected function getActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tambah Data')
                ->hidden()
                ->icon('heroicon-o-plus-circle'),
        ];
    }

    protected function getTableActionsPosition(): ?string
    {
        return Position::BeforeCells;
    }
}
