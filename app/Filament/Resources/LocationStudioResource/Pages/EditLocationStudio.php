<?php

namespace App\Filament\Resources\LocationStudioResource\Pages;

use App\Filament\Resources\LocationStudioResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditLocationStudio extends EditRecord
{
    protected static string $resource = LocationStudioResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->after(function () {
                    activity()
                        ->causedBy(Auth::user())
                        ->log('Menghapus data studio');
                }),
        ];
    }

    protected function getRedirectUrl(): ?string
    {
        activity()
            ->causedBy(Auth::user())
            ->log('Update data studio');
        return $this->getResource()::getUrl('index');
    }
}
