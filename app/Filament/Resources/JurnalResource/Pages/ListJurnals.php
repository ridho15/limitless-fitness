<?php

namespace App\Filament\Resources\JurnalResource\Pages;

use App\Filament\Resources\JurnalResource;
use App\Filament\Widgets\InstrukturWidgetOverview;
use App\Filament\Widgets\JurnalStatsOverview;
use App\Filament\Widgets\PendapatanOverView;
use Filament\Pages\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\Position;
use Illuminate\Database\Eloquent\Builder;

class ListJurnals extends ListRecords
{
    protected $listeners = ['filterUpdate' => 'updateTableFilters'];
    protected static string $resource = JurnalResource::class;

    protected function getActions(): array
    {
        return [
            CreateAction::make()
                ->label('Manual Jurnal')
                ->icon('heroicon-o-plus-circle'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            JurnalStatsOverview::class
        ];
    }

    protected function getTableActionsPosition(): ?string
    {
        return Position::BeforeCells;
    }

    public function updatedTableFilters(): void
    {
        $this->emit('updateFilterFromTable', $this->tableFilters['tanggal']['tanggal_from'], $this->tableFilters['tanggal']['tanggal_to'], $this->tableFilters['tanggal']['id_location']);
    }
}
