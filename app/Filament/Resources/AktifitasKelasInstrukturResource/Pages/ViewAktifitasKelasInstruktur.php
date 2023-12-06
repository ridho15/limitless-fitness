<?php

namespace App\Filament\Resources\AktifitasKelasInstrukturResource\Pages;

use App\Filament\Resources\AktifitasKelasInstrukturResource;
use App\Models\Location;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewAktifitasKelasInstruktur extends ViewRecord
{
    protected static string $resource = AktifitasKelasInstrukturResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $id_location_studio = $data['id_location_studio'];
        $data['id_location'] = Location::whereHas('locationStudio', function ($query) use ($id_location_studio) {
            $query->where('id', $id_location_studio);
        })->first()->id;
        return $data;
    }

    protected function getActions(): array
    {
        return [];
    }
}
