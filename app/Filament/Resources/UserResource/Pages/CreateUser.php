<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Http\Controllers\HelperController;
use Faker\Factory;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        activity()
            ->causedBy(Auth::user())
            ->log('Membuat user baru');
        return 'Berhasil membuat user baru';
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['barcode'] = HelperController::generateBarcode();
        return $data;
    }
}
