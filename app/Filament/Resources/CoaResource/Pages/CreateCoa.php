<?php

namespace App\Filament\Resources\CoaResource\Pages;

use App\Filament\Resources\CoaResource;
use App\Models\Coa;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCoa extends CreateRecord
{
    protected static string $resource = CoaResource::class;

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Berhasil membuat COA Baru';
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $coa = Coa::find($data['id_coa']);
        if ($data['tipe'] == 1) {
            $coa->update([
                'saldo' => $coa->saldo + $data['jumlah_uang']
            ]);
        } else if ($data['tipe'] == 0) {
            $coa->update([
                'saldo' => $coa->saldo - $data['jumlah_uang']
            ]);
        }
        return $data;
    }
}
