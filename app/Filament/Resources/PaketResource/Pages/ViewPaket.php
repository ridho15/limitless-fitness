<?php

namespace App\Filament\Resources\PaketResource\Pages;

use App\Filament\Resources\PaketResource;
use Filament\Pages\Actions;
use Filament\Pages\Actions\DeleteAction;
use Filament\Pages\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPaket extends ViewRecord
{
    protected static string $resource = PaketResource::class;

    protected function getActions(): array
    {
        return [
            EditAction::make()
                ->icon('heroicon-o-pencil-alt'),
            DeleteAction::make()
                ->icon('heroicon-o-trash')
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $location = [];
        foreach($this->record->paketLocation as $item){
            array_push($location, $item->id_location);
        }
        $data['location'] = $location;
        return $data;
    }
}
