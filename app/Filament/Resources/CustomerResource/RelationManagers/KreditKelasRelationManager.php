<?php

namespace App\Filament\Resources\CustomerResource\RelationManagers;

use App\Models\BookingKelas;
use App\Models\Kelas;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\DB;

class KreditKelasRelationManager extends RelationManager
{
    protected static string $relationship = 'OrderPaket';

    protected static ?string $recordTitleAttribute = 'id_user';

    protected static ?string $modelLabel = 'Total Kredit';

    protected static ?string $pluralModelLabel = 'Total Kredit';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_kelas')
                    ->label('Kelas'),
                TextColumn::make('jumlah_pertemuan')
                    ->label('Total Session'),
                TextColumn::make('jumlah_digunakan')
                    ->label('Session Digunakan'),
                TextColumn::make('sisa')
                    ->label('Sisa Session'),
            ])
            ->filters([
                //
            ])
            ->headerActions([])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([]);
    }

    protected function getRelatedModel(): string
    {
        $kelas = Kelas::whereHas('paket', function ($query) {
            $query->whereHas('orderPaket', function ($query) {
                $query->where('id_user', $this->getOwnerRecord()->id);
            });
        })->get();
        foreach ($kelas as $item) {
            $jumlahKredit = 0;
            foreach ($item->paket as $paket) {
                foreach ($paket->orderPaket as $orderPaket) {
                    if ($orderPaket->is_active == 1) {
                        $jumlahKredit += $orderPaket->jumlah_pertemuan;
                    }
                }
            }

            $jumlahDigunakan = BookingKelas::where('id_user', $this->ownerRecord->id)
                ->whereHas('kelasActivity', function ($query) use ($item) {
                    $query->where('id_kelas', $item->id);
                })
                ->whereStatusKehadiran(['1', '0'])
                ->count();

            $sisa = $jumlahKredit - $jumlahDigunakan;

            $item->jumlah_pertemuan = $jumlahKredit;
            $item->nama_kelas = $item->nama_kelas;
            $item->sisa = $sisa;
            $item->jumlah_digunakan = $jumlahDigunakan;
        }
        return $kelas;
    }
}
