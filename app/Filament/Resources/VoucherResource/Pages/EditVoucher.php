<?php

namespace App\Filament\Resources\VoucherResource\Pages;

use App\Filament\Resources\VoucherResource;
use Filament\Pages\Actions;
use Filament\Pages\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditVoucher extends EditRecord
{
    protected static string $resource = VoucherResource::class;

    protected function getActions(): array
    {
        return [
            DeleteAction::make()
                ->icon('heroicon-o-trash')
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['id_paket'] = json_decode($data['id_paket']);
        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['id_paket'] = json_encode($data['id_paket']);
        return $data;
    }
}
