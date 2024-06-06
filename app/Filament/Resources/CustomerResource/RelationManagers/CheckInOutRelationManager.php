<?php

namespace App\Filament\Resources\CustomerResource\RelationManagers;

use App\Models\Location;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;

class CheckInOutRelationManager extends RelationManager
{
    protected static string $relationship = 'checkInOut';

    protected static ?string $recordTitleAttribute = 'Check In';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Fieldset::make('Form Check In Out')
                    ->schema([
                        Select::make('id_location')
                            ->searchable()
                            ->label('Location')
                            ->preload()
                            ->required()
                            ->options(Location::get()->pluck('nama', 'id')),
                        Select::make('id_order_paket')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->reactive()
                            ->label('Order Paket')
                            ->options(function ($get) {
                                return $get('list_order_paket');
                            }),
                        DateTimePicker::make('jadwal')
                            ->label('Jadwal')
                            ->required(),
                        Radio::make('status')
                            ->inline()
                            ->label('Masuk/Keluar')
                            ->options([
                                '1' => "Masuk",
                                '2' => "Keluar"
                            ])
                            ->required()
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable(),
                TextColumn::make('location.nama')
                    ->label('Lokasi')
                    ->searchable(),
                TextColumn::make('orderPaket.paket.nama')
                    ->label('Paket')
                    ->searchable(),
                TextColumn::make('jadwal')
                    ->label('Jadwal')
                    ->sortable(),
                BadgeColumn::make('status')
                    ->label('Status Check In')
                    ->toggleable()
                    ->color(function ($state) {
                        if ($state == 1) {
                            return 'success';
                        } else {
                            return 'secondary';
                        }
                    })
                    ->formatStateUsing(function ($state) {
                        if ($state == 1) {
                            return 'Masuk';
                        } else {
                            return "Keluar";
                        }
                    })
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Tambah Check In')
                    ->icon('heroicon-o-plus-circle'),
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
