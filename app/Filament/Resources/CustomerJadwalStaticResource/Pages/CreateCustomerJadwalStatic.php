<?php

namespace App\Filament\Resources\CustomerJadwalStaticResource\Pages;

use App\Filament\Resources\CustomerJadwalStaticResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCustomerJadwalStatic extends CreateRecord
{
    protected static string $resource = CustomerJadwalStaticResource::class;
}
