<?php

namespace App\Filament\Resources\CustomerResource\Pages;

use App\Filament\Resources\BookingKelasResource\Pages\ListBookingKelas;
use App\Filament\Resources\CustomerResource;
use App\Models\BookingKelas;
use App\Models\Kelas;
use App\Models\OrderPaket;
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
        $this->listKelas = Kelas::whereHas('paket', function ($query) {
            $query->whereHas('orderPaket', function ($query) {
                $query->where('id_user', $this->id_user)
                    ->where('expired_date', '>=', now())
                    ->where('is_active', 1);
            });
        })->get();

        foreach ($this->listKelas as $index => $item) {
            $jumlahKredit = 0;
            foreach ($item->paket as $paket) {
                foreach ($paket->orderPaket as $orderPaket) {
                    if ($orderPaket->is_active == 1 && $orderPaket->id_user == $this->id_user && $orderPaket->expired_date >= now()) {
                        $jumlahKredit += $orderPaket->jumlah_pertemuan;
                    }
                }
            }

            $listBookingKelas = BookingKelas::with('kelasActivity')
                ->where('id_user', $this->id_user)
                ->whereHas('kelasActivity', function ($query) use ($item) {
                    $query->where('id_kelas', $item->id);
                })
                ->whereStatusKehadiran([
                    '1', '0'
                ])->get();

            if ($this->jumlahDigunakanTerbanyak <= count($listBookingKelas)) {
                $this->jumlahDigunakanTerbanyak = count($listBookingKelas);
            }

            $sisa = $jumlahKredit - count($listBookingKelas);

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
                    'jumlah_pertemuan' => $orderPaket->jumlah_pertemuan + $penambahan
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
