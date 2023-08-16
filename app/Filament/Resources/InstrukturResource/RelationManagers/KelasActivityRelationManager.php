<?php

namespace App\Filament\Resources\InstrukturResource\RelationManagers;

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
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
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
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Buat Jadwal')
                    ->icon('heroicon-o-plus-circle'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
