<?php

namespace App\Filament\Resources\VoucherResource\Pages;

use App\Filament\Resources\VoucherResource;
use App\Models\Voucher;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\Position;
use Illuminate\Database\Eloquent\Builder;

class ListVouchers extends ListRecords
{
    protected static string $resource = VoucherResource::class;

    protected function getTableQuery(): Builder
    {
        return parent::getTableQuery()->orderBy('created_at', 'DESC');
    }

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Voucher')
                ->icon('heroicon-o-plus-circle'),
        ];
    }

    protected function getTableActionsPosition(): ?string
    {
        return Position::BeforeCells;
    }
}
