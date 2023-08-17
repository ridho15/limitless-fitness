<x-filament::page>
    <div class="rounded overflow-hidden shadow-lg">

        <div class="px-6 pt-4 pb-2" style="padding-top: 2.5rem; padding-bottom: 2.5rem; background-color: white">
            <div class="mb-5">
                <div class="flex flex-row mb-2">
                    <div class="basis-1/4 mx-2">Tanggal</div>
                    <div class="basis-1/2 font-bold mx-2">: {{ $kelasActivity->tanggal }}</div>
                </div>
                <div class="flex flex-row mb-2">
                    <div class="basis-1/4 mx-2">Jam Mulai</div>
                    <div class="basis-1/2 font-bold mx-2">: {{ $kelasActivity->jam_mulai }}</div>
                </div>
            </div>
            <table class="table-auto w-full" style="border-radius: 10px; overflow-x: scroll">
                <thead>
                    <tr class="border px-4 py-2">
                        <th class="border px-4 py-2">No</th>
                        <th class="border px-4 py-2">Customer</th>
                        <th class="border px-4 py-2">Status Kehadiran</th>
                        <th class="border px-4 py-2">Keterangan</th>
                        <th class="border px-4 py-2">Omset</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $total = 0;
                    @endphp
                    @if (count($listBookingKelas) > 0)
                        @foreach ($listBookingKelas as $index => $item)
                            <tr class="bg-current">
                                <td class="border px-4 py-2 text-center bg-white">{{ $index + 1 }}</td>
                                <td class="border px-4 py-2 text-center bg-white">{{ $item->user->name }}</td>
                                <td class="border px-4 py-2 text-center bg-white">
                                    {{ $item->status_kehadiran_formatted }}</td>
                                <td class="border px-4 py-2 text-center bg-white">{{ $item->keterangan ?? '-' }}</td>
                                <td class="border px-4 py-2 text-center bg-white">
                                    Rp.{{ number_format($item->kelasActivity->kelas->honor, 0, ',', '.') }}</td>
                            </tr>
                            @php
                                $total += $item->kelasActivity->kelas->honor;
                            @endphp
                        @endforeach
                    @else
                        <tr class="bg-current">
                            <td colspan="5" class="border px-4 py-2 text-center bg-white">
                                Tidak ada data
                            </td>
                        </tr>
                    @endif
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" class="border px-4 py-2 text-center bg-white font-bold">Total</td>
                        <td class="border px-4 py-2 text-center bg-white font-bold">
                            Rp.{{ number_format($total, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</x-filament::page>
