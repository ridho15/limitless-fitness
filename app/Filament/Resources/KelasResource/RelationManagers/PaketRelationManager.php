<?php

namespace App\Filament\Resources\KelasResource\RelationManagers;

use App\Filament\Resources\PaketResource;
use App\Models\Location;
use App\Models\Paket;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TextInput\Mask;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PaketRelationManager extends RelationManager
{
    protected static string $relationship = 'Paket';

    protected static ?string $recordTitleAttribute = 'id_kelas';

    public static function form(Form $form): Form
    {
        $location = Location::get()->pluck('nama', 'id')->toArray();
        $location[0] = 'All location';
        return $form
            ->schema([
                TextInput::make('nama')
                    ->label('Nama Paket')
                    ->string()
                    ->maxLength(255)
                    ->required(),
                TextInput::make('jumlah_pertemuan')
                    ->numeric()
                    ->required(),
                TextInput::make('valid_day')
                    ->numeric()
                    ->required()
                    ->default(0),
                Select::make('location')
                    ->preload()
                    ->searchable()
                    ->options($location),
                TextInput::make('harga')
                    ->numeric()
                    ->mask(fn (Mask $mask) => $mask->patternBlocks([
                        'uang' => fn (Mask $mask) => $mask->numeric()
                            ->thousandsSeparator('.')
                            ->decimalSeparator(',')
                    ])->pattern('Rp.uang'))
                    ->required(),
                TextInput::make('promote_code'),
                TextInput::make('diskon')
                    ->numeric()
                    ->default(0)
                    ->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')
                    ->label('Nama Paket')
                    ->searchable(),
                TextColumn::make('jumlah_pertemuan')
                    ->label('Jumlah Pertemuan')
                    ->formatStateUsing(function ($state) {
                        return $state . ' Pertemuan';
                    })
                    ->sortable(),
                TextColumn::make('harga_formatted')
                    ->label('Harga')
                    ->sortable(),
                TextColumn::make('diskon')
                    ->formatStateUsing(function ($state) {
                        return $state . '%';
                    }),
                TextColumn::make('harga_diskon')
                    ->label('Harga Diskon')
                    ->formatStateUsing(function ($state) {
                        return 'Rp.' . number_format($state, 0, ',', '.');
                    })
                    ->sortable(),
                TextColumn::make('valid_day')
                    ->formatStateUsing(function ($state) {
                        return $state . ' Hari';
                    })->sortable(),
                TextColumn::make('promote_code'),

                TextColumn::make('location')
                    ->formatStateUsing(function ($state) {
                        if ($state == 0) {
                            return 'All Location';
                        } else {
                            $location = Location::find($state);
                            return $location->nama;
                        }
                    })
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                ViewAction::make()
                ->url(function($record){
                    return PaketResource::getUrl('view', ['record' => $record]);
                })
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
