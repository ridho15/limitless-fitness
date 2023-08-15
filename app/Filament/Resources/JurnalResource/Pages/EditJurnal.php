<?php

namespace App\Filament\Resources\JurnalResource\Pages;

use App\Filament\Resources\JurnalResource;
use App\Models\Coa;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditJurnal extends EditRecord
{
    protected static string $resource = JurnalResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->icon('heroicon-o-trash')
                ->after(function () {
                    activity()
                        ->causedBy(Auth::user())
                        ->log('menghapus data jurnal');
                }),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotificationTitle(): ?string
    {
        activity()
            ->causedBy(Auth::user())
            ->log('Melakukan update data jurnal');
        return 'Data jurnal berhasil di update';
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if ($this->record->tipe != $data['tipe']) {
            $jumlah_uang = $data['jumlah_uang'] * 2;
            if ($data['tipe'] == 1) {
                $this->record->coa->update([
                    'saldo' => $this->record->coa->saldo + $jumlah_uang
                ]);
            } else if ($data['tipe'] == 0) {
                $this->record->coa->update([
                    'saldo' => $this->record->coa->saldo - $jumlah_uang
                ]);
            }
        }

        $data['id_user'] = Auth::id();
        return $data;
    }
}
