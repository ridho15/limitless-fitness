<?php

namespace App\Filament\Resources\CustomerResource\Pages;

use App\Filament\Resources\CustomerResource;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditCustomer extends EditRecord
{
    protected static string $resource = CustomerResource::class;

    protected static ?string $title = 'Detail Customer';

    protected static ?string $modelLabel = 'Detail Customer';

    protected static ?string $pluralModelLabel = 'Detail Customer';

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->label('Hapus Customer')
                ->icon('heroicon-o-trash')
                ->after(function () {
                    activity()
                        ->causedBy(Auth::user())
                        ->log('Menghapus data customer');
                }),
        ];
    }

    protected function getSavedNotificationTitle(): ?string
    {
        activity()
            ->causedBy(Auth::user())
            ->log('Mengupdate data customer');
        return "Berhasil mengupdate customer baru";
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $user = User::where('nik', $data['nik'])->where('email', '!=', $data['email'])->first();
        if ($user) {
            Notification::make()
                ->title("Error")
                ->icon('error')
                ->danger()
                ->body("NIK sudah digunakan")
                ->send();

            $this->halt();
        }
        return $data;
    }
}
