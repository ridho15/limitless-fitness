<?php

namespace App\Filament\Resources\LocationResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LocationStudioRelationManager extends RelationManager
{
    protected static string $relationship = 'LocationStudio';

    protected static ?string $recordTitleAttribute = 'id_location';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nama')
                    ->label('Nama Studio')
                    ->string()
                    ->required(),
                Select::make('level')
                    ->searchable()
                    ->options([
                        '0' => 'Semua Level',
                        '1' => 'Intermediate',
                        '2' => 'Beginer',
                        '3' => 'Advanced'
                    ])->required(),
                TextInput::make('equipment')
                    ->helperText('Pisahkan setiap equipment dengan tanda koma ","')
                    ->nullable(),
                TextInput::make('max_peserta')
                    ->numeric()
                    ->default(0)
                    ->required(),
                FileUpload::make('image')
                    ->image()
                    ->maxSize(2048)
                    ->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Tambah Lokasi Studio')
                    ->icon('heroicon-o-plus-circle')
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make()
            ])
            ->bulkActions([
                DeleteBulkAction::make()
            ]);
    }
}
