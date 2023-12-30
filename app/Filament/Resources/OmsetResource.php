<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OmsetResource\Pages;
use App\Models\Jurnal;
use App\Models\Location;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class OmsetResource extends Resource
{
    protected static ?string $model = Jurnal::class;

    protected static ?string $navigationIcon = 'heroicon-o-cash';

    protected static ?string $navigationLabel = 'Omset';

    protected static ?string $slug = 'Omset';

    protected static ?string $title = 'Omset';

    protected static ?string $pluralModelLabel = 'Omset';

    protected static ?string $navigationGroup = 'Account';

    protected static int $omset = 0;
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
                    ->label('Nama')
                    ->searchable(),
                TextColumn::make('orderPaket.paket.nama')
                    ->label('Paket')
                    ->searchable(),
                TextColumn::make('tanggal')
                    ->searchable()
                    ->label('Tanggal')
                    ->formatStateUsing(function ($state) {
                        return Carbon::parse($state)->locale('id')->isoFormat('dddd, DD MMMM YYYY');
                    }),
                TextColumn::make('orderPaket.location.nama')
                    ->label('Location')
                    ->toggleable()
                    ->searchable(),
                TextColumn::make('jumlah_uang')
                    ->searchable()
                    ->label('Jumlah Uang')
                    ->formatStateUsing(function ($state) {
                        return 'Rp.' . number_format($state, 0, ',', '.');
                    }),
                TextColumn::make('omset')
                    ->label('Omset')
                    ->formatStateUsing(function ($record) {
                        static::$omset += $record->jumlah_uang;
                        return 'Rp.' . number_format(static::$omset, 0, ',', '.');
                    }),

            ])
            ->filters([
                Filter::make('tanggal')
                    ->label('Tanggal')
                    ->form([
                        DatePicker::make('tanggal_pembayaran_from')
                            ->label('Tanggal Dari')->default(Carbon::now()->startOfMonth()),
                        DatePicker::make('tanggal_pembayaran_to')
                            ->label('Tanggal ke')->default(Carbon::now()->endOfMonth()),
                    ])->query(function (Builder $query, array $data) {
                        return $query->when($data['tanggal_pembayaran_from'], function (Builder $query, $date) {
                            return $query->where('tanggal', '>=', $date);
                        })->when($data['tanggal_pembayaran_to'], function (Builder $query, $date) {
                            return $query->where('tanggal', '<=', $date);
                        });
                    }),
                SelectFilter::make('id_location')
                    ->label('Location')
                    ->searchable()
                    ->options(Location::select(['nama', 'id'])->get()->pluck('nama', 'id'))
                    ->query(function (Builder $query, $data) {
                        return $query->when($data['value'], function (Builder $query, $id_location) {
                            return $query->whereHas('orderPaket', function (Builder $query) use ($id_location) {
                                return $query->where('id_location', $id_location);
                            });
                        });
                    })
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\DeleteBulkAction::make(),
            ]);
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
            'index' => Pages\ListOmsets::route('/'),
            // 'create' => Pages\CreateOmset::route('/create'),
            // 'edit' => Pages\EditOmset::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('tipe', 1)->orderBy('tanggal', 'ASC');
    }

    protected static function shouldRegisterNavigation(): bool
    {
        return Auth::user()->hasRole('Super Admin');
    }
}
