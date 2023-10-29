<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\CustomerResource;
use App\Models\OrderPaket;
use Carbon\Carbon;
use Closure;
use DateTime;
use Filament\Tables;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

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
        })->where('sisa', '>', 0)
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
            TextColumn::make('user.name')
                ->label('Customer')
                ->searchable(),
            TextColumn::make('paket.nama')
                ->label('Paket')
                ->searchable(),
            TextColumn::make('jumlah_pertemuan')
                ->alignCenter()
                ->sortable(),
            TextColumn::make('sisa')
                ->label('Sisa Paket')
                ->searchable(),
            TextColumn::make('expired_date')
                ->label('Hari Akan Habis')
                ->formatStateUsing(function ($state) {
                    $jarak = Carbon::now()->diff($state);
                    return $jarak->days . ' Days';
                })
                ->sortable()
        ];
    }
}
