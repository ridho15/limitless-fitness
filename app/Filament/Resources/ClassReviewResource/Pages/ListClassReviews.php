<?php

namespace App\Filament\Resources\ClassReviewResource\Pages;

use App\Filament\Resources\ClassReviewResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListClassReviews extends ListRecords
{
    protected static string $resource = ClassReviewResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
