<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BiayaInstruktruResource\Pages;
use App\Filament\Resources\BiayaInstruktruResource\RelationManagers;
use App\Models\BiayaInstruktru;
use App\Models\Jurnal;
use App\Models\Location;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BiayaInstruktruResource extends Resource
{
    protected static ?string $model = Jurnal::class;

    protected static ?string $navigationIcon = 'heroicon-o-cash';

    protected static ?string $slug = 'biaya_instruktur';

    protected static ?string $title = 'Biaya Instruktur';

    protected static ?string $modelLabel = 'Biaya Instruktur';

    protected static ?string $pluralModelLabel = 'Biaya Instruktur';

    protected static ?string $navigationGroup = 'Account';

    protected static int $total = 0;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')
                    ->label('Laporan')
                    ->toggleable()
                    ->searchable(),
                TextColumn::make('tanggal')
                    ->sortable()
                    ->label('Tanggal')
                    ->formatStateUsing(function ($state) {
                        return Carbon::parse($state)->locale('id')->isoFormat('dddd, DD MMMM YYYY');
                    }),
                TextColumn::make('parent.orderPaket.paket.nama')
                    ->label('From Paket')
                    ->toggleable()
                    ->searchable(),
                TextColumn::make('parent.orderPaket.user.name')
                    ->label('From User')
                    ->toggleable()
                    ->searchable(),
                TextColumn::make('parent.orderPaket.location.nama')
                    ->label('Location')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('jumlah_uang')
                    ->label('Biaya')
                    ->formatStateUsing(function ($state) {
                        return 'Rp' . number_format($state, 0, ',', '.');
                    }),
                TextColumn::make('jumlah')
                    ->label('Total')
                    ->formatStateUsing(function ($record) {
                        static::$total += $record->jumlah_uang;
                        return 'Rp.' . number_format(static::$total, 0, ',', '.');
                    })
            ])
            ->filters([
                Filter::make('filter')
                    ->form([
                        Select::make('id_location')
                            ->label('Location')
                            ->options(Location::select(['nama', 'id'])->get()->pluck('nama', 'id'))
                            ->searchable(),
                        DatePicker::make('created_from')
                            ->label('Dari Tanggal'),
                        DatePicker::make('created_until')
                            ->label('Sampai Tanggal')
                    ])->query(function (Builder $query, $data) {
                        return $query->when($data['id_location'], function (Builder $query, $id_location) {
                            return $query->whereHas('orderPaket', function (Builder $query) use ($id_location) {
                                return $query->where('id_location', $id_location);
                            });
                        })->when($data['created_from'], function (Builder $query, $date) {
                            return $query->whereDate('tanggal', '>=', $date);
                        })->when($data['created_until'], function (Builder $query, $date) {
                            return $query->wheredate('tanggal', '<=', $date);
                        });
                    })
            ])
            ->actions([])
            ->bulkActions([]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBiayaInstruktrus::route('/'),
            'create' => Pages\CreateBiayaInstruktru::route('/create'),
            'edit' => Pages\EditBiayaInstruktru::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('tipe', 0)
            ->whereNotNull('id_parent');
    }
}
