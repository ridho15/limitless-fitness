<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\CustomerResource;
use App\Models\Location;
use App\Models\OrderPaket;
use Carbon\Carbon;
use Closure;
use DateTime;
use Filament\Forms\Components\Select;
use Filament\Tables;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class CustomerOrderExpired extends BaseWidget
{
    protected static ?string $heading = "Customer Akan Expired";

    protected static ?int $sort = 3;

    public $id_location = null;

    protected function getTableQuery(): Builder
    {
        $duaMinggu = Carbon::now()->addWeeks(2)->isoFormat('YYYY-MM-DD');
        $query = OrderPaket::when($this->id_location, function (Builder $query) {
            return $query->where('id_location', $this->id_location);
        })->where(function ($query) use ($duaMinggu) {
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
            TextColumn::make('user.name')
                ->label('Customer')
                ->searchable(),
            TextColumn::make('paket.nama')
                ->label('Paket')
                ->searchable(),
            TextColumn::make('expired_date')
                ->label('Hari Akan Habis')
                ->toggleable()
                ->formatStateUsing(function ($state) {
                    $jarak = Carbon::now()->diff($state);
                    if ($jarak->days > 0) {
                        return $jarak->days . ' Days';
                    } else {
                        return 'Hari Ini';
                    }
                })
                ->sortable(),
            TextColumn::make('location.nama')
                ->searchable()
                ->toggleable()
                ->label('Lokasi')
        ];
    }

    protected function getTableFilters(): array
    {
        return [
            Filter::make('lokasi')
                ->form([
                    Select::make('id_location')
                        ->searchable()
                        ->label('Lokasi')
                        ->options(Location::select(['nama', 'id'])->get()->pluck('nama', 'id'))
                ])->indicateUsing(function ($data) {
                    $this->id_location = $data['id_location'];
                })
        ];
    }
}
