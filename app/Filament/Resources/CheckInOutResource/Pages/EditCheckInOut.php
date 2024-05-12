<?php

namespace App\Filament\Resources\CheckInOutResource\Pages;

use App\Filament\Resources\CheckInOutResource;
use App\Models\OrderPaket;
use Filament\Pages\Actions;
use Filament\Pages\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCheckInOut extends EditRecord
{
    protected static string $resource = CheckInOutResource::class;

    protected function getActions(): array
    {
        return [
            DeleteAction::make()
            ->label('Hapus Data')
            ->icon('heroicon-o-trash'),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $listOrderPaket = OrderPaket::where('id_user', $data['id_user'])->where('status', 1)
            ->where('is_active', 1)
            ->whereDate('expired_date', '>=', now())
            ->get();
        foreach ($listOrderPaket as $item) {
            $data['list_order_paket'][$item->id] = $item->paket->nama;
        }
        return $data;
    }
}
