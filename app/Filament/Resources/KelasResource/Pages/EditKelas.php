<?php

namespace App\Filament\Resources\KelasResource\Pages;

use App\Filament\Resources\KelasResource;
use Filament\Pages\Actions;
use Filament\Pages\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditKelas extends EditRecord
{
    protected static string $resource = KelasResource::class;

    protected function getActions(): array
    {
        return [
            DeleteAction::make()
                ->label('Hapus Kelas')
                ->icon('heroicon-o-trash')
                ->after(function () {
                    activity()
                        ->causedBy(Auth::user())
                        ->log('Menghapus data kelas');
                })
        ];
    }

    protected function getSavedNotificationTitle(): ?string
    {
        activity()
            ->causedBy(Auth::user())
            ->log('Mengupdate data kelas');
        return 'Berhasil update data kelas';
    }
}
