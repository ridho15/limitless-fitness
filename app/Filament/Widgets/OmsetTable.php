<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\InstrukturOmsetResource;
use App\Models\Kelas;
use App\Models\Location;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class OmsetTable extends BaseWidget
{
    public $tanggal_awal = null;
    public $tanggal_akhir = null;
    public $id_location = null;

    protected static ?string $heading = "Omset Per Kelas";

    protected function getTableQuery(): Builder
    {
        return Kelas::orderBy('id', 'ASC');
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('nama_kelas')
                ->label("Kelas")
                ->searchable(),
        ];
    }

    protected function getTableFilters(): array
    {
        return [
            Filter::make('tanggal')
                ->form([
                    DatePicker::make('created_from'),
                    DatePicker::make('created_until')->default(now())
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
                        ->label('Lokasi')
                        ->searchable()
                        ->options(Location::select(['nama', 'id'])->get()->pluck('nama', 'id'))
                ])->indicateUsing(function ($data) {
                    $this->id_location = $data['id_location'];
                })
        ];
    }

    protected function getTableActions(): array
    {
        return [
        ];
    }
}
