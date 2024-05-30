<?php

namespace App\Filament\Resources\CustomerResource\Pages;

use App\Filament\Resources\CustomerResource;
use App\Models\User;
use Faker\Factory;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateCustomer extends CreateRecord
{
    protected static string $resource = CustomerResource::class;

    protected function getCreatedNotificationTitle(): ?string
    {
        activity()
            ->causedBy(Auth::user())
            ->log('Membuat customer baru');
        return "Berhasil membuat customer baru";
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = User::where('email', $data['email'])
            ->orWhere('nik', $data['nik'])
            ->orWhere('no_hp', $data['no_hp'])
            ->first();

        if ($user) {
            if ($user->email == $data['email']) {
                Notification::make()
                    ->danger()
                    ->title('Informasi')
                    ->body("Email sudah digunakan")
                    ->icon('error')
                    ->send();
            } elseif ($user->no_hp == $data['no_hp']) {
                Notification::make('no_hp')
                    ->danger()
                    ->title('Informasi')
                    ->body('Nomor Hp sudah digunakan')
                    ->send();
            } elseif ($user->nik == $data['nik']) {
                Notification::make('nik')
                    ->danger()
                    ->title('Informasi')
                    ->body('NIK sudah digunakan')
                    ->send();
            }

            return $this->halt();
        }
        $faker = Factory::create();
        $data['id'] = $faker->ean8();
        return $data;
    }
}
