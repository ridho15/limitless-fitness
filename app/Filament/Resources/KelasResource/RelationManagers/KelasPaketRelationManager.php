<?php

namespace App\Filament\Resources\KelasResource\RelationManagers;

use App\Models\Location;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KelasPaketRelationManager extends RelationManager
{
    protected static string $relationship = 'KelasPaket';

    protected static ?string $recordTitleAttribute = 'id_kelas';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('id_paket')
                    ->preload()
                    ->searchable()
                    ->relationship('paket', 'nama')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('paket.nama')
                    ->label('Nama Paket')
                    ->searchable(),
                TextColumn::make('paket.harga_formatted')
                    ->label('Harga')
                    ->sortable(),
                TextColumn::make('paket.valid_day')
                    ->label('Valid Day')
                    ->formatStateUsing(function ($state) {
                        return $state . ' Hari';
                    })->sortable(),
                TextColumn::make('paket.promote_code')
                    ->label('Promote Code'),
                TextColumn::make('paket.diskon')
                    ->label('Diskon')
                    ->formatStateUsing(function ($state) {
                        return $state . '%';
                    }),
                TextColumn::make('paket.location')
                    ->label('Location')
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
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
