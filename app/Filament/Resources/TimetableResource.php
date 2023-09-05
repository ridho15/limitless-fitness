<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TimetableResource\Pages;
use App\Filament\Resources\TimetableResource\Pages\ManageTimetablePage;
use App\Filament\Resources\TimetableResource\Pages\ViewTimetable;
use App\Filament\Resources\TimetableResource\RelationManagers\BookingKelasRelationManager;
use App\Filament\Resources\TimetableResource\Widgets\ManageTimetableWidget;
use App\Models\Kelas;
use App\Models\KelasActivity;
use App\Models\LocationStudio;
use App\Models\Ruangan;
use App\Models\User;
use Carbon\Carbon;
use Closure;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
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

    public $instruktur = [];

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
                                        ->options(Kelas::get()->pluck('nama_kelas', 'id'))
                                        ->required(),
                                    Select::make('id_location_studio')
                                        ->label('Studio')
                                        ->searchable()
                                        ->preload()
                                        ->required()
                                        ->options(LocationStudio::get()->pluck('nama', 'id')),
                                    DatePicker::make('tanggal')
                                        ->label('Tanggal')
                                        ->disabled(function ($state) {
                                            if (Auth::user()->hasRole('Super Admin')) {
                                                return false;
                                            }

                                            return true;
                                        })
                                        ->required(),
                                    Select::make('jam_mulai')
                                        ->label('Waktu Mulai')
                                        ->multiple(function () {
                                            if (request()->segment(4) == 'edit') {
                                                return false;
                                            } else {
                                                return true;
                                            }
                                        })
                                        ->disabled(function ($state) {
                                            if (Auth::user()->hasRole('Super Admin')) {
                                                return false;
                                            }

                                            return true;
                                        })
                                        ->reactive()
                                        ->searchable()
                                        ->preload()
                                        ->options(function (Closure $get) {
                                            $jam = [];
                                            for ($i = 0; $i <= 24; $i++) {
                                                $time = sprintf("%02d", $i) . ':00:00';
                                                array_push($jam, $time);
                                            }
                                            return $jam;
                                        })
                                        ->required(),
                                    Select::make('id_user')
                                        ->label('Instruktur')
                                        ->preload()
                                        ->searchable()
                                        ->disabledOn('edit')
                                        ->options(function (Closure $get) {
                                            if ($get('jam_mulai') != null && !is_string($get('jam_mulai'))) {
                                                $jam = [];
                                                foreach ($get('jam_mulai') as $item) {
                                                    $time = sprintf("%02d", $item) . ':00:00';
                                                    array_push($jam, $time);
                                                }
                                            } else {
                                                $jam = $get('jam_mulai');
                                            }
                                            $instruktur = User::whereHas('roles', function($query){
                                                $query->where('name', 'Instruktur');
                                            })
                                            // whereHas('kelasActivity', function ($query) use ($jam, $get) {
                                                // $query->where('tanggal', date('Y-m-d', strtotime($get('tanggal'))))
                                                    // ->where('id_kelas', null)
                                                    // ->where('id_location_studio', null)
                                                    // ->whereJamMulai($jam);
                                            // })
                                            ->get()->pluck('name', 'id');

                                            return $instruktur;
                                        })
                                        ->required(),
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

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
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
                TextColumn::make('nama_latihan')
                    ->searchable(),
                TextColumn::make('kelas.nama_kelas')
                    ->label('Kelas')
                    ->searchable(),
                TextColumn::make('locationStudio.nama')
                    ->label('Studio')
                    ->searchable(),
                TextColumn::make('level_formatted')
                    ->label('Level'),
                TextColumn::make('status_aktivitas_kelas')
                    ->label('Status Jadwal')
            ])
            ->filters([])
            ->actions([
                EditAction::make(),
                DeleteAction::make()
                    ->after(function () {
                        activity()
                            ->causedBy(Auth::user())
                            ->log('Menghapus data timetable');
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
            // 'index' => Pages\ListTimetables::route('/'),
            'index' => ManageTimetablePage::route('/'),
            'create' => Pages\CreateTimetable::route('/create'),
            'view' => ViewTimetable::route('/{record}'),
            'edit' => Pages\EditTimetable::route('/{record}/edit'),
        ];
    }

    protected static function shouldRegisterNavigation(): bool
    {
        return Auth::user()->hasRole('Instruktur') ? false : true;
    }
}
