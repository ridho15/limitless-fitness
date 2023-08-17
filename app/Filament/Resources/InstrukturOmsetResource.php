<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InstruktruOmsetResource\Pages\ViewListDetailOmset;
use App\Filament\Resources\InstrukturOmsetResource\Pages;
use App\Filament\Resources\InstrukturOmsetResource\Pages\ViewInstrukturOmset;
use App\Filament\Resources\InstrukturOmsetResource\RelationManagers;
use App\Filament\Resources\InstrukturOmsetResource\RelationManagers\KelasActivityRelationManager;
use App\Filament\Widgets\PendatapanInstrukturOverview;
use App\Models\BookingKelas;
use App\Models\InstrukturOmset;
use App\Models\Kelas;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class InstrukturOmsetResource extends Resource
{
    protected static ?string $model = Kelas::class;

    protected static ?string $navigationIcon = 'heroicon-o-cash';

    protected static ?string $slug = 'instruktur_omset';

    protected static ?string $pluralModelLabel = 'Instruktur Omset';

    protected static ?string $title = 'Instruktur Omset';

    protected static ?string $tanggal_awal = null;

    protected static ?string $tanggal_akhir = null;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        Fieldset::make('View Omset')
                            ->schema([
                                TextInput::make('nama_kelas')
                                    ->label('Nama Kelas')
                                    ->disabled(),
                                TextInput::make('jumlah_pertemuan')
                                    ->label('Jumlah Pertemuan')
                                    ->formatStateUsing(function ($record) {
                                        $listBookingKelas = BookingKelas::whereHas('kelasActivity', function ($query) use ($record) {
                                            $query->where('id_kelas', $record->id)
                                                ->where('id_user', Auth::id())
                                                ->whereDate('tanggal', '<=', now())->where(function ($query) {
                                                    if (static::$tanggal_awal != null && static::$tanggal_akhir != null) {
                                                        $query->whereDate('tanggal', '>=', static::$tanggal_awal)
                                                            ->whereDate('tanggal', '<=', static::$tanggal_akhir);
                                                    }
                                                });
                                        })->count();

                                        return $listBookingKelas;
                                    }),
                                TextInput::make('omset')
                                    ->label('Omset')
                                    ->formatStateUsing(function ($record) {
                                        $listBookingKelas = BookingKelas::whereHas('kelasActivity', function ($query) use ($record) {
                                            $query->where('id_kelas', $record->id)
                                                ->where('id_user', Auth::id())
                                                ->whereDate('tanggal', '<=', now())
                                                ->where(function ($query) {
                                                    if (static::$tanggal_awal != null && static::$tanggal_akhir != null) {
                                                        $query->whereDate('tanggal', '>=', static::$tanggal_awal)
                                                            ->whereDate('tanggal', '<=', static::$tanggal_akhir);
                                                    }
                                                });
                                        })->get();

                                        $omset = 0;
                                        foreach ($listBookingKelas as $item) {
                                            $omset += $item->kelasActivity->kelas->honor;
                                        }

                                        return 'Rp.' . number_format($omset, 0, ',', '.');
                                    })
                            ])
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_kelas')
                    ->label("Kelas")
                    ->searchable(),
                TextColumn::make('jumlah_pertemuan')
                    ->alignCenter()
                    ->formatStateUsing(function ($record) {
                        $listBookingKelas = BookingKelas::whereHas('kelasActivity', function ($query) use ($record) {
                            $query->where('id_kelas', $record->id)
                                ->where(function ($query) {
                                    if (Auth::user()->hasRole('Instruktur')) {
                                        $query->where('id_user', Auth::id());
                                    }
                                })
                                ->whereDate('tanggal', '<=', now())
                                ->where(function ($query) {
                                    if (static::$tanggal_awal != null && static::$tanggal_akhir != null) {
                                        $query->whereDate('tanggal', '>=', static::$tanggal_awal)
                                            ->whereDate('tanggal', '<=', static::$tanggal_akhir);
                                    }
                                });
                        })->count();

                        return $listBookingKelas;
                    }),
                TextColumn::make('omset')
                    ->formatStateUsing(function ($record) {
                        $listBookingKelas = BookingKelas::whereHas('kelasActivity', function ($query) use ($record) {
                            $query->where('id_kelas', $record->id)
                                ->where(function ($query) {
                                    if (Auth::user()->hasRole('Instruktur')) {
                                        $query->where('id_user', Auth::id());
                                    }
                                })
                                ->whereDate('tanggal', '<=', now())
                                ->where(function ($query) {
                                    if (static::$tanggal_awal != null && static::$tanggal_akhir != null) {
                                        $query->whereDate('tanggal', '>=', static::$tanggal_awal)
                                            ->whereDate('tanggal', '<=', static::$tanggal_akhir);
                                    }
                                });
                        })->get();

                        $omset = 0;
                        foreach ($listBookingKelas as $item) {
                            $omset += $item->kelasActivity->kelas->honor;
                        }

                        return 'Rp.' . number_format($omset, 0, ',', '.');
                    })
                    ->label('Omset')
            ])
            ->filters([
                Filter::make('tanggal')
                    ->form([
                        DatePicker::make('created_from'),
                        DatePicker::make('created_until')->default(now())
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
                    })
            ])
            ->actions([
                ViewAction::make()
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            KelasActivityRelationManager::class
        ];
    }

    public static function getWidgets(): array
    {
        return [
            PendatapanInstrukturOverview::class,
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
}
