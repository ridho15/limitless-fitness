<?php

namespace App\Filament\Resources\OmsetResource\Pages;

use App\Filament\Resources\OmsetResource;
use App\Filament\Widgets\PendapatanOverView;
use App\Models\OrderPaket;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\View\View;

class ListOmsets extends ListRecords
{
    protected static string $resource = OmsetResource::class;

    protected function getActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            PendapatanOverView::class
        ];
    }

    // protected function getTableContentFooter(): ?View
    // {
    //     $omset = OrderPaket::whereStatus(1)
    //         ->when($this->getResource()::$tanggal_pembayaran_from, function ($query) {
    //             $query->where('tanggal_pembayaran', '>=', $this->getResource()::$tanggal_pembayaran_from);
    //         })->when($this->getResource()::$tanggal_pembayaran_to, function ($query) {
    //             $query->where('tanggal_pembayaran', '<=', $this->getResource()::$tanggal_pembayaran_to);
    //         })
    //         ->select(['harga'])->sum('harga');
    //     $data = [
    //         'calc_columns' => [
    //             [
    //                 'column' => "user.name",
    //                 'value' => $this->getResource()::$tanggal_pembayaran_from
    //             ],
    //             [
    //                 'column' => 'harga',
    //                 'value' => 'Rp.' . number_format($omset)
    //             ],
    //         ],
    //         'hasCheckboxColumn' => false
    //     ];
    //     return view('filament.table-footer.omset', $data);
    // }
}
