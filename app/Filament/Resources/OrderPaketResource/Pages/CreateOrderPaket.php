<?php

namespace App\Filament\Resources\OrderPaketResource\Pages;

use App\Filament\Resources\OrderPaketResource;
use App\Models\Paket;
use Carbon\Carbon;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateOrderPaket extends CreateRecord
{
    protected static string $resource = OrderPaketResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $paket = Paket::find($data['id_paket']);
        if ($data['status'] == 1) {
            $data['is_active'] = 1;
            $data['expired_date'] = Carbon::now()->addDays($paket->valid_day);
        } else {
            $data['is_active'] = 0;
        }

        $data['sisa'] = $data['jumlah_pertemuan'];
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        activity()
            ->causedBy(Auth::user())
            ->log('Membuat data order paket baru');
        return 'Berhasil membuat order paket baru';
    }
}
