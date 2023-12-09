<?php

namespace App\Filament\Resources\InstrukturResource\RelationManagers;

use App\Filament\Resources\AktifitasKelasInstrukturResource;
use App\Models\Kelas;
use App\Models\LocationStudio;
use App\Models\User;
use Carbon\Carbon;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class KelasActivityRelationManager extends RelationManager
{
    protected static string $relationship = 'KelasActivity';

    protected static ?string $recordTitleAttribute = 'id_user';

    public static function form(Form $form): Form
    {
        return $form
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
                        $instruktur = User::whereHas('kelasActivity', function ($query) use ($jam, $get) {
                            $query->where('tanggal', date('Y-m-d', strtotime($get('tanggal'))))
                                ->where('id_kelas', null)
                                ->where('id_location_studio', null)
                                ->whereJamMulai($jam);
                        })->get()->pluck('name', 'id');

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
                TextColumn::make('nama_latihan')
                    ->toggleable()
                    ->searchable(),
                TextColumn::make('kelas.nama_kelas')
                    ->label('Kelas')
                    ->toggleable()
                    ->searchable(),
                TextColumn::make('locationStudio.nama')
                    ->label('Studio')
                    ->toggleable()
                    ->searchable(),
                TextColumn::make('level_formatted')
                    ->label('Level')
                    ->toggleable(),
                TextColumn::make('status_aktivitas_kelas')
                    ->label('Status Jadwal'),
                TextColumn::make('jumlah_peserta')
                    ->label('Jumlah Peserta')
                    ->formatStateUsing(function ($record) {
                        $record->jumlah_peserta = $record->bookingKelas->whereIn('status_kehadiran', [0, 1])->count();
                        return $record->jumlah_peserta;
                    })->alignCenter(),
                TextColumn::make('pendapatan')
                    ->label('Pendapatan')
                    ->toggleable()
                    ->formatStateUsing(function ($record) {
                        $pendapatan = 0;
                        if ($record->jumlah_peserta >= 2 && $record->status == 3) {
                            $pendapatan = $record->kelas->fee;
                        }
                        return 'Rp.' . number_format($pendapatan, 0, ',', '.');
                    }),
                TextColumn::make('rating')
                    ->label('Rating')
                    ->formatStateUsing(function ($record) {
                        $jumlahReview = 0;
                        $jumlahRating = 0;
                        foreach ($record->bookingKelas->where('rating', '!=', null) as $bookingKelas) {
                            $jumlahRating += $bookingKelas->rating;
                        }

                        $jumlahReview = $record->bookingKelas->where('rating', '!=', null)->count();

                        if ($jumlahRating == 0 || $jumlahRating == 0) {
                            $total = 0;
                        } else {
                            $total = ($jumlahRating / $jumlahReview) * 2;
                        }

                        return number_format($total, 2);
                    })
                    ->sortable(),
            ])
            ->filters([
                Filter::make('tanggal')
                    ->form([
                        DatePicker::make('dari_tanggal')
                            ->label('Dari Tanggal'),
                        DatePicker::make('sampai_tanggal')
                            ->label('Sampai Tanggal')
                    ])->query(function (Builder $query, $data) {
                        return $query->when($data['dari_tanggal'], function (Builder $query, $date) {
                            return $query->whereDate('tanggal', '>=', $date);
                        })->when($data['sampai_tanggal'], function (Builder $query, $date) {
                            return $query->whereDate('tanggal', '<=', $date);
                        });
                    })
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Buat Jadwal')
                    ->icon('heroicon-o-plus-circle'),
            ])
            ->actions([
                Action::make('view')
                    ->label('View')
                    ->icon('heroicon-o-eye')
                    ->color('secondary')
                    ->url(function ($record) {
                        return AktifitasKelasInstrukturResource::getUrl('view', ['record' => $record]);
                    }),
                EditAction::make()
                    ->hidden(function () {
                        if (Auth::user()->hasRole('Super Admin')) {
                            return false;
                        }

                        return true;
                    }),
                DeleteAction::make()
                    ->hidden(function () {
                        if (Auth::user()->hasRole('Super Admin')) {
                            return false;
                        }

                        return true;
                    })
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }
}
