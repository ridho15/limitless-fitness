<?php

namespace App\Filament\Resources\PaketResource\Pages;

use App\Filament\Resources\PaketResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreatePaket extends CreateRecord
{
    protected static string $resource = PaketResource::class;

    protected function getCreatedNotificationTitle(): ?string
    {
        activity()
            ->causedBy(Auth::user())
            ->log('Membuat paket baru');
        return 'Berhasil membuat paket baru';
    }
}
