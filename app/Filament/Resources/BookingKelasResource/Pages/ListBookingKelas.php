<?php

namespace App\Filament\Resources\BookingKelasResource\Pages;

use App\Filament\Resources\BookingKelasResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\Position;

class ListBookingKelas extends ListRecords
{
    protected static string $resource = BookingKelasResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Buat Booking Kelas')
            ->icon('heroicon-o-plus-circle'),
        ];
    }

    protected function getTableActionsPosition(): ?string
    {
        return Position::BeforeCells;
    }
}
