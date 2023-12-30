<?php

namespace App\Filament\Resources\InstruktruOmsetResource\Pages;

use App\Filament\Resources\InstruktruOmsetResource;
use App\Filament\Resources\InstrukturOmsetResource;
use App\Filament\Resources\TimetableResource;
use App\Models\BookingKelas;
use App\Models\KelasActivity;
use Filament\Pages\Actions\CreateAction;
use Filament\Resources\Pages\Page;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;

class ViewListDetailOmset extends Page
{
    protected static string $resource = InstrukturOmsetResource::class;

    protected static ?string $title = 'View Detail Omset';

    protected static string $view = 'filament.resources.instruktru-omset-resource.pages.view-list-detail-omset';

    public $listBookingKelas = [];

    public $kelasActivity;

    public function render(): View
    {
        return view(static::$view, $this->getViewData())
            ->layout(static::$layout, $this->getLayoutData());
    }

    public function mount($record)
    {
        $this->kelasActivity = KelasActivity::find($record);
        if (!$this->kelasActivity) {
            return session()->flash('fail', 'Data Kelas activity tidak ditemukan !');
        }

        $this->listBookingKelas = BookingKelas::where('id_kelas_activity', $record)
            ->where(function ($query) {
                $query->where('status_kehadiran', '1')
                    ->orWhereNull('status_kehadiran');
            })->get();
    }

    protected function getActions(): array
    {
        return [];
    }

    protected function getBreadcrumbs(): array
    {
        return [
            'Omset Instruktur', 'List Detail'
        ];
    }
}
