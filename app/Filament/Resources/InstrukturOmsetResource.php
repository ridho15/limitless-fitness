<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InstruktruOmsetResource\Pages\ViewListDetailOmset;
use App\Filament\Resources\InstrukturOmsetResource\Pages;
use App\Filament\Resources\InstrukturOmsetResource\Pages\ViewInstrukturOmset;
use App\Filament\Resources\InstrukturOmsetResource\RelationManagers\BookingKelasRelationManager;
use App\Filament\Widgets\PendapatanInstrukturOverview;
use App\Models\KelasActivity;
use App\Models\Location;
use App\Models\User;
use Carbon\Carbon;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Livewire\Livewire;

class InstrukturOmsetResource extends Resource
{
    protected static ?string $model = KelasActivity::class;

    protected static ?string $navigationIcon = 'heroicon-o-cash';

    protected static ?string $slug = 'instruktur_report';

    protected static ?string $pluralModelLabel = 'Instruktur Report';

    protected static ?string $title = 'Instruktur Report';

    protected static ?string $navigationGroup = 'Account';

    public static ?string $tanggal_awal;

    public static ?string $tanggal_akhir;

    protected static int $omset = 0;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kelas.nama_kelas')
                    ->searchable()
                    ->toggleable()
                    ->label('Kelas'),
                TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->formatStateUsing(function ($state) {
                        return Carbon::parse($state)->locale('id')->isoFormat('dddd, DD MMMM YYYY');
                    }),
                TextColumn::make('jam_mulai')
                    ->label('Jam Mulai'),
                TextColumn::make('locationStudio.location.nama')
                    ->label('Lokasi')
                    ->toggleable()
                    ->searchable(),
                TextColumn::make('locationStudio.nama')
                    ->toggleable()
                    ->label('Studio')
                    ->searchable(),
                TextColumn::make('fee')
                    ->label('Fee')
                    ->formatStateUsing(function ($record) {
                        $fee = 0;
                        if ($record->kelas->min_anggota <= $record->booking_kelas_count) {
                            $fee = $record->kelas->fee;
                            static::$omset += $fee;
                        }
                        return 'Rp.' . number_format($fee, 0, ',', '.');
                    }),
                TextColumn::make('Penghasilan')
                    ->label('Penghasilan')
                    ->formatStateUsing(function ($record) {
                        return 'Rp.' . number_format(static::$omset, 0, ',', '.');
                    })
            ])
            ->filters([
                Filter::make('tanggal')
                    ->form([
                        DatePicker::make('created_from')
                            ->default(Carbon::now()->startOfMonth()),
                        DatePicker::make('created_until')->default(Carbon::now()->endOfMonth())
                    ])->query(function (Builder $query, $data) {
                        if ($data['created_from'] != null && $data['created_until'] != null) {
                            static::$tanggal_awal = $data['created_from'];
                            static::$tanggal_akhir = $data['created_until'];
                        }
                        return $query->when($data['created_from'], function (Builder $query, $date) {
                            return $query->whereDate('tanggal', '>=', $date);
                        })->when($data['created_until'], function (Builder $query, $date) {
                            return $query->whereDate('tanggal', '<=', $date);
                        });
                    }),
                SelectFilter::make('id_location')
                    ->label('Lokasi')
                    ->searchable()
                    ->options(Location::select(['nama', 'id'])->get()->pluck('nama', 'id'))
                    ->query(function (Builder $query, $data) {
                        return $query->when($data['value'], function (Builder $query, $id_location) {
                            return $query->whereHas('locationStudio', function (Builder $query) use ($id_location) {
                                return $query->where('id_location', $id_location);
                            });
                        });
                    }),
                SelectFilter::make('id_user')
                    ->hidden(function () {
                        if (Auth::user()->hasRole(['Instruktur'])) {
                            return true;
                        }
                        return false;
                    })
                    ->options(User::whereHas('roles', function ($query) {
                        $query->where('name', 'Instruktur');
                    })->select(['name', 'id'])->get()->pluck('name', 'id'))
                    ->label('Instruktur')
                    ->searchable(),
            ])
            ->actions([
                ViewAction::make()
            ])
            ->bulkActions([
                DeleteBulkAction::make()
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
        // return [];
        $pendapatanInstruktur = PendapatanInstrukturOverview::class;
        return [
            $pendapatanInstruktur,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInstrukturOmsets::route('/'),
            'view' => ViewInstrukturOmset::route('/{record}'),
            'view-detail-omset' => ViewListDetailOmset::route('/detail-omset/{record}')
        ];
    }

    protected static function shouldRegisterNavigation(): bool
    {
        if (Auth::user()->hasRole(['Super Admin', 'Instruktur'])) {
            return true;
        }

        return false;
    }

    public static function getEloquentQuery(): Builder
    {
        // return parent::getEloquentQuery();
        return parent::getEloquentQuery()->when(Auth::user()->hasRole(['Instruktur']), function ($query) {
            $query->where('id_user', Auth::id());
        })->with('kelas')->withCount(['bookingKelas' => function (Builder $query) {
            $query->where('status_kehadiran', 1)
                ->orWhereNull('status_kehadiran');
        }])
            ->having('booking_kelas_count', ">", 0)
            ->whereDate('tanggal', '<=', now())
            ->orderBy('id', 'DESC');
    }
}
