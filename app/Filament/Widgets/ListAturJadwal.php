<?php

namespace App\Filament\Widgets;

use App\Models\KelasActivity;
use Carbon\Carbon;
use Closure;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ListAturJadwal extends BaseWidget
{
    protected static ?int $sort = 2;

    public $id_location;

    public function mount(){
        $this->id_location = Auth::user()->id_location;
    }

    protected function getTableQuery(): Builder
    {
        return KelasActivity::where('id_kelas', null)
            ->whereHas('locationStudio', function ($query) {
                $query->where('id_location', $this->id_location);
            })
            ->where('tanggal', '!=', null)
            ->where('jam_mulai', '!=', null);
    }

    protected function getTableColumns(): array
    {
        return [
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
            TextColumn::make('status_aktivitas_kelas')
                ->label('Status Jadwal')
        ];
    }
}
