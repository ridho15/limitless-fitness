<?php

namespace App\Filament\Resources\CheckInOutResource\Pages;

use App\Filament\Resources\CheckInOutResource;
use Filament\Pages\Actions;
use Filament\Pages\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCheckInOuts extends ListRecords
{
    protected static string $resource = CheckInOutResource::class;

    protected function getActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tambah Data')
                ->icon('heroicon-o-plus-circle'),
        ];
    }
}
