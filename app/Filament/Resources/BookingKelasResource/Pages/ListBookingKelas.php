<?php

namespace App\Filament\Resources\BookingKelasResource\Pages;

use App\Filament\Resources\BookingKelasResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBookingKelas extends ListRecords
{
    protected static string $resource = BookingKelasResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
