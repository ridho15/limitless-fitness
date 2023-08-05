<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaketResource\Pages;
use App\Filament\Resources\PaketResource\RelationManagers;
use App\Filament\Resources\PaketResource\RelationManagers\KelasPaketRelationManager;
use App\Models\Kelas;
use App\Models\Location;
use App\Models\Paket;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TextInput\Mask;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;

class PaketResource extends Resource
{
    protected static ?string $model = Paket::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-copy';

    protected static ?string $navigationGroup = 'Data Master';

    public static function form(Form $form): Form
    {
        $location = Location::get()->pluck('nama', 'id')->toArray();
        $location[0] = 'All location';
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        Fieldset::make('Form Paket')
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
                            ])
                    ])
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
                    ->sortable(),
                TextColumn::make('valid_day')
                    ->formatStateUsing(function ($state) {
                        return $state . ' Hari';
                    })->sortable(),
                TextColumn::make('promote_code'),
                TextColumn::make('diskon')
                    ->formatStateUsing(function ($state) {
                        return $state . '%';
                    }),
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
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
                ViewAction::make()
            ])
            ->bulkActions([
                DeleteBulkAction::make()
            ]);
    }

    public static function getRelations(): array
    {
        return [
            KelasPaketRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPakets::route('/'),
            'create' => Pages\CreatePaket::route('/create'),
            'edit' => Pages\EditPaket::route('/{record}/edit'),
        ];
    }
}
