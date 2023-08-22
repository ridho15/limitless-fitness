<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\InstrukturOmsetResource;
use App\Models\BookingKelas;
use App\Models\Kelas;
use App\Models\KelasActivity;
use Closure;
use Filament\Forms\Components\DatePicker;
use Filament\Tables;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class OmsetTable extends BaseWidget
{
    public $tanggal_awal = null;
    public $tanggal_akhir = null;

    public function mount()
    {
    }
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
            TextColumn::make('jumlah_pertemuan')
                ->alignCenter()
                ->formatStateUsing(function ($record) {
                    $listKelasActivity = KelasActivity::with(['kelas', 'bookingKelas' => function ($query) {
                        $query->where(function ($query) {
                            $query->where('status_kehadiran', '1')
                                ->orWhere('status_kehadiran', '0');
                        });
                    }])->where(function ($query) {
                        if (Auth::user()->hasRole('Instruktur')) {
                            $query->where('id_user', Auth::id());
                        }
                    })
                        ->where('id_kelas', $record->id)
                        ->where(function ($query) {
                            if ($this->tanggal_awal != null && $this->tanggal_akhir != null) {
                                $query->whereDate('tanggal', '>=', $this->tanggal_awal)
                                    ->whereDate('tanggal', '<=', $this->tanggal_akhir);
                            }
                        })->get();

                    $jumlahPertemuan = 0;
                    foreach ($listKelasActivity as $item) {
                        $jumlahPeserta = 0;
                        foreach ($item->bookingKelas as $bookingKelas) {
                            if ($bookingKelas->status_kehadiran == '1' || $bookingKelas->status_kehadiran == '0') {
                                $jumlahPeserta++;
                            }
                        }

                        if ($item->kelas->min_anggota <= $jumlahPeserta) {
                            $jumlahPertemuan++;
                        }
                    }

                    return $jumlahPertemuan;
                }),
            TextColumn::make('omset')
                ->formatStateUsing(function ($record) {
                    $listKelasActivity = KelasActivity::with(['kelas', 'bookingKelas' => function ($query) {
                        $query->where(function ($query) {
                            $query->where('status_kehadiran', '1')
                                ->orWhere('status_kehadiran', '0');
                        });
                    }])->where(function ($query) {
                        if (Auth::user()->hasRole('Instruktur')) {
                            $query->where('id_user', Auth::id());
                        }
                    })
                        ->where('id_kelas', $record->id)
                        ->where(function ($query) {
                            if ($this->tanggal_awal != null && $this->tanggal_akhir != null) {
                                $query->whereDate('tanggal', '>=', $this->tanggal_awal)
                                    ->whereDate('tanggal', '<=', $this->tanggal_akhir);
                            }
                        })->get();

                    $omset = 0;
                    foreach ($listKelasActivity as $item) {
                        $jumlahPeserta = 0;
                        foreach ($item->bookingKelas as $bookingKelas) {
                            if ($bookingKelas->status_kehadiran == '1' || $bookingKelas->status_kehadiran == '0') {
                                $jumlahPeserta++;
                            }
                        }

                        if ($item->kelas->min_anggota <= $jumlahPeserta) {
                          $omset += $item->kelas->fee;
                        }
                    }

                    return 'Rp.' . number_format($omset, 0, ',', '.');
                })
                ->label('Omset')
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
                })
        ];
    }

    protected function getTableActions(): array
    {
        return [
            ViewAction::make()
                ->url(function ($record) {
                    return InstrukturOmsetResource::getUrl('view', ['record' => $record]);
                }),
        ];
    }

    // public function getColumnSpan(): int|string|array
    // {
    //     return 2;
    // }
}
