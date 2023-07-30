<?php

namespace App\Filament\Resources\BookingOrderResource\Pages;

use App\Filament\Resources\BookingOrderResource;
use Filament\Pages\Actions;
use Filament\Pages\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditBookingOrder extends EditRecord
{
    protected static string $resource = BookingOrderResource::class;

    protected function getActions(): array
    {
        return [
            DeleteAction::make()
                ->icon('heroicon-o-trash')
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Berhasil membuat booking order baru';
    }
}
