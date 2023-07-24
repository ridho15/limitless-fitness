<?php

namespace App\Filament\Resources\KelasPertemuanAbsensiResource\Pages;

use App\Filament\Resources\KelasPertemuanAbsensiResource;
use App\Models\KelasPertamuan;
use Filament\Pages\Actions;
use Filament\Pages\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditKelasPertemuanAbsensi extends EditRecord
{
    protected static string $resource = KelasPertemuanAbsensiResource::class;

    protected function getActions(): array
    {
        return [
            DeleteAction::make()
                ->icon('heroicon-o-trash')
                ->label('Hapus Absensi')
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Berhasil mengupdate data absensi';
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $kelasPertemuan = KelasPertamuan::find($data['id_kelas_pertemuan']);
        $data['id_kelas'] = $kelasPertemuan->id_kelas;
        return $data;
    }
}
