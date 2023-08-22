<x-filament::page>
    <div class="rounded overflow-hidden shadow-lg">
        <div class="px-6 pt-4 pb-2"
            style="padding-top: 2.5rem; padding-bottom: 2.5rem; background-color: white; overflow: auto !important; max-height: 1200px;">
            <div class="grid grid-cols-2 gap-4" style="margin-bottom: 20px">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="start_date">
                        Start Date
                    </label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        wire:model="startDate" id="start_date" type="date" placeholder="Tanggal">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="end_date">
                        End Date
                    </label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        wire:model='endDate' id="end_date" type="date" placeholder="Tanggal">
                </div>
            </div>
            <div id="table-scroll" class="table-scroll">
                <div class="table-wrap">
                    <table class="table-auto w-full main-table" style="border-radius: 10px">
                        <thead>
                            <tr>
                                <th class="border px-4 py-2 fixed-side"
                                    @if (count($listTanggal) > 0) rowspan="4" @endif>Date
                                    Time</th>
                                @foreach ($listTanggal as $index => $item)
                                    <th class="border px-4 py-2" colspan="{{ count($listStudio) }}">
                                        {{ \Carbon\Carbon::parse($item->tanggal)->locale('id')->isoFormat('dddd') }}
                                    </th>
                                @endforeach
                            </tr>
                            @if (count($listTanggal) == 0)
                                <tr>
                                    <th class="border px-4 py-2">Tidak ada aktifitas</th>
                                </tr>
                            @endif
                            <tr>
                                @foreach ($listTanggal as $item)
                                    <th class="border px-4 py-2" colspan="{{ count($listStudio) }}">
                                        {{ \Carbon\Carbon::parse($item->tanggal)->locale('id')->isoFormat('DD MMMM YYYY') }}
                                    </th>
                                @endforeach
                            </tr>
                            <tr>
                                @foreach ($listTanggal as $tanggal)
                                    @foreach ($listStudio as $item)
                                        <th class="border px-4 py-2">{{ $item->nama }}</th>
                                    @endforeach
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($listJam as $jam)
                                <tr class="bg-current">
                                    <td class="border px-4 py-2 text-center bg-white fixed-side"
                                        style="font-weight: bold">
                                        {{ \Carbon\Carbon::parse($jam->jam_mulai)->locale('id')->isoFormat('HH:mm') }}
                                    </td>
                                    @foreach ($listTanggal as $tanggal)
                                        @foreach ($listStudio as $studio)
                                            @php
                                                $listKelasActivity = \App\Models\KelasActivity::whereTime('jam_mulai', $jam->jam_mulai)
                                                    ->where('id_location_studio', $studio->id)
                                                    ->whereDate('tanggal', $tanggal->tanggal)
                                                    ->get();
                                            @endphp
                                            <td class="border bg-white" style="vertical-align: top !important">
                                                @if (count($listKelasActivity) > 0)
                                                    @foreach ($listKelasActivity as $kelasActivity)
                                                        <div class="border px-4 py-2 text-center"
                                                            style="background-color: #22543d; color: white">
                                                            <a
                                                                href="{{ app\Filament\Resources\TimetableResource::getUrl('view', ['record' => $kelasActivity]) }}">
                                                                {{ $kelasActivity->user->name }}</a>
                                                        </div>
                                                        @foreach ($kelasActivity->bookingKelas as $bookingKelas)
                                                            <div class="border px-4 py-2 text-center">
                                                                <a href="{{ app\Filament\Resources\BookingKelasResource::getUrl('edit', ['record' => $bookingKelas]) }}"
                                                                    class="text-dark">{{ $bookingKelas->user->name }}</a>
                                                            </div>
                                                        @endforeach
                                                        @for ($i = 1; $i <= 5 - count($kelasActivity->bookingKelas); $i++)
                                                            <div class="border px-4 py-2 text-center text-white">-</div>
                                                        @endfor
                                                    @endforeach
                                                @else
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        <div class="border px-4 py-2 text-center text-white">-</div>
                                                    @endfor
                                                @endif
                                            </td>
                                        @endforeach
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script>
            jQuery(document).ready(function() {
                jQuery(".main-table").clone(true).appendTo('#table-scroll').addClass('clone');
            });
        </script>
    </div>
</x-filament::page>
