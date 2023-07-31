<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KelasPertemuanResource\Pages;
use App\Filament\Resources\KelasPertemuanResource\RelationManagers;
use App\Models\KelasPertamuan;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;

class KelasPertemuanResource extends Resource
{
    protected static ?string $model = KelasPertamuan::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';

    protected static ?string $navigationLabel = 'Kelas Pertemuan';

    protected static ?string $pluralModelLabel = 'Jadwal Kelas Pertemuan';

    protected static ?int $navigationSort = 2;

    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Fieldset::make('Form Kelas Pertemuan')
                    ->schema([
                        Select::make('id_kelas')
                            ->searchable()
                            ->required()
                            ->relationship('kelas', 'nama_kelas')
                            ->preload(),
                        DateTimePicker::make('datetime_avaiable')
                            ->label('Waktu Pertemuan')
                            ->required(),
                        Radio::make('status')
                            ->label('Status Pertemuan')
                            ->options([
                                '0' => 'Dibatalkan',
                                '1' => 'Avaiable'
                            ])->required(),
                        Textarea::make('keterangan')
                            ->string()
                            ->nullable()
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kelas.nama_kelas')
                    ->label('Kelas')
                    ->searchable(),
                TextColumn::make('datetime_avaiable')
                    ->sortable(),
                TextColumn::make('status_formatted')
                    ->label('Status Pertemuan'),
                TextColumn::make('keterangan')
                    ->searchable()
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKelasPertemuans::route('/'),
            'create' => Pages\CreateKelasPertemuan::route('/create'),
            'edit' => Pages\EditKelasPertemuan::route('/{record}/edit'),
        ];
    }
}
