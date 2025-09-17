<?php

namespace App\Filament\Resources\OrderPaketResource\Pages;

use App\Filament\Resources\OrderPaketResource;
use App\Models\Jurnal;
use Carbon\Carbon;
use Filament\Pages\Actions;
use Filament\Pages\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditOrderPaket extends EditRecord
{
    protected static string $resource = OrderPaketResource::class;

    protected function getActions(): array
    {
        return [
            DeleteAction::make()
                ->label('Hapus Order Paket')
                ->icon('heroicon-o-trash')
                ->after(function () {
                    activity()
                        ->causedBy(Auth::user())
                        ->log('Menghapus data order paket');
                })
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if ($data['status'] == 1) {
            $data['is_active'] = 1;
            if ($this->record->is_active != $data['is_active']) {
                $data['expired_date'] = Carbon::now()->addDays($this->record->paket->valid_day);
            }
        } else {
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
        activity()
            ->causedBy(Auth::user())
            ->log('melakukan data order paket');
        return 'Berhasil mengupdate data order paket';
    }

    protected function afterSave()
    {
        if ($this->getRecord()->status == 1) {
            $jurnal = Jurnal::updateOrCreate([
                'id_order_paket' => $this->getRecord()->id,
                'tipe' => 1
            ], [
                'nama' => "Peserta an, " . $this->getRecord()->user->name . ' ' . $this->getRecord()->paket->nama,
                'jumlah_uang' => $this->getRecord()->harga,
                'tipe' => 1,
                'struk' => $this->getRecord()->bukti_pembayaran,
                'tanggal' => now(),
                'id_user' => Auth::id(),
                'id_order_paket' => $this->getRecord()->id
            ]);
            Jurnal::updateOrCreate([
                'id_order_paket' => $this->getRecord()->id,
                'id_parent' => $jurnal->id,
                'tipe' => 0,
            ], [
                'nama' => 'Biaya Instruktur 1px group @' . 0,
                'jumlah_uang' => $this->getRecord()->paket->kelas->honor ?? 0,
                'tipe' => '0',
                'struk' => null,
                'tanggal' => now(),
                'id_user' => Auth::id(),
                'id_order_paket' => $this->getRecord()->id
            ]);
            if ($this->getRecord()->is_active != 1) {
                $tanggalExpired = Carbon::now()->addMonth($this->getRecord()->paket->valid_month);
                $this->getRecord()->update([
                    'status' => 1,
                    'expired_date' => $tanggalExpired,
                    'is_active' => 1
                ]);
            }
        }
    }
}
