<?php

namespace App\Filament\Widgets;

use App\Models\BookingKelas;
use App\Models\Jurnal;
use App\Models\User;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use Illuminate\Support\Facades\Auth;

class PendatapanInstrukturOverview extends BaseWidget
{
    protected function getCards(): array
    {
        $listOmset = BookingKelas::with(['kelasActivity' => function ($query) {
            $query->where(function ($query) {
                if (Auth::user()->hasRole('Instruktur')) {
                    $query->where('id_user', Auth::id());
                }
            })
                ->where('id_kelas', '!=', null)
                ->where('id_location_studio', '!=', null)
                ->whereDate('tanggal', '<=', now());
        }, 'kelasActivity.kelas'])
            ->where(function ($query) {
                $query->where('status_kehadiran', '1')
                    ->orWhere('status_kehadiran', '0');
            })->get();

        $listCard = [];

        $jurnal = Jurnal::select('jumlah_uang', 'tipe')->where('id_order_paket', '!=', null)
            ->get();

        $omset = 0;
        $pengeluaran = 0;
        foreach ($jurnal as $item) {
            if ($item->tipe == 1) {
                $omset += $item->jumlah_uang;
            } elseif ($item->tipe == '0') {
                $pengeluaran += $item->jumlah_uang;
            }
        }

        $jumlahCustomer = User::whereHas('roles', function ($query) {
            $query->where('name', 'Customer');
        })->whereMonth('created_at', now())->count();

        if (Auth::user()->hasRole('Super Admin')) {
            array_push($listCard, Card::make('Gross Profit', 'Rp.' . number_format($omset - $pengeluaran, 0, ',', '.'))
                ->description('Omset di kurangi dengan fee instruktur')
                ->color('primary'));

            array_push($listCard,  Card::make('Jumlah Customer', $jumlahCustomer)
                ->description('Jumlah Customer Dalam Bulan Ini')
                ->color('primary'));
        }


        $omset = 0;
        foreach ($listOmset as $item) {
            $omset += $item->kelasActivity->kelas->honor;
        }

        array_push($listCard, Card::make('Instruktur Payroll', 'Rp.' . number_format($omset, 0, ',', '.'))->description('Pendapatan secara keseluruhan dari kelas yang sudah lewat')
            ->color('success'));

        $listOmset = BookingKelas::with(['kelasActivity.kelas'])
            ->whereHas('kelasActivity', function ($query) {
                $query->where(function ($query) {
                    if (Auth::user()->hasRole('Instruktur')) {
                        $query->where('id_user', Auth::id());
                    }
                })
                    ->where('id_kelas', '!=', null)
                    ->where('id_location_studio', '!=', null)
                    ->whereDate('tanggal', '<=', now())
                    ->whereDate('tanggal', '>=', Carbon::now()->subMonths(1)->isoFormat('YYYY-MM-DD'));
            })->where(function ($query) {
                $query->where('status_kehadiran', '1')
                    ->orWhere('status_kehadiran', '0');
            })->get();

        $omset = 0;
        foreach ($listOmset as $item) {
            $omset += $item->kelasActivity->kelas->honor;
        }

        array_push($listCard, Card::make('Instruktur Payroll', 'Rp.' . number_format($omset, 0, ',', '.'))->description('Pendapatan dalam 1 bulan')
            ->color('success'));

        // $listOmset = BookingKelas::whereHas('kelasActivity', function ($query) {
        //     $query->where(function ($query) {
        //         if (Auth::user()->hasRole('Instruktur')) {
        //             $query->where('id_user', Auth::id());
        //         }
        //     })
        //         ->where('id_kelas', '!=', null)
        //         ->where('id_location_studio', '!=', null)
        //         ->whereDate('tanggal', '<=', now())
        //         ->whereDate('tanggal', '>=', Carbon::now()->subWeeks(1)->isoFormat('YYYY-MM-DD'));
        // })->where(function ($query) {
        //     $query->where('status_kehadiran', '1')
        //         ->orWhere('status_kehadiran', '0');
        // })->get();

        // $omset = 0;
        // foreach ($listOmset as $item) {
        //     $omset += $item->kelasActivity->kelas->honor;
        // }

        // array_push($listCard, Card::make('Instruktur Payroll', 'Rp.' . number_format($omset, 0, ',', '.'))->description('Pendapatan dalam 1 minggu')
        //     ->color('success'));

        // $listOmset = BookingKelas::whereHas('kelasActivity', function ($query) {
        //     $query->where(function ($query) {
        //         if (Auth::user()->hasRole('Instruktur')) {
        //             $query->where('id_user', Auth::id());
        //         }
        //     })
        //         ->where('id_kelas', '!=', null)
        //         ->where('id_location_studio', '!=', null)
        //         ->whereDate('tanggal', now());
        // })->where(function ($query) {
        //     $query->where('status_kehadiran', '1')
        //         ->orWhere('status_kehadiran', '0');
        // })->get();

        // $omset = 0;
        // foreach ($listOmset as $item) {
        //     $omset += $item->kelasActivity->kelas->honor;
        // }

        // array_push($listCard, Card::make('Instruktur Payroll', 'Rp.' . number_format($omset, 0, ',', '.'))->description('Pendapatan hari ini')
        //     ->color('success'));
        return $listCard;
    }
}
