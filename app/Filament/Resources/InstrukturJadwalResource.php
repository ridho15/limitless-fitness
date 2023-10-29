<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InstrukturJadwalResource\Pages;
use App\Filament\Resources\InstrukturJadwalResource\Pages\ManageTimetablePage;
use App\Filament\Resources\InstrukturJadwalResource\Pages\ViewInstrukturJadwal;
use App\Filament\Resources\InstrukturJadwalResource\RelationManagers;
use App\Filament\Resources\InstrukturJadwalResource\RelationManagers\BookingKelasRelationManager;
use App\Filament\Resources\InstrukturJadwalResource\Widgets\ManageTimetableWidget;
use App\Models\InstrukturJadwal;
use App\Models\KelasActivity;
use Carbon\Carbon;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Select;
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
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class InstrukturJadwalResource extends Resource
{
    protected static ?string $model = KelasActivity::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';

    protected static ?string $label = "Atur Jadwal";

    protected static ?string $modelLabel = 'Atur Jadwal';

    protected static ?string $pluralModelLabel = 'Atur Jadwal';

    protected static ?string $slug = 'atur-jadwal';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        Fieldset::make('Form Jadwal')
                            ->schema([
                                TextInput::make('instruktur')
                                    ->formatStateUsing(function ($record) {
                                        if ($record) {
                                            return $record->user->name;
                                        } else {
                                            return null;
                                        }
                                    })->hidden(function () {
                                        if (Auth::user()->hasRole('Instruktur')) {
                                            return true;
                                        } else {
                                            return false;
                                        }
                                    })->disabled(),
                                TextInput::make('kelas')
                                    ->formatStateUsing(function ($record) {
                                        if ($record) {
                                            return $record->kelas->nama_kelas;
                                        } else {
                                            return null;
                                        }
                                    })->hidden(function () {
                                        if (Auth::user()->hasRole('Instruktur')) {
                                            return true;
                                        } else {
                                            return false;
                                        }
                                    })
                                    ->disabled(),
                                DatePicker::make('tanggal')
                                    ->label('Tanggal Available')
                                    ->required(),
                                TimePicker::make('jam_mulai')
                                    ->label('Waktu Mulai')
                                    ->required(),
                                TimePicker::make('jam_selesai')
                                    ->label('Waktu Selesai')
                                    ->hiddenOn(['edit', 'view'])
                                    ->required(),
                                TextInput::make('nama_latihan')
                                    ->disabled(function (Closure $get) {
                                        if ($get('id_kelas') != null && $get('id_location_studio') != null) {
                                            return false;
                                        }

                                        return true;
                                    })
                                    ->label('Nama Latihan'),
                                Select::make('level')
                                    ->disabled(function (Closure $get) {
                                        if ($get('id_kelas') != null && $get('id_location_studio') != null) {
                                            return false;
                                        }

                                        return true;
                                    })
                                    ->options([
                                        '0' => 'Semua Level',
                                        '1' => 'Intermediate',
                                        '2' => 'Beginer',
                                        '3' => 'Advanced'
                                    ])
                            ])
                    ])
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        if (Auth::user()->hasRole('Instruktur')) {
            return parent::getEloquentQuery()->where('id_user', Auth::id())->orderBy('tanggal','DESC')->orderBy('jam_mulai','ASC');
        } else {
            return parent::getEloquentQuery()->orderBy('tanggal', 'DESC')->orderBy('jam_mulai','ASC');
        }
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
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
                TextColumn::make('status_aktivitas_kelas')
                    ->label('Status Jadwal')
            ])
            ->filters([
                Filter::make('tanggal')
                    ->form([
                        DatePicker::make('created_from'),
                        DatePicker::make('created_until')->default(now())
                    ])->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('tanggal', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('tanggal', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make()
                    ->after(function () {
                        activity()
                            ->causedBy(Auth::user())
                            ->log('Menghapus atur jadwal');
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
            BookingKelasRelationManager::class
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
            'index' => Pages\ListInstrukturJadwals::route('/'),
            // 'index' => ManageTimetablePage::route('/'),
            'create' => Pages\CreateInstrukturJadwal::route('/create'),
            'view' => ViewInstrukturJadwal::route('/{record}'),
            'edit' => Pages\EditInstrukturJadwal::route('/{record}/edit'),
        ];
    }

    protected static function shouldRegisterNavigation(): bool
    {
        if (Auth::user()->hasRole(['Instruktur', 'Super Admin'])) {
            return true;
        }

        return false;
    }
}
