<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\InstrukturResource;
use App\Models\KelasActivity;
use App\Models\Location;
use App\Models\User;
use Carbon\Carbon;
use Closure;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Tables;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Queue\Console\RetryCommand;
use Illuminate\Support\Facades\Auth;

class JadwalInstrukturAssign extends BaseWidget
{
    public $tanggal_awal = null;
    public $tanggal_akhir = null;
    public $id_location = null;
    protected static ?string $heading = "Slot Instruktur";

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('name')
                ->label('Instruktur')
                ->searchable(),
            TextColumn::make('assing_time')
                ->label('Assign Time')
                ->formatStateUsing(function ($record) {
                    $jumlahAssing = KelasActivity::where('id_user', $record->id)
                        ->when($this->id_location, function (Builder $query) {
                            $query->whereHas('locationStudio', function (Builder $query) {
                                $query->where('id_location', $this->id_location);
                            });
                        })
                        ->whereNotNull('id_kelas')
                        ->when($this->tanggal_awal != null && $this->tanggal_akhir != null, function (Builder $query, $data) {
                            return $query->whereDate('tanggal', '<=', $this->tanggal_akhir)
                                ->whereDate('tanggal', '>=', $this->tanggal_awal);
                        })->when($this->tanggal_awal == null && $this->tanggal_akhir == null, function (Builder $query, $data) {
                            return $query->whereNotNull('tanggal');
                        })
                        ->whereNotNull('jam_mulai')->count();
                    return $jumlahAssing;
                }),
            TextColumn::make('jumlah_jadwal')
                ->label('Number of Schedules')
                ->formatStateUsing(function ($record) {
                    $kelasActivity = KelasActivity::where('id_user', $record->id)
                        ->when($this->tanggal_awal != null && $this->tanggal_akhir != null, function (Builder $query, $data) {
                            return $query->whereDate('tanggal', '>=', $this->tanggal_awal)
                                ->whereDate('tanggal', '<=', $this->tanggal_akhir);
                        })->when($this->tanggal_awal == null && $this->tanggal_akhir == null, function (Builder $query, $data) {
                            return $query->whereNotNull('tanggal');
                        })
                        ->when($this->id_location, function (Builder $query, $data) {
                            $query->whereHas('locationStudio', function (Builder $query) {
                                $query->where('id_location', $this->id_location);
                            });
                        })
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
                ->url(function ($record) {
                    return InstrukturResource::getUrl('view', ['record' => $record]);
                })
        ];
    }

    protected function getTableQuery(): Builder
    {
        return User::whereHas('roles', function ($query) {
            $query->where('name', 'Instruktur');
        })->orderBy('created_at', 'DESC');
    }


    protected function getTableFilters(): array
    {
        return [
            Filter::make('tanggal')
                ->form([
                    DatePicker::make('created_from')->default(Carbon::now()->startOfMonth()),
                    DatePicker::make('created_until')->default(Carbon::now()->endOfMonth())
                ])->indicateUsing(function ($data) {
                    if ($data['created_from'] != null) {
                        $this->tanggal_awal = $data['created_from'];
                    } else {
                        $this->tanggal_awal = null;
                    }

                    if ($data['created_until'] != null) {
                        $this->tanggal_akhir = $data['created_until'];
                    } else {
                        $this->tanggal_akhir = null;
                    }
                }),
            Filter::make('lokasi')
                ->form([
                    Select::make('id_location')
                        ->searchable()
                        ->label('Lokasi')
                        ->options(Location::select('nama', 'id')->get()->pluck('nama', 'id'))
                ])->indicateUsing(function ($data) {
                    $this->id_location = $data['id_location'];
                })
        ];
    }
}
