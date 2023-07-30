<?php

namespace App\Filament\Resources\BookingOrderResource\Pages;

use App\Filament\Resources\BookingOrderResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBookingOrder extends CreateRecord
{
    protected static string $resource = BookingOrderResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Berhasil membuat booking order baru';
    }
}
