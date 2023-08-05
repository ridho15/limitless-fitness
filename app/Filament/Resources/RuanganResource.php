<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RuanganResource\Pages;
use App\Filament\Resources\RuanganResource\RelationManagers;
use App\Models\Ruangan;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RuanganResource extends Resource
{
    protected static ?string $model = Ruangan::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive';

    protected static ?string $navigationGroup = 'Data Master';

    protected static ?string $modelLabel = 'Ruangan';

    protected static ?string $pluralModelLabel = 'Ruangan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Fieldset::make('Form Ruangan')
                    ->schema([
                        Select::make('id_location_studio')
                            ->label('Studio')
                            ->relationship('locationStudio', 'nama')
                            ->required()
                            ->searchable()
                            ->preload(),
                        TextInput::make('nama_ruangan')
                            ->required()
                            ->string()
                            ->maxLength(255),
                        TextInput::make('max_peserta')
                            ->label('Maximal peserta')
                            ->required()
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('locationStudio.nama')
                    ->label('Studio')
                    ->searchable(),
                TextColumn::make('nama_ruangan')
                    ->label('Nama Ruangan')
                    ->searchable(),
                TextColumn::make('max_peserta')
                    ->label("Maximal peserta")
                    ->sortable()
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make()
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListRuangans::route('/'),
            'create' => Pages\CreateRuangan::route('/create'),
            'edit' => Pages\EditRuangan::route('/{record}/edit'),
        ];
    }
}
