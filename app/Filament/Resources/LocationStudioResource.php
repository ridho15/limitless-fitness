<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LocationStudioResource\Pages;
use App\Filament\Resources\LocationStudioResource\RelationManagers;
use App\Models\LocationStudio;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class LocationStudioResource extends Resource
{
    protected static ?string $model = LocationStudio::class;

    protected static ?string $navigationIcon = 'heroicon-o-library';

    protected static ?string $navigationGroup = 'Data Master';

    protected static ?string $modelLabel = 'Studio';

    protected static ?string $pluralModelLabel = 'Studio';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Fieldset::make('Form Studio')
                    ->schema([
                        TextInput::make('nama')
                            ->label('Nama Studio')
                            ->string()
                            ->required(),
                        TextInput::make('equipment')
                            ->helperText('Pisahkan setiap equipment dengan tanda koma ","')
                            ->nullable(),
                        FileUpload::make('image')
                            ->image()
                            ->maxSize(2048)
                            ->required()
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->circular()
                    ->width(100)
                    ->height(100),
                TextColumn::make('location.nama')
                    ->label('Location')
                    ->searchable(),
                TextColumn::make('nama')
                    ->label('Nama')
                    ->searchable(),
                TextColumn::make('equipment'),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make()
                    ->after(function () {
                        activity()
                            ->causedBy(Auth::user())
                            ->log('Menghapus data studio');
                    }),
                ViewAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
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
            'index' => Pages\ListLocationStudios::route('/'),
            'create' => Pages\CreateLocationStudio::route('/create'),
            'edit' => Pages\EditLocationStudio::route('/{record}/edit'),
        ];
    }
}
