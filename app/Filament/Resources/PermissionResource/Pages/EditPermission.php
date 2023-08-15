<?php

namespace App\Filament\Resources\PermissionResource\Pages;

use App\Filament\Resources\PermissionResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditPermission extends EditRecord
{
    protected static string $resource = PermissionResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->after(function () {
                    activity()
                        ->causedBy(Auth::user())
                        ->log('Menghapus data permission');
                }),
        ];
    }

    protected function getRedirectUrl(): string
    {
        activity()
            ->causedBy(Auth::user())
            ->log('Update data permission');
        return $this->getResource()::getUrl('index');
    }
}
