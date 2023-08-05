<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookingKelasResource\Pages;
use App\Filament\Resources\BookingKelasResource\RelationManagers;
use App\Models\BookingKelas;
use App\Models\KelasActivity;
use App\Models\LocationStudio;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BookingKelasResource extends Resource
{
    protected static ?string $model = BookingKelas::class;

    protected static ?string $navigationIcon = 'heroicon-o-color-swatch';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        Fieldset::make()
                            ->schema([
                                Select::make('id_user')
                                    ->label('Customer')
                                    ->preload()
                                    ->searchable()
                                    ->options(User::whereHas('roles', function ($query) {
                                        $query->where('name', 'Customer');
                                    })->get()->pluck('name', 'id')),
                                Select::make('id_kelas_activity')
                                    ->label('Kelas Aktifitas')
                                    ->preload()
                                    ->searchable()
                                    ->options(KelasActivity::get()->pluck('simple_detail', 'id')),
                                Radio::make('status_kehadiran')
                                    ->options([
                                        '' => 'Belum di ketahui',
                                        '1' => 'Hadir',
                                        '2' => 'Izin',
                                        '0' => 'Tidak hadir'
                                    ]),
                                Textarea::make('keterangan')
                                    ->nullable(),
                                Toggle::make('is_send_email')
                                    ->label('Send Email')
                                    ->nullable()
                            ])
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Nama Customer')
                    ->searchable(),
                TextColumn::make('kelasActivity.simple_detail')
                    ->label('Kelas'),
                ToggleColumn::make('is_send_email')
                    ->label('Send Email'),
                TextColumn::make('keterangan')
                    ->searchable(),
            ])
            ->actions([
                EditAction::make(),
                ViewAction::make(),
                DeleteAction::make(),
            ])
            ->filters([
                //
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
            'index' => Pages\ListBookingKelas::route('/'),
            'create' => Pages\CreateBookingKelas::route('/create'),
            'edit' => Pages\EditBookingKelas::route('/{record}/edit'),
        ];
    }
}
