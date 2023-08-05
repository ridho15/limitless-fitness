<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TimetableResource\Pages;
use App\Filament\Resources\TimetableResource\RelationManagers;
use App\Filament\Resources\TimetableResource\RelationManagers\BookingKelasRelationManager;
use App\Filament\Resources\TimetableResource\RelationManagers\KelasRatingRelationManager;
use App\Filament\Resources\TimetableResource\Widgets\CalendarWidget;
use App\Models\KelasActivity;
use App\Models\Timetable;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;

class TimetableResource extends Resource
{
    protected static ?string $model = KelasActivity::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    protected static ?string $navigationLabel = 'Timetable';

    protected static ?string $pluralModelLabel = 'Timetable';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        Fieldset::make('Form timetable')->schema([
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
                        ])
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_latihan')
                    ->searchable(),
                TextColumn::make('kelas.nama_kelas')
                    ->label('Kelas')
                    ->searchable(),
                TextColumn::make('locationStudio.nama')
                    ->label('Lokasi Studio')
                    ->searchable(),
                TextColumn::make('level_formatted')
                    ->sortable(),
                TextColumn::make('jam_mulai_formatted')
                    ->label('Jam Mulai')
                    ->sortable(),
                TextColumn::make('jam_selesai_formatted')
                    ->label('Jam Selesai')
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Instruktur')
                    ->searchable(),
                TextColumn::make('cancellation_policy')
                    ->searchable(),
                TextColumn::make('status_formatted')
                    ->label('Status Jadwal')
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make()
                ->after(function(){
                    return CalendarWidget::refreshEvents();
                }),
                ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            BookingKelasRelationManager::class,
            KelasRatingRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTimetables::route('/'),
            'create' => Pages\CreateTimetable::route('/create'),
            'edit' => Pages\EditTimetable::route('/{record}/edit'),
        ];
    }
}
