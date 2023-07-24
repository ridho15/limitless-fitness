<?php

namespace App\Filament\Resources\JurnalResource\Pages;

use App\Filament\Resources\JurnalResource;
use App\Models\Coa;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateJurnal extends CreateRecord
{
    protected static string $resource = JurnalResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $coa = Coa::find($data['id_coa']);
        if($this->data['tipe'] == 1){
            $coa->update([
                'saldo' => $coa->saldo + $data['jumlah_uang']
            ]);
        }else{
            $coa->update([
                'saldo' => $coa->saldo - $data['jumlah_uang']
            ]);
        }

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Berhasil menyimpan data jurnal baru';
    }
}
