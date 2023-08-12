<?php

namespace App\Filament\Resources\TimetableResource\Pages;

use App\Filament\Resources\TimetableResource;
use App\Filament\Resources\TimetableResource\Widgets\CalendarWidget;
use App\Filament\Resources\TimetableResource\Widgets\ManageTimetableWidget;
use Filament\Pages\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\Position;

class ListTimetables extends ListRecords
{
    protected static string $resource = TimetableResource::class;

    protected function getActions(): array
    {
        return [
            // CreateAction::make()
            //     ->label('Tambah Timetable')
            //     ->icon('heroicon-o-plus-circle')
        ];
    }

    protected function getTableActionsPosition(): ?string
    {
        return Position::BeforeCells;
    }

    protected function getHeaderWidgets(): array
    {
        return [
            ManageTimetableWidget::class,
            // CalendarWidget::class,
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            // CalendarWidget::class
        ];
    }

    protected function getHeaderWidgetsColumns(): int|string|array
    {
        return [
            'md' => 1,
            'xl' => 1
        ];
    }
}
