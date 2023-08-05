<?php

namespace App\Filament\Resources\RuanganResource\Pages;

use App\Filament\Resources\RuanganResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRuangans extends ListRecords
{
    protected static string $resource = RuanganResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Ruangan')
                ->icon('heroicon-o-plus-circle'),
        ];
    }
}
