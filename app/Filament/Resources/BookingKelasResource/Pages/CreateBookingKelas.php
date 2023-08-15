<?php

namespace App\Filament\Resources\BookingKelasResource\Pages;

use App\Filament\Resources\BookingKelasResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateBookingKelas extends CreateRecord
{
    protected static string $resource = BookingKelasResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        $message = 'Berhasil membuat booking kelas baru';
        activity()
            ->causedBy(Auth::user())
            ->log($message);
        return $message;
    }
}
