<?php

namespace App\Filament\Resources\InstrukturResource\Pages;

use App\Filament\Resources\InstrukturResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditInstruktur extends EditRecord
{
    protected static string $resource = InstrukturResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->after(function () {
                    activity()
                        ->causedBy(Auth::user())
                        ->log('Menghapus data instruktur');
                }),
        ];
    }

    protected function getSavedNotificationTitle(): ?string
    {
        activity()
            ->causedBy(Auth::user())
            ->log('Mengupdate data instruktur');
        return "Berhasil mengupdate instruktur baru";
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
