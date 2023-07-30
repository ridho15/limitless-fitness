<?php

namespace App\Filament\Resources\KelasResource\RelationManagers;

use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
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

class KelasActivityRelationManager extends RelationManager
{
    protected static string $relationship = 'KelasActivity';

    protected static ?string $recordTitleAttribute = 'id_kelas';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('id_location_studio')
                    ->searchable()
                    ->preload()
                    ->relationship('locationStudio', 'nama')
                    ->required(),
                DateTimePicker::make('jam_latihan')
                    ->required(),
                TextInput::make('max_anggota')
                    ->numeric()
                    ->default(0)
                    ->required(),
                Select::make('id_user')
                    ->label('Instruktur')
                    ->preload()
                    ->searchable()
                    ->options(User::whereHas('roles', function ($query) {
                        $query->where('name', 'Instruktur');
                    })->get()->pluck('name', 'id'))
                    ->required(),
                Select::make('status')
                    ->searchable()
                    ->required()
                    ->options([
                        '0' => 'Dibatalkan',
                        '1' => 'Set',
                        '2' => 'Sedang berlangsung',
                        '3' => 'Sudah lewat',
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('locationStudio.nama')
                    ->label('Lokasi Studio')
                    ->searchable(),
                TextColumn::make('jam_latihan')
                    ->sortable(),
                TextColumn::make('max_anggota')
                    ->label('Jumlah Maximal')
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Instruktur')
                    ->searchable(),
                TextColumn::make('status_formatted')
                    ->label('Status Jadwal')
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Buat Kelas Aktifitas')
                    ->icon('heroicon-o-plus-circle')
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make()
            ]);
    }
}
