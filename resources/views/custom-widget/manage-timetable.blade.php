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
                        @php
                            $jumlahRuangan = 0;
                            foreach ($listStudio as $studio) {
                                $jumlahRuangan += count($studio->ruangan);
                            }
                        @endphp
                        <th class="border px-4 py-2" colspan="{{ $jumlahRuangan }}">
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
                        @php
                            $jumlahRuangan = 0;
                            foreach ($listStudio as $studio) {
                                $jumlahRuangan += count($studio->ruangan);
                            }
                        @endphp
                        <th class="border px-4 py-2" colspan="{{ $jumlahRuangan }}">
                            {{ \Carbon\Carbon::parse($item->tanggal)->locale('id')->isoFormat('DD MMMM YYYY') }}</th>
                    @endforeach
                </tr>
                <tr>
                    @foreach ($listStudio as $item)
                        <th class="border px-4 py-2" colspan="{{ count($item->ruangan) }}">{{ $item->nama }}</th>
                    @endforeach
                </tr>
                <tr>
                    @foreach ($listStudio as $item)
                        @foreach ($item->ruangan as $ruangan)
                            <th class="border px-4 py-2">{{ $ruangan->nama_ruangan }}</th>
                        @endforeach
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($listJam as $jam)
                    <tr class="bg-current">
                        <td class="border px-4 py-2 text-center" rowspan="{{ $pesertaTerbanyak }}"
                            style="font-weight: bold">
                            {{ \Carbon\Carbon::parse($jam->jam_mulai)->locale('id')->isoFormat('HH:mm') }}
                        </td>
                        @foreach ($listTanggal as $tanggal)
                            @foreach ($listStudio as $studio)
                                @foreach ($studio->ruangan as $ruangan)
                                    @php
                                        $listKelasActivity = \App\Models\KelasActivity::whereTime('jam_mulai', $jam->jam_mulai)
                                            ->where('id_ruangan', $ruangan->id)
                                            ->whereDate('tanggal', $tanggal->tanggal)
                                            ->get();
                                    @endphp
                                    <td class="border">
                                        @foreach ($listKelasActivity as $kelasActivity)
                                            <div class="border px-4 py-2 text-center"
                                                style="background-color: #22543d; color: white">
                                                {{ $kelasActivity->user->name }}</div>
                                            @foreach ($kelasActivity->bookingKelas as $bookingKelas)
                                                <div class="border px-4 py-2 text-center">
                                                    {{ $bookingKelas->user->name }}</div>
                                            @endforeach
                                        @endforeach
                                    </td>
                                @endforeach
                            @endforeach
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
