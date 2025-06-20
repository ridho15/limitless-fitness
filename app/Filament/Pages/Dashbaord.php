<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\BelumBayarChart;
use App\Filament\Widgets\CustomerOrderExpired;
use App\Filament\Widgets\JadwalInstrukturAssign;
use App\Filament\Widgets\ListAturJadwal;
use App\Filament\Widgets\ListTimetable;
use App\Filament\Widgets\OmsetChart;
use App\Filament\Widgets\OmsetTable;
use App\Filament\Widgets\PendapatanInstruktur;
use App\Filament\Widgets\PendapatanInstrukturOverview;
use App\Filament\Widgets\PendapatanOverView;
use App\Filament\Widgets\PendapatanPerLocationOverview;
use App\Filament\Widgets\PengeluaranChart;
use App\Models\Location;
use Closure;
use Filament\Facades\Filament;
use Filament\Pages\Dashboard as BasePage;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class Dashbaord extends BasePage
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static string $view = 'filament.pages.dashbaord';

    public $id_location;
    public $listLocation = [];

    protected static function getNavigationLabel(): string
    {
        return static::$navigationLabel ?? static::$title ?? __('filament::pages/dashboard.title');
    }

    public function render(): View
    {
        return view(static::$view, $this->getViewData())
            ->layout(static::$layout, $this->getLayoutData());
    }

    public function mount()
    {
        $this->listLocation = Location::get();
    }

    public static function getRoutes(): Closure
    {
        return function () {
            Route::get('/', static::class)->name(static::getSlug());
        };
    }

    protected function getWidgets(): array
    {
        if (Auth::user()->hasRole(['Super Admin'])) {
            $listWidget = [
                PendapatanOverView::class,
                // JadwalInstrukturAssign::class,
                CustomerOrderExpired::class,
                OmsetChart::class,
                PengeluaranChart::class,
                BelumBayarChart::class,
                OmsetTable::class,
            ];
        } elseif (Auth::user()->hasRole('Admin')) {
            $listWidget = [
                JadwalInstrukturAssign::class,
                ListTimetable::class,
                ListAturJadwal::class,
                CustomerOrderExpired::class,
            ];
        } elseif (Auth::user()->hasRole('Instruktur')) {
            $listWidget = [
                // PendapatanInstrukturOverview::class,
                PendapatanInstruktur::class
            ];
        } elseif(Auth::user()->hasRole(['Owner Cabang'])){
            $listWidget = [
                JadwalInstrukturAssign::class,
                ListTimetable::class,
                ListAturJadwal::class
            ];
        } elseif(Auth::user()->hasRole('Finance')){
            $listWidget = [
                PendapatanOverView::class,
                OmsetChart::class,
                PengeluaranChart::class,
                BelumBayarChart::class,
                OmsetTable::class
            ];
        } else {
            $listWidget = [];
        }

        return $listWidget;
    }

    protected function getColumns(): int | string | array
    {
        return 2;
    }

    protected function getTitle(): string
    {
        return static::$title ?? __('filament::pages/dashboard.title');
    }
}
