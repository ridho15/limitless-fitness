<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\InstrukturResource;
use App\Models\KelasActivity;
use App\Models\User;
use Carbon\Carbon;
use Closure;
use Filament\Forms\Components\DatePicker;
use Filament\Tables;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class JadwalInstrukturAssign extends BaseWidget
{
    public $tanggal_awal = null;
    public $tanggal_akhir = null;
    public $id_location;
    protected static ?string $heading = "Slot Instruktur";

    protected function getTableQuery(): Builder
    {
        return User::whereHas('roles', function ($query) {
            $query->where('name', 'Instruktur');
        })->orderBy('created_at', 'DESC');
    }

    public function mount(){
        $this->id_location = Auth::user()->id_location;
    }

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
                    ->whereHas('locationStudio', function($query){
                        $query->where('id_location', $this->id_location);
                    })
                        ->where('id_kelas', '!=', null)
                        ->where(function ($query) {
                            if ($this->tanggal_awal != null && $this->tanggal_akhir != null) {
                                $query->where('tanggal', '<=', $this->tanggal_akhir)
                                    ->where('tanggal', '>=', $this->tanggal_awal);
                            } else {
                                $query->where('tanggal', '!=', null);
                            }
                        })
                        ->where('jam_mulai', '!=', null)->count();

                    return $jumlahAssing;
                }),
            TextColumn::make('jumlah_jadwal')
                ->label('Jumlah Jadwal')
                ->formatStateUsing(function ($record) {
                    $kelasActivity = KelasActivity::where('id_user', $record->id)
                        ->where(function ($query) {
                            if ($this->tanggal_awal != null && $this->tanggal_akhir != null) {
                                $query->where('tanggal', '<=', $this->tanggal_akhir)
                                    ->where('tanggal', '>=', $this->tanggal_awal);
                            } else {
                                $query->where('tanggal', '!=', null);
                            }
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
                })
        ];
    }
}
