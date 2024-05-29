<?php

namespace App\Filament\Resources\InstrukturOmsetResource\Pages;

use App\Filament\Resources\InstrukturOmsetResource;
use App\Models\KelasActivity;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Auth;

class ViewInstrukturOmset extends ViewRecord
{
    protected static string $resource = InstrukturOmsetResource::class;

    protected static ?string $title = 'View Instruktur Omset';

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $kelasActivity = KelasActivity::where(function ($query) {
            if (Auth::user()->hasRole(['Instruktur'])) {
                $query->where('id_user', Auth::id());
            }
        })->withCount('bookingKelas')
            ->having('booking_kelas_count', '>=', 2)
            ->whereDate('tanggal', '<=', now())
            ->where('id_kelas', $data['id'])
            ->get();
            
        $omset = 0;
        foreach($kelasActivity as $item){
            $omset += $item->kelas->fee;
        }   

        $data['omset'] = 'Rp.' . number_format($omset,0,',','.');
        return $data;
    }
}
