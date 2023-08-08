<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TimetableResource\Pages;
use App\Filament\Resources\TimetableResource\RelationManagers\BookingKelasRelationManager;
use App\Filament\Resources\TimetableResource\Widgets\ManageTimetableWidget;
use App\Models\KelasActivity;
use App\Models\User;
use Carbon\Carbon;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TimePicker;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
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
                                    DatePicker::make('tanggal')
                                        ->label('Tanggal')
                                        ->required(),
                                    TimePicker::make('jam_mulai')
                                        ->label('Waktu Mulai')
                                        ->required(),
                                    TimePicker::make('jam_selesai')
                                        ->label('Waktu Selesai')
                                        ->hiddenOn(['edit', 'view'])    
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
                TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->formatStateUsing(function ($state) {
                        return Carbon::parse($state)->locale('id')->isoFormat('DD MMMM YYYY');
                    })
                    ->sortable(),
                TextColumn::make('jam_mulai')
                    ->label('Jam Mulai')
                    ->formatStateUsing(function ($state) {
                        return Carbon::parse($state)->locale('id')->isoFormat('HH:mm');
                    })
                    ->sortable(),

                TextColumn::make('user.name')
                    ->label('Instruktur')
                    ->searchable(),
                TextColumn::make('status_aktivitas_kelas')
                    ->label('Status Jadwal')
            ])
            ->filters([])
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
            // KelasRatingRelationManager::class
        ];
    }

    public static function getWidgets(): array
    {
        return [
            ManageTimetableWidget::class
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

    protected static function shouldRegisterNavigation(): bool
    {
        return Auth::user()->hasRole('Instruktur') ? false : true;
    }
}
