<?php

namespace App\Filament\Resources\OrderPaketResource\Pages;

use App\Filament\Resources\OrderPaketResource;
use App\Models\Jurnal;
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
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate()
    {
        if ($this->record->status == 1) {
            $jurnal = Jurnal::updateOrCreate([
                'id_order_paket' => $this->record->id,
                'tipe' => 1
            ], [
                'nama' => "Peserta an, " . $this->record->user->name . ' ' . $this->record->paket->nama,
                'jumlah_uang' => $this->record->harga,
                'tipe' => 1,
                'struk' => $this->record->bukti_pembayaran,
                'tanggal' => now(),
                'id_user' => Auth::id(),
                'id_order_paket' => $this->record->id
            ]);
            Jurnal::updateOrCreate([
                'id_order_paket' => $this->record->id,
                'id_parent' => $jurnal->id,
                'tipe' => 0,
            ], [
                'nama' => 'Biaya Instruktur 1px group @' . $this->record->paket->kelas->honor,
                'jumlah_uang' => $this->record->paket->kelas->honor ?? 0,
                'tipe' => '0',
                'struk' => null,
                'tanggal' => now(),
                'id_user' => Auth::id(),
                'id_order_paket' => $this->record->id
            ]);
            if ($this->record->is_active != 1) {
                $tanggalExpired = Carbon::now()->addMonth($this->record->paket->valid_month);
                $this->record->update([
                    'status' => 1,
                    'expired_date' => $tanggalExpired,
                    'is_active' => 1
                ]);
            }
        }
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        activity()
            ->causedBy(Auth::user())
            ->log('Membuat data order paket baru');
        return 'Berhasil membuat order paket baru';
    }
}
