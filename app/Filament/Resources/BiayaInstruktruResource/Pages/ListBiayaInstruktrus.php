<?php

namespace App\Filament\Resources\BiayaInstruktruResource\Pages;

use App\Filament\Resources\BiayaInstruktruResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBiayaInstruktrus extends ListRecords
{
    protected static string $resource = BiayaInstruktruResource::class;

    protected function getActions(): array
    {
        return [
        ];
    }
}
