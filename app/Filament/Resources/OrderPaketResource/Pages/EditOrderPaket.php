<?php

namespace App\Filament\Resources\OrderPaketResource\Pages;

use App\Filament\Resources\OrderPaketResource;
use Carbon\Carbon;
use Filament\Pages\Actions;
use Filament\Pages\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditOrderPaket extends EditRecord
{
    protected static string $resource = OrderPaketResource::class;

    protected function getActions(): array
    {
        return [
            DeleteAction::make()
                ->label('Hapus Order Paket')
                ->icon('heroicon-o-trash')
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if($data['status'] == 1){
            $data['is_active'] = 1;
            if($this->record->is_active != $data['is_active']){
                $data['expired_date'] = Carbon::now()->addDays($this->record->paket->valid_day);
            }
        }else{
            $data['is_active'] = 0;
        }
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Berhasil mengupdate data order paket';
    }
}
