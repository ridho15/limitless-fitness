<?php

namespace App\Filament\Resources\InstrukturJadwalResource\Pages;

use App\Filament\Resources\InstrukturJadwalResource;
use Filament\Pages\Actions;
use Filament\Pages\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditInstrukturJadwal extends EditRecord
{
    protected static string $resource = InstrukturJadwalResource::class;

    protected function getActions(): array
    {
        return [
            DeleteAction::make()
                ->icon('heroicon-o-trash')
                ->after(function () {
                    activity()
                        ->causedBy(Auth::user())
                        ->log('Menghapus data jadwal');
                })
        ];
    }

    protected function getSavedNotificationTitle(): ?string
    {
        activity()
            ->causedBy(Auth::user())
            ->log('Melakukan update data jadwal');
        return 'Berhasil mengupdate data jadwal';
    }

    protected function getRedirectUrl(): ?string
    {
        return $this->getResource()::getUrl('index');
    }
}
