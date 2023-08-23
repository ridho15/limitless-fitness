<?php

namespace App\Filament\Resources\TimetableResource\Pages;

use App\Filament\Resources\TimetableResource;
use Filament\Pages\Actions;
use Filament\Pages\Actions\DeleteAction;
use Filament\Pages\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Auth;

class ViewTimetable extends ViewRecord
{
    protected static string $resource = TimetableResource::class;

    protected function getActions(): array
    {
        return [
            EditAction::make()
                ->label('Edit Timetable')
                ->icon('heroicon-o-pencil-alt'),
            DeleteAction::make()
                ->label('Hapus Timetable')
                ->icon('heroicon-o-trash')
                ->after(function () {
                    activity()
                        ->causedBy(Auth::user())
                        ->log('Menghapus data timetable');
                })
        ];
    }
}
