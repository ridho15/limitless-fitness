<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\CustomerResource;
use App\Models\OrderPaket;
use Carbon\Carbon;
use Closure;
use Filament\Tables;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class CustomerOrderExpired extends BaseWidget
{
    protected static ?string $heading = "Customer Akan Expired";

    protected static ?int $sort = 3;

    protected function getTableQuery(): Builder
    {
        $duaMinggu = Carbon::now()->addWeeks(2)->isoFormat('YYYY-MM-DD');
        $query = OrderPaket::where(function ($query) use ($duaMinggu) {
            $query->whereDate('expired_date', '>=', date('Y-m-d'))
                ->whereDate('expired_date', "<=", $duaMinggu);
        })
            ->where('is_active', 1)
            ->orderBy('expired_date', 'DESC');
        return $query;
    }

    protected function getTableActions(): array
    {
        return [
            ViewAction::make()
                ->url(function ($record) {
                    return CustomerResource::getUrl('kredit-customer', ['record' => $record->user]);
                })
        ];
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('expired_date')->sortable(),
            TextColumn::make('user.name')
                ->label('Customer')
                ->searchable(),
            TextColumn::make('paket.nama')
                ->label('Paket')
                ->searchable(),
            TextColumn::make('jumlah_pertemuan')
                ->sortable(),
            TextColumn::make('status_formatted')
                ->label('Status'),
            BadgeColumn::make('is_active')
                ->toggleable()
                ->label('Status Aktif')
                ->enum([
                    0 => 'Tidak aktif',
                    1 => 'Aktif'
                ])
                ->colors([
                    'danger' => 0,
                    'primary' => 1
                ]),
            TextColumn::make('created_at')
                ->label('Tanggal Order')
                ->sortable(),
            TextColumn::make('tanggal_pembayaran')->toggleable()->sortable()
        ];
    }
}
