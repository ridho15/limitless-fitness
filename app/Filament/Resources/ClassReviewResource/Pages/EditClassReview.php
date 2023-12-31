<?php

namespace App\Filament\Resources\ClassReviewResource\Pages;

use App\Filament\Resources\ClassReviewResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditClassReview extends EditRecord
{
    protected static string $resource = ClassReviewResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
