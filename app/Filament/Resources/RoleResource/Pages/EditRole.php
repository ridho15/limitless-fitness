<?php

namespace App\Filament\Resources\RoleResource\Pages;

use App\Filament\Resources\RoleResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditRole extends EditRecord
{
    protected static string $resource = RoleResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->icon('heroicon-o-trash')
                ->after(function () {
                    activity()->causedBy(Auth::user())
                        ->log("Menghapus data role");
                }),
        ];
    }

    protected function getRedirectUrl(): string
    {
        activity()->causedBy(Auth::user())
            ->log("Mengupdate data role");
        return $this->getResource()::getUrl('index');
    }
}
