<?php

namespace App\Filament\Resources\TimetableResource\Pages;

use App\Filament\Resources\TimetableResource;
use Filament\Pages\Actions;
use Filament\Pages\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTimetables extends ListRecords
{
    protected static string $resource = TimetableResource::class;

    protected function getActions(): array
    {
        return [
            CreateAction::make()
            ->label('Tambah Timetable')
            ->icon('heroicon-o-plus-circle')
        ];
    }
}
