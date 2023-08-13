<div class="rounded overflow-hidden shadow-lg">
    <div class="px-6 pt-4 pb-2" style="padding-top: 2.5rem; padding-bottom: 2.5rem; background-color: white">
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
        <table class="table-auto w-full" style="border-radius: 10px">
            <thead>
                <tr>
                    <th class="border px-4 py-2" @if (count($listTanggal) > 0) rowspan="4" @endif>Date Time</th>
                    @foreach ($listTanggal as $index => $item)
                        <th class="border px-4 py-2" colspan="{{ count($listStudio) }}">
                            {{ \Carbon\Carbon::parse($item->tanggal)->locale('id')->isoFormat('dddd') }}</th>
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
                            {{ \Carbon\Carbon::parse($item->tanggal)->locale('id')->isoFormat('DD MMMM YYYY') }}</th>
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
                        <td class="border px-4 py-2 text-center bg-white" style="font-weight: bold">
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
                                <td class="border bg-white">
                                    @foreach ($listKelasActivity as $kelasActivity)
                                        <div class="border px-4 py-2 text-center"
                                            style="background-color: #22543d; color: white">
                                            <a href="{{ app\Filament\Resources\InstrukturJadwalResource::getUrl('view', ['record' => $kelasActivity]) }}">
                                                {{ $kelasActivity->user->name }}</a></div>
                                        @foreach ($kelasActivity->bookingKelas as $bookingKelas)
                                            <div class="border px-4 py-2 text-center">
                                                <a href="{{ app\Filament\Resources\CustomerResource::getUrl('view', ['record' => $bookingKelas->user]) }}" class="text-dark">{{ $bookingKelas->user->name }}</a></div>
                                        @endforeach
                                    @endforeach
                                </td>
                            @endforeach
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
