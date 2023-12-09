<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AktifitasKelasInstrukturResource\Pages;
use App\Filament\Resources\AktifitasKelasInstrukturResource\Pages\ViewAktifitasKelasInstruktur;
use App\Filament\Resources\AktifitasKelasInstrukturResource\RelationManagers;
use App\Filament\Resources\AktifitaskelasIntrukturResource\RelationManagers\BookingKelasRelationManager;
use App\Models\AktifitasKelasInstruktur;
use App\Models\KelasActivity;
use App\Models\Location;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class AktifitasKelasInstrukturResource extends Resource
{
    protected static ?string $model = KelasActivity::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';

    protected static ?string $pluralModelLabel = 'Review Your Class';

    protected static ?string $slug = 'review-your-class';

    protected static ?string $title = 'Review Your Class';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        Select::make('id_kelas')
                            ->preload()
                            ->searchable()
                            ->relationship('kelas', 'nama_kelas'),
                        TextInput::make('nama_latihan')
                            ->label('Nama Latihan'),
                        TextInput::make('tanggal')
                            ->label('Tanggal'),
                        TextInput::make('jam_mulai')
                            ->label('Jam Mulai'),
                        Select::make('id_location')
                            ->label('Lokasi')
                            ->searchable()
                            ->preload()
                            ->options(function () {
                                $listLocation = Location::select(['nama', 'id'])->get()->pluck('nama', 'id');
                                return $listLocation;
                            }),
                        Select::make('id_location_studio')
                            ->label('Studio')
                            ->relationship('locationStudio', 'nama'),
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Instruktur')
                    ->toggleable()
                    ->searchable()
                    ->hidden(function () {
                        if (Auth::user()->hasRole(["Instruktur"])) {
                            return true;
                        } else {
                            return false;
                        }
                    }),
                TextColumn::make('kelas.nama_kelas')
                    ->searchable()
                    ->label('Kelas'),
                TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->formatStateUsing(function ($state) {
                        return Carbon::parse($state)->locale('id')->isoFormat('dddd, DD MMMM YYYY');
                    }),
                TextColumn::make('jam_mulai')
                    ->label('Jam Mulai'),
                TextColumn::make('jumlah_peserta')
                    ->label('Jumlah Peserta')
                    ->alignCenter()
                    ->formatStateUsing(function ($record) {
                        $jumlahPeserta = 0;
                        foreach ($record->bookingKelas as $item) {
                            if ($item->status_kehadiran == '1' || $item->status_kehadiran == '0') {
                                $jumlahPeserta++;
                            }
                        }
                        return $jumlahPeserta;
                    }),
                TextColumn::make('rating')
                    ->label('Rating')
                    ->searchable()
                    ->toggleable()
                    ->formatStateUsing(function ($state) {
                        return number_format($state, 2);
                    })
                    ->sortable(),

            ])
            ->filters([
                Filter::make('tanggal')
                    ->form([
                        DatePicker::make('start_date')
                            ->label('Dari Tanggal'),
                        DatePicker::make('end_date')
                            ->label('Sampai Tanggal')
                    ])->query(function (Builder $query, $data) {
                        return $query->when($data['start_date'], function (Builder $query, $date) {
                            return $query->whereDate('tanggal', '>=', $date);
                        })->when($data['end_date'], function (Builder $query, $date) {
                            return $query->whereDate('tanggal', '<=', $date);
                        });
                    }),
                SelectFilter::make('id_location')
                    ->searchable()
                    ->label('Lokasi')
                    ->options(Location::select('nama', 'id')->get()->pluck('nama', 'id'))
            ])
            ->actions([
                ViewAction::make()
            ])
            ->bulkActions([]);
    }

    public static function getRelations(): array
    {
        return [
            BookingKelasRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAktifitasKelasInstrukturs::route('/'),
            'create' => Pages\CreateAktifitasKelasInstruktur::route('/create'),
            'view' => ViewAktifitasKelasInstruktur::route('{record}'),
            'edit' => Pages\EditAktifitasKelasInstruktur::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->when(Auth::user()->hasRole(['Instruktur']), function ($query) {
            return $query->where('id_user', Auth::id());
        })->whereNotNull('id_location_studio')
            ->whereDate('tanggal', '<=', now())
            ->orderBy('tanggal', 'DESC')
            ->orderBy('jam_mulai', 'DESC');
    }
}
