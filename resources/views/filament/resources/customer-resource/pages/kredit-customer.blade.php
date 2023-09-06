<x-filament::page>
    <div class="rounded overflow-hidden shadow-lg bg-white p-5">
        <div class="px-6 pt-4 pb-2"
            style="padding-top: 2.5rem; padding-bottom: 2.5rem; background-color: white; overflow: auto">
            <div class="mb-10" style="margin-bottom: 20px">
                <div>Customer : <strong>{{ $user->name }}</strong></div>
                <div>Email : <strong>{{ $user->email }}</strong></div>
            </div>
            <table class="table-auto w-full" style="border-radius: 10px;">
                <thead>
                    <tr>
                        <th class="border px-4 py-2">Kelas</th>
                        <th class="border px-4 py-2">Total Session</th>
                        <th class="border px-4 py-2">Digunakan</th>
                        <th class="border px-4 py-2">Sisa Paket</th>
                        <th class="border px-4 py-2" colspan="{{ $jumlahDigunakanTerbanyak }}">Tanggal</th>
                        <th class="border px-4 py-2">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($listKelas) > 0)
                        @foreach ($listKelas as $item)
                            <tr class="bg-current">
                                <td class="border px-4 py-2 bg-white" style="font-weight: bold">{{ $item->nama_kelas }}
                                </td>
                                <td class="border px-5 py-2 bg-white text-end">{{ $item->jumlah_kredit }}</td>
                                <td class="border px-4 py-2 bg-white text-end">{{ $item->jumlah_digunakan }}</td>
                                <td class="border px-4 py-2 bg-white text-end">{{ $item->sisa }}</td>
                                @if ($jumlahDigunakanTerbanyak > 0)
                                    @for ($i = 0; $i < $jumlahDigunakanTerbanyak; $i++)
                                        <td class="border px-4 bg-white py-2 text-end">
                                            @php
                                                if (array_key_exists($i, $item->listBookingKelas->toArray())) {
                                                    echo date('d-m-Y', strtotime($item->listBookingKelas[$i]->kelasActivity->tanggal)) . ' ' . date('H:i', strtotime($item->listBookingKelas[$i]->kelasActivity->jam_mulai));
                                                }
                                            @endphp
                                        </td>
                                    @endfor
                                @else
                                    <td class="border px-4 bg-white py-2 text-end"></td>
                                @endif
                                @if (Auth::user()->hasRole(['Super Admin', 'Admin']))
                                    <td class="border px-4 py-2 bg-white text-end"><button type="button"
                                            wire:click="clickEditSession({{ $item->id }})"
                                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full"
                                            style="background-color: rgb(0, 183, 255)">
                                            Edit Session
                                        </button></td>
                                @else
                                    <td class="border px-4 py-2 bg-white text-end">
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6" class="border px-4 py-2 text-center">Tidak ada kelas</td>
                        </tr>
                    @endif
                </tbody>
            </table>
            <div class="text-center">
                <div class="mt-3" wire:loading>
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
            @if (count($listOrderPaket) > 0)
                <div class="text-start mt-10">
                    <button type="button" wire:click='closeListOrderPaket' class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full"
                    style="background-color: rgb(255, 0, 0)">Close</button>
                </div>
            @endif
            @foreach ($listOrderPaket as $index => $item)
                <form action="#" wire:submit.prevent="updateSession({{ $item->id }}, {{ $index }})" method="POST">
                    <div class="grid grid-cols-4 gap-4 mt-5"
                        style="margin-bottom: 20px; margin-top: 20px; align-items: center">
                        <div class="mb-4">
                            Kelas : <strong>{{ $item->paket->kelas->nama_kelas }}</strong>
                        </div>
                        <div class="mb-4">
                            Tanggal Order : <strong>{{ date('d-m-Y H:i', strtotime($item->created_at)) }}</strong>
                        </div>
                        <div class="mb-4">
                            <input
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                wire:model="jumlahSession.{{ $index }}" id="jumlahSession" type="numeric"
                                placeholder="Session">
                        </div>
                        <div class="mb-4">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full"
                                style="background-color: rgb(0, 183, 255)">Simpan</button>
                        </div>
                    </div>
                </form>
            @endforeach
        </div>
    </div>
</x-filament::page>
