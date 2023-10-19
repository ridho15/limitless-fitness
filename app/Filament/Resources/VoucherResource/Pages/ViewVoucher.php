<?php

namespace App\Filament\Resources\VoucherResource\Pages;

use App\Filament\Resources\VoucherResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewVoucher extends ViewRecord
{
    protected static string $resource = VoucherResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['id_paket'] = json_decode($data['id_paket']);
        return $data;
    }
}
