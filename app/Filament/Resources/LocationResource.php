<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LocationResource\Pages;
use App\Filament\Resources\LocationResource\RelationManagers\LocationOpeningRelationManager;
use App\Filament\Resources\LocationResource\RelationManagers\LocationStudioRelationManager;
use App\Models\Location;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Auth;

class LocationResource extends Resource
{
    protected static ?string $model = Location::class;

    protected static ?string $navigationIcon = 'heroicon-o-location-marker';

    protected static ?string $navigationGroup = 'Data Master';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        Fieldset::make('Form Location')
                            ->schema([
                                TextInput::make('nama')
                                    ->string()
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('email')
                                    ->email()
                                    ->unique(ignoreRecord: true)
                                    ->required(),
                                TextInput::make('no_hp')
                                    ->label('Nomor Handphone')
                                    ->tel()
                                    ->telRegex('/^(\+62|0)(\d{8,12})$/')
                                    ->required(),
                                TextInput::make('nama_bank')
                                    ->required()
                                    ->label('Nama Bank'),
                                TextInput::make('no_rekening')
                                    ->required()
                                    ->label('Nomor Rekening'),
                                TextInput::make('atas_nama')
                                    ->label('Atas Nama Rekening')
                                    ->required(),
                                Textarea::make('deskripsi')
                                    ->nullable(),
                                Textarea::make('alamat')
                                    ->required()
                                    ->string()
                                    ->maxLength(255),
                                TextInput::make('url_location')
                                    ->required()
                                    ->maxLength(255)
                                    ->string(),
                                FileUpload::make('image')
                                    ->image()
                                    ->required()
                                    ->maxSize(2048)
                            ])
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')
                    ->searchable(),
                TextColumn::make('no_hp')
                    ->searchable(),
                TextColumn::make('email')
                    ->searchable(),
                TextColumn::make('nama_bank')
                    ->label('Nama Bank')
                    ->searchable(),
                TextColumn::make('no_rekening')
                    ->label('Nomor Rekening')
                    ->searchable(),
                TextColumn::make('atas_nama')
                    ->label('Atas Nama Rekening')
                    ->searchable(),
                TextColumn::make('deskripsi')
                    ->searchable(),
                TextColumn::make('alamat')
                    ->searchable(),
                TextColumn::make('url_location'),
                ImageColumn::make('image'),
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    EditAction::make(),
                    DeleteAction::make()
                        ->after(function () {
                            activity()
                                ->causedBy(Auth::user())
                                ->log('Menghapus data location');
                        }),
                    ViewAction::make()
                ])
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            LocationOpeningRelationManager::class,
            LocationStudioRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLocations::route('/'),
            'create' => Pages\CreateLocation::route('/create'),
            'edit' => Pages\EditLocation::route('/{record}/edit'),
        ];
    }
}
