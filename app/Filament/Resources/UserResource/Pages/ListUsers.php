<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Pages\Actions;
use Filament\Pages\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\Position;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getActions(): array
    {
        return [
            // CreateAction::make()
            //     ->label('Tambah User')
            //     ->icon('heroicon-o-plus-circle')
        ];
    }
    protected function getTableActionsPosition(): ?string
    {
        return Position::BeforeCells;
    }
}
