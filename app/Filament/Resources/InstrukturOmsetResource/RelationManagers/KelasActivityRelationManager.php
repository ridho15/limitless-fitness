<?php

namespace App\Filament\Resources\InstrukturOmsetResource\RelationManagers;

use App\Filament\Resources\InstrukturOmsetResource;
use App\Filament\Resources\TimetableResource;
use App\Models\BookingKelas;
use App\Models\KelasActivity;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class KelasActivityRelationManager extends RelationManager
{
    protected static string $relationship = 'KelasActivity';

    protected static ?string $recordTitleAttribute = 'id_kelas';

    protected static ?string $title = 'History Kelas';

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
                    ->label('Nama Kelas')
                    ->searchable(),
                TextColumn::make('user.name')
                    ->label('User'),
                TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->sortable(),
                TextColumn::make('jam_mulai')
                    ->label('Jam Mulai')
                    ->sortable(),
                TextColumn::make('jumlah_peserta')
                    ->label('Jumlah Peserta')
                    ->alignCenter()
                    ->formatStateUsing(function ($record) {
                        $jumlahPeserta = BookingKelas::where('id_kelas_activity', $record->id)
                            ->where(function ($query) {
                                $query->where('status_kehadiran', '0')
                                    ->orWhere('status_kehadiran', '1');
                            })->count();

                        return $jumlahPeserta;
                    }),
                TextColumn::make('omset')
                    ->label('Omset')
                    ->formatStateUsing(function ($record) {
                        $listBookingKelas = BookingKelas::where('id_kelas_activity', $record->id)
                            ->where(function ($query) {
                                $query->where('status_kehadiran', '0')
                                    ->orWhere('status_kehadiran', '1');
                            })->get();
                        $omset = 0;
                        foreach ($listBookingKelas as $item) {
                            $omset += $record->kelas->honor;
                        }
                        return 'Rp.' . number_format($omset, 0, ',', '.');
                    })
            ])
            ->filters([
                //
            ])
            ->headerActions([])
            ->actions([
                ViewAction::make()
                    ->url(function ($record) {
                        return InstrukturOmsetResource::getUrl('view-detail-omset', ['record' => $record]);
                    })
            ])
            ->bulkActions([]);
    }

    protected function getTableQuery(): Builder|Relation
    {
        return parent::getTableQuery()->where(function ($query) {
            if (Auth::user()->hasRole('Instruktur')) {
                $query->where('id_user', Auth::id());
            }
        });
    }
}
