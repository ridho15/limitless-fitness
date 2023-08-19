<?php

namespace App\Filament\Resources\CustomerResource\Pages;

use App\Filament\Resources\BookingKelasResource\Pages\ListBookingKelas;
use App\Filament\Resources\CustomerResource;
use App\Models\BookingKelas;
use App\Models\Kelas;
use App\Models\OrderPaket;
use App\Models\Paket;
use App\Models\User;
use Filament\Resources\Pages\Page;
use HtmlSanitizer\Extension\Table\NodeVisitor\ThNodeVisitor;
use Illuminate\Contracts\View\View as ViewView;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class KreditCustomer extends Page
{
    protected static string $resource = CustomerResource::class;

    protected static string $view = 'filament.resources.customer-resource.pages.kredit-customer';

    public $listeners = [
        'updateSession',
        'clickEditSession'
    ];

    public $listKelas = [];
    public $id_user;
    public $user;
    public $id_kelas;
    public $jumlahSession = 0;
    public $kelas;
    public $jumlahDigunakanTerbanyak;

    public function mount($record)
    {
        $this->id_user = $record;
        $this->user = User::find($this->id_user);
        $this->getData();
    }

    public function render(): View
    {
        $this->getData();
        return view(static::$view, $this->getViewData())
            ->layout(static::$layout, $this->getLayoutData());
    }

    public function getData()
    {
        $this->listKelas = Kelas::with(['paket.orderPaket' => function ($query) {
            $query->where('id_user', $this->id_user)
                ->whereDate('expired_date', '>=', now())
                ->where('status', 1)
                ->where('is_active', 1)
                ->where('sisa', '>', 0);
        }])
            ->whereHas('paket', function ($query) {
                $query->whereHas('orderPaket', function ($query) {
                    $query->where('id_user', $this->id_user)
                        ->where('expired_date', '>=', now())
                        ->where('sisa', '>', 0)
                        ->where('status', 1)
                        ->where('is_active', 1);
                });
            })->get();

        foreach ($this->listKelas as $index => $item) {
            $jumlahKredit = 0;
            $sisa = 0;
            foreach ($item->paket as $paket) {
                foreach ($paket->orderPaket as $orderPaket) {
                    if ($orderPaket->is_active == 1 && $orderPaket->id_user == $this->id_user && $orderPaket->expired_date >= now()) {
                        $jumlahKredit += $orderPaket->jumlah_pertemuan;
                        $sisa += $orderPaket->sisa;
                    }
                }
            }

            $listBookingKelas = BookingKelas::with('kelasActivity')
                ->where('id_user', $this->id_user)
                ->whereHas('kelasActivity', function ($query) use ($item) {
                    $query->where('id_kelas', $item->id);
                })
                ->where(function ($query) {
                    $query->where('status_kehadiran', '1')
                        ->orWhere('status_kehadiran', '0');
                })->get();

            if ($this->jumlahDigunakanTerbanyak <= count($listBookingKelas)) {
                $this->jumlahDigunakanTerbanyak = count($listBookingKelas);
            }

            $this->listKelas[$index]->jumlah_kredit = $jumlahKredit;
            $this->listKelas[$index]->jumlah_digunakan = count($listBookingKelas);
            $this->listKelas[$index]->sisa = $sisa;
            $this->listKelas[$index]->listBookingKelas = $listBookingKelas ?? [];
        }
    }

    public function updateSession()
    {
        if (Auth::user()->hasRole('Super Admin')) {
            $kelas = Kelas::find($this->id_kelas);

            $jumlahKredit = 0;
            foreach ($kelas->paket as $paket) {
                foreach ($paket->orderPaket as $orderPaket) {
                    if ($orderPaket->is_active == 1 && $orderPaket->id_user == $this->id_user && $orderPaket->expired_date >= now()) {
                        $jumlahKredit += $orderPaket->jumlah_pertemuan;
                    }
                }
            }

            $penambahan = $this->jumlahSession - $jumlahKredit;

            $orderPaket = OrderPaket::where('id_user', $this->id_user)
                ->whereHas('paket', function ($query) {
                    $query->where('id_kelas', $this->id_kelas);
                })->where('is_active', 1)
                ->where('expired_date', '>=', now())
                ->orderBy('created_at', 'DESC')
                ->first();

            if ($orderPaket) {
                $orderPaket->update([
                    'sisa' => $orderPaket->sisa + $penambahan
                ]);
            }

            $this->jumlahSession = 0;
            $this->id_kelas = 0;

            activity()
                ->causedBy(Auth::user())
                ->log('Melakukan update data sesi pada customer');

            $this->getData();
        } else {
            return session()->flash('fail', 'Anda tidak dizinkan !');
        }
    }

    public function clickEditSession($id_kelas)
    {
        $this->id_kelas = $id_kelas;
        $this->kelas = Kelas::find($this->id_kelas);
        $orderPaket = OrderPaket::whereHas('paket', function ($query) {
            $query->whereHas('kelas', function ($query) {
                $query->where('id_kelas', $this->id_kelas);
            });
        })->where('id_user', $this->id_user)
            ->where('is_active', 1)
            ->where('expired_date', '>=', now())
            ->get();

        $this->jumlahSession = 0;
        foreach ($orderPaket as $item) {
            $this->jumlahSession += $item->jumlah_pertemuan;
        }

        $this->getData();
    }
}
