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
use App\Filament\Widgets\PendatapanInstrukturOverview;
use App\Filament\Widgets\PengeluaranChart;
use App\Filament\Widgets\TotalJamInstruktur;
use App\Models\Kelas;
use Closure;
use Filament\Facades\Filament;
use Filament\Pages\Page;
use Filament\Pages\Dashboard as BasePage;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class Dashbaord extends BasePage
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static string $view = 'filament.pages.dashbaord';

    protected static function getNavigationLabel(): string
    {
        return static::$navigationLabel ?? static::$title ?? __('filament::pages/dashboard.title');
    }

    public function render(): View
    {
        return view(static::$view, $this->getViewData())
            ->layout(static::$layout, $this->getLayoutData());
    }

    public static function getRoutes(): Closure
    {
        return function () {
            Route::get('/', static::class)->name(static::getSlug());
        };
    }

    protected function getWidgets(): array
    {
        // return Filament::getWidgets();
        if (Auth::user()->hasRole(['Super Admin'])) {
            $listWidget = [
                PendatapanInstrukturOverview::class,
                // TotalJamInstruktur::class,
                JadwalInstrukturAssign::class,
                ListTimetable::class,
                ListAturJadwal::class,
                CustomerOrderExpired::class,
                OmsetTable::class,
                OmsetChart::class,
                PengeluaranChart::class,
                BelumBayarChart::class
            ];
        } elseif (Auth::user()->hasRole('Admin')) {
            $listWidget = [
                // TotalJamInstruktur::class,
                JadwalInstrukturAssign::class,
                ListTimetable::class,
                ListAturJadwal::class,
                CustomerOrderExpired::class,
            ];
        } elseif (Auth::user()->hasRole('Instruktur')) {
            $listWidget = [
                PendatapanInstrukturOverview::class,
                OmsetTable::class,
                PendapatanInstruktur::class
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
