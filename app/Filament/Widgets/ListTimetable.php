<?php

namespace App\Filament\Widgets;

use App\Models\KelasActivity;
use Carbon\Carbon;
use Closure;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class ListTimetable extends BaseWidget
{
    protected static ?int $sort = 2;

    protected function getTableQuery(): Builder
    {
        return KelasActivity::where('id_kelas', '!=', null)
            ->where('id_location_studio', '!=', null)
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
            TextColumn::make('level_formatted')
                ->label('Level'),
            TextColumn::make('status_aktivitas_kelas')
                ->label('Status Jadwal')
        ];
    }
}
