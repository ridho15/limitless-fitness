<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\InstrukturResource;
use App\Models\KelasActivity;
use App\Models\User;
use Closure;
use Filament\Tables;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class JadwalInstrukturAssign extends BaseWidget
{
    protected function getTableQuery(): Builder
    {
        return User::whereHas('roles', function ($query) {
            $query->where('name', 'Instruktur');
        })->orderBy('created_at', 'DESC');
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('name')
                ->label('Instruktur')
                ->searchable(),
            TextColumn::make('assing_time')
            ->label('Assign Time')
            ->formatStateUsing(function($record){
                $jumlahAssing = KelasActivity::where('id_user', $record->id)
                    ->where('id_location_studio', '!=', null)
                    ->where('id_kelas', '!=', null)
                    ->where('tanggal', '!=', null)
                    ->where('jam_mulai', '!=', null)->count();

                    return $jumlahAssing;
            }),
            TextColumn::make('jumlah_jadwal')
            ->label('Jumlah Jadwal')
            ->formatStateUsing(function($record){
                $kelasActivity = KelasActivity::where('id_user', $record->id)
                ->count();

                return $kelasActivity;
            })
        ];
    }

    protected function getTableActions(): array
    {
        return [
            ViewAction::make()
            ->label('Lihat')
            ->icon('heroicon-o-eye')
            ->url(function($record){
                return InstrukturResource::getUrl('view', ['record' => $record]);
            })
        ];
    }
}
