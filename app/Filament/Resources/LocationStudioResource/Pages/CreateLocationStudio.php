<?php

namespace App\Filament\Resources\LocationStudioResource\Pages;

use App\Filament\Resources\LocationStudioResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateLocationStudio extends CreateRecord
{
    protected static string $resource = LocationStudioResource::class;

    protected function getRedirectUrl(): string
    {
        activity()
            ->causedBy(Auth::user())
            ->log('Membuat data studio baru');
        return $this->getResource()::getUrl('index');
    }
}
