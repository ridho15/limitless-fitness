<?php

namespace App\Filament\Resources\PaketResource\Pages;

use App\Filament\Resources\PaketResource;
use App\Models\PaketLocation;
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

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return $data;
    }

    protected function afterCreate()
    {
        foreach ($this->data['location'] as $item) {
            PaketLocation::updateOrCreate([
                'id_location' => $item,
                'id_paket' => $this->record->id,
            ], [
                'id_location' => $item,
                'id_paket' => $this->record->id
            ]);
        }
    }
}
