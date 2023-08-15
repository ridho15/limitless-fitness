<?php

namespace App\Filament\Resources\PaketResource\Pages;

use App\Filament\Resources\PaketResource;
use Filament\Pages\Actions;
use Filament\Pages\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditPaket extends EditRecord
{
    protected static string $resource = PaketResource::class;

    protected function getActions(): array
    {
        return [
            DeleteAction::make()
                ->label('Hapus Paket')
                ->icon('heroicon-o-trash')
        ];
    }

    protected function getSavedNotificationTitle(): ?string
    {
        activity()
            ->causedBy(Auth::user())
            ->log('Melakukan update paket');
        return 'Berhasil mengupdate paket';
    }
}
