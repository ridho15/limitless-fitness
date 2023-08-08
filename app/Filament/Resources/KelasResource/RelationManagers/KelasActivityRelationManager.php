<?php

namespace App\Filament\Resources\KelasResource\RelationManagers;

use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;

class KelasActivityRelationManager extends RelationManager
{
    protected static string $relationship = 'KelasActivity';

    protected static ?string $label = "Timetable";

    protected static ?string $pluralModelLabel = "Timetable";

    protected static ?string $recordTitleAttribute = 'id_kelas';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nama_latihan')
                    ->string()
                    ->required()
                    ->maxLength(255),
                Select::make('id_kelas')
                    ->label('Kelas')
                    ->preload()
                    ->searchable()
                    ->relationship('kelas', 'nama_kelas')
                    ->required(),
                Select::make('id_ruangan')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->relationship('ruangan', 'nama_ruangan'),
                Select::make('level')
                    ->required()
                    ->options([
                        '0' => 'Open to all',
                        '1' => 'Intermediate',
                        '2' => 'Beginer',
                        '3' => 'Advanced'
                    ]),
                DatePicker::make('tanggal')
                    ->label('Tanggal')
                    ->required(),
                TimePicker::make('jam_mulai')
                    ->label('Waktu Mulai')
                    ->required(),
                TimePicker::make('jam_selesai')
                    ->label('Waktu Selesai')
                    ->hiddenOn('edit')
                    ->required(),
                Select::make('id_user')
                    ->label('Instruktur')
                    ->preload()
                    ->searchable()
                    ->options(User::whereHas('roles', function ($query) {
                        $query->where('name', 'Instruktur');
                    })->get()->pluck('name', 'id')),
                Select::make('status')
                    ->searchable()
                    ->default(1)
                    ->options([
                        '0' => 'Dibatalkan',
                        '1' => 'Set',
                        '2' => 'Sedang berlangsung',
                        '3' => 'Sudah lewat',
                    ])->hidden(),
                Textarea::make('cancellation_policy')
                    ->label('Terms and Conditions')
                    ->string()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_latihan')
                    ->searchable(),
                TextColumn::make('ruangan.locationStudio.nama')
                    ->label('Lokasi Studio')
                    ->searchable(),
                TextColumn::make('ruangan.nama_ruangan')
                    ->label('Ruangan')
                    ->searchable(),
                TextColumn::make('level_formatted')
                    ->sortable(),
                TextColumn::make('jam_mulai_formatted')
                    ->label('Jam Mulai')
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Instruktur')
                    ->searchable(),
                TextColumn::make('status_formatted')
                    ->label('Status Jadwal'),
                TextColumn::make('cancellation_policy')
                    ->searchable(),
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
