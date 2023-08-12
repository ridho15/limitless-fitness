<?php

namespace App\Filament\Resources\CustomerResource\Pages;

use App\Filament\Resources\CustomerResource;
use Filament\Pages\Actions;
use Filament\Pages\Actions\Action;
use Filament\Pages\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewCustomer extends ViewRecord
{
    protected static string $resource = CustomerResource::class;

    protected function getActions(): array
    {
        return [
            EditAction::make()
                ->icon('heroicon-o-pencil-alt'),
            Action::make('view-credit')
                ->label('View Session')
                ->icon('heroicon-o-arrow-circle-down')
                ->label('View Kredit')
                ->url(function () {
                    return CustomerResource::getUrl('kredit-customer', ['record' => $this->getRecord()]);
                })
        ];
    }
}
