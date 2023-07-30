<?php

namespace App\Filament\Resources\BookingOrderResource\Pages;

use App\Filament\Resources\BookingOrderResource;
use Filament\Pages\Actions;
use Filament\Pages\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBookingOrders extends ListRecords
{
    protected static string $resource = BookingOrderResource::class;

    protected function getActions(): array
    {
        return [
            CreateAction::make()
                ->icon('heroicon-o-plus-circle')
                ->label('Tambah Booking Order')
        ];
    }
}
