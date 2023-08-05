<?php

namespace App\Filament\Resources\PaketResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KelasPaketRelationManager extends RelationManager
{
    protected static string $relationship = 'KelasPaket';

    protected static ?string $recordTitleAttribute = 'id_paket';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('id_kelas')
                    ->preload()
                    ->searchable()
                    ->relationship('kelas', 'nama_kelas')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kelas.nama_kelas')
                    ->label('Kelas')
                    ->searchable(),
                TextColumn::make('kelas.keterangan'),
                TextColumn::make('kelas.duration')
                    ->label('Durasi')
                    ->sortable(),
                TextColumn::make('kelas.bawaan')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Tambah Kelas')
                    ->icon('heroicon-o-plus-circle')
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }
}
