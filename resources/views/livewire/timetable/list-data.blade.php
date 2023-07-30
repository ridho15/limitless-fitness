<div class="container mt-3 mb-5">
    @foreach ($listData as $index => $item)
        <div class="mb-5" id="{{ $index }}">
            <div class="">
                <span
                    class="fw-semibold fs-5 me-2">{{ \Carbon\Carbon::parse($item['tanggal'])->locale('id')->isoFormat('dddd, DD MMMM YYYY') }}</span>
                <small class="fw-light text-secondary">{{ $item['data']->count() }} Classes</small>
            </div>
            @if (count($item['data']) > 0)
                @foreach ($item['data'] as $data)
                    <div class="row align-items-center mt-3">
                        <div class="col-md-1 text-center">
                            <div class="fw-bold text-center">Jam {{ date('H:i', strtotime($data->jam_latihan)) }}</div>
                            <small class="fw-light text-secondary">{{ $data->kelas->duration }} mins</small>
                        </div>
                        <div class="col-md-11">
                            <div class="shadow bg-white py-3 px-5" style="border-radius: 15px">
                                <div class="row justify-content-center align-items-center">
                                    <div class="col-md-3">
                                        <span class="fw-bold d-block">{{ $data->kelas->nama_kelas }}</span>
                                        <small class="fw-light d-block">{{ $data->user->name }}</small>
                                    </div>
                                    <div class="col-md-3">
                                        <span class="fw-bold d-block">{{ $data->locationStudio->location->nama }}</span>
                                        <small class="fw-light d-block">{{ $data->locationStudio->nama }}</small>
                                    </div>
                                    <div class="col-md-3 text-center">
                                        <small
                                            class="text-secondary fw-bold">{{ $data->status_aktivitas_kelas }}</small>
                                    </div>
                                    <div class="col-md-3 text-end">
                                        <a href="{{ route('detail-timetable', ['id' => $data->id]) }}"
                                            class="btn custom-btn" @if ($data->status != 1) disabled @endif
                                            style="padding-left: 50px; padding-right: 50px">Join
                                            <br>
                                            Waitlist</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <small class="d-block text-center py-3 fw-light">Belum ada kelas</small>
            @endif
        </div>
    @endforeach
</div>
