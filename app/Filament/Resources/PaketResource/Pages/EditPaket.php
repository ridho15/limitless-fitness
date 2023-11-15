<?php

namespace App\Filament\Resources\PaketResource\Pages;

use App\Filament\Resources\PaketResource;
use App\Models\PaketLocation;
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

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $location = [];
        foreach ($this->record->paketLocation as $item) {
            array_push($location, $item->id_location);
        }
        $data['location'] = $location;
        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        foreach ($data['location'] as $item) {
            PaketLocation::updateOrCreate([
                'id_paket' => $this->record->id,
                'id_location' => $item
            ], [
                'id_paket' => $this->record->id,
                'id_location' => $item
            ]);
        }
        return $data;
    }
}
