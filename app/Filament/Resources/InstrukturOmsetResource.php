<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InstruktruOmsetResource\Pages\ViewListDetailOmset;
use App\Filament\Resources\InstrukturOmsetResource\Pages;
use App\Filament\Resources\InstrukturOmsetResource\Pages\ViewInstrukturOmset;
use App\Filament\Resources\InstrukturOmsetResource\RelationManagers\KelasActivityRelationManager;
use App\Filament\Widgets\PendapatanInstrukturOverview;
use App\Models\Kelas;
use App\Models\KelasActivity;
use App\Models\Location;
use Carbon\Carbon;
use Closure;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class InstrukturOmsetResource extends Resource
{
    protected static ?string $model = KelasActivity::class;

    protected static ?string $navigationIcon = 'heroicon-o-cash';

    protected static ?string $slug = 'instruktur_report';

    protected static ?string $pluralModelLabel = 'Instruktur Report';

    protected static ?string $title = 'Instruktur Report';

    protected static ?string $tanggal_awal = null;

    protected static ?string $tanggal_akhir = null;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        Fieldset::make('View Omset')
                            ->schema([])
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('omset')
                    ->formatStateUsing(function ($record) {
                        return "Rp." . number_format($record->omset, 0, ',', '.');
                    })
                    ->label('Omset')
            ])
            ->filters([
                Filter::make('tanggal')
                    ->form([
                        DatePicker::make('created_from')
                            ->default(Carbon::now()->startOfMonth()->isoFormat('YYYY-MM-DD')),
                        DatePicker::make('created_until')->default(Carbon::now()->endOfMonth()->isoFormat('YYYY-MM-DD'))
                    ])->indicateUsing(function ($data) {
                        if ($data['created_from'] != null) {
                            static::$tanggal_awal = $data['created_from'];
                        } else {
                            static::$tanggal_awal = null;
                        }

                        if ($data['created_until'] != null) {
                            static::$tanggal_akhir = $data['created_until'];
                        } else {
                            static::$tanggal_akhir = null;
                        }
                    }),
                Filter::make('lokasi')
                    ->form([
                        Select::make('id_location')
                            ->label('Lokasi')
                            ->searchable()
                            ->options(Location::select(['nama', 'id'])->get()->pluck('nama', 'id'))
                    ])->query(function ($query, $data) {
                        $query->when($data['id_location'], function ($query, $id_location) {
                        });
                    })
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
            KelasActivityRelationManager::class
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
        return parent::getEloquentQuery()->when(Auth::user()->hasRole(['Instruktur']), function ($query) {
            $query->where('id_user', Auth::id());
        })->withCount('bookingKelas', function ($query) {
            $query->whereIn('status_kehadiran', [0, 1]);
        })->having('booking_kelas_count', '>=', 2)
            ->orderBy('tanggal', 'ASC');
    }
}
