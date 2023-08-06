<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TimetableResource\Pages;
use App\Filament\Resources\TimetableResource\RelationManagers\BookingKelasRelationManager;
use App\Filament\Resources\TimetableResource\RelationManagers\KelasRatingRelationManager;
use App\Filament\Resources\TimetableResource\Widgets\CalendarWidget;
use App\Models\KelasActivity;
use App\Models\Timetable;
use App\Models\User;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\RichEditor;
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
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

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
                            Grid::make()
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
                                        ->hidden(Auth::user()->hasRole('Instruktur') ? true : false)
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
                                ])->columns(2),
                            RichEditor::make('cancellation_policy')
                                ->label('Terms and Conditions')
                                ->string()
                                ->default('1. All payments must be received before your session begins.
                                    2. Booking: All sessions must be booked in advance. Please
                                    call our studio or use our online booking system to schedule
                                    your session.
                                    3. Attendance: Please arrive on time for your session. If you
                                    arrive late, the session will still end at the scheduled time, and
                                    you will still be charged the full session rate.
                                    4. Attire: Please wear comfortable clothing that allows you to
                                    move freely. We recommend wearing grip socks for hygiene
                                    easons.
                                    5. Equipment: Our studio provides mats, blocks, straps, and
                                    other necessary equipment. Please do not bring your own
                                    equipment unless approved by our instructors.
                                    6. Health and Safety: Please inform your instructor of any
                                    injuries or health conditions before starting your session. If you
                                    feel any discomfort or pain during the session, inform your
                                    instructor immediately.
                                    7. If class is less than 2 pax in 1 session, studio is able to
                                    cancel the class 3 hours before conduct,
                                    8. Reschedule and Refunds:
                                    a. All sessions purchased are non-refundable.
                                    b. Rescheduling of a session must be done at least 24
                                    hours before the scheduled session time.
                                    c. Late cancellations, Reschedule or no-shows will result in a
                                    penalty made less than 24 hours before the scheduled session
                                    time will result in the forfeit of that session.
                                    d. Exceptions to these rules will be made in the event of
                                    sudden illness or emergency situations, and will be
                                    handled on a case-by-case basis.')
                                ->required(),
                        ])->columns(1)
                    ])
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        if (Auth::user()->hasRole('Instruktur')) {
            return parent::getEloquentQuery()->where('id_user', Auth::id());
        } else {
            return parent::getEloquentQuery();
        }
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
                TextColumn::make('ruangan.locationStudio.nama')
                    ->label('Lokasi Studio')
                    ->searchable(),
                TextColumn::make('ruangan.nama_ruangan')
                    ->label('Ruangan')
                    ->searchable(),
                TextColumn::make('level_formatted')
                    ->label('Level'),
                TextColumn::make('jam_mulai')
                    ->label('Jam Mulai')
                    ->formatStateUsing(function ($state) {
                        return Carbon::parse($state)->locale('id')->isoFormat('DD MMMM YYYY HH:mm');
                    })
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Instruktur')
                    ->searchable(),
                TextColumn::make('cancellation_policy')
                    ->label('Term and Conditions')
                    ->searchable(),
                TextColumn::make('status_formatted')
                    ->label('Status Jadwal')
            ])
            ->filters([
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make()
                    ->after(function () {
                        return redirect(TimetableResource::getUrl('index'));
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
