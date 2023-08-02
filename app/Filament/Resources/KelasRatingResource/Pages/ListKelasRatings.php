<?php

namespace App\Filament\Resources\KelasRatingResource\Pages;

use App\Filament\Resources\KelasRatingResource;
use Filament\Pages\Actions;
use Filament\Pages\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\Position;

class ListKelasRatings extends ListRecords
{
    protected static string $resource = KelasRatingResource::class;

    protected function getActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tambah Rating Kelas')
                ->icon('heroicon-o-plus-circle')
        ];
    }

    protected function getTableActionsPosition(): ?string
    {
        return Position::BeforeCells;
    }
}
