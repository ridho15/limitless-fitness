<?php

namespace App\Filament\Resources\BookingKelasResource\Pages;

use App\Filament\Resources\BookingKelasResource;
use App\Filament\Resources\TimetableResource;
use Filament\Pages\Actions;
use Filament\Pages\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditBookingKelas extends EditRecord
{
    protected static string $resource = BookingKelasResource::class;

    protected function getActions(): array
    {
        return [
            DeleteAction::make()
                ->icon('heroicon-o-trash')
                ->label('Hapus Data')
                ->after(function () {
                    activity()
                        ->causedBy(Auth::user())
                        ->log('Menghapus data booking kelas');
                })
        ];
    }

    protected function getRedirectUrl(): string
    {
        return TimetableResource::getUrl('index');
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
