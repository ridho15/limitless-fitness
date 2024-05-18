<div class="container mt-3 mb-5">
    <div class="text-center">
        <div class="mt-3" wire:loading>
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>
    @if (count($listData) > 0)
    @foreach ($listData as $index => $item)
    <div class="mb-5" id="{{ $index }}">
        <div class="">
            <span class="fw-semibold fs-5 me-2 d-block d-md-inline">{{
                \Carbon\Carbon::parse($item['tanggal'])->locale('id')->isoFormat('dddd, DD MMMM YYYY') }}</span>
            <small class="fw-light text-secondary">{{ $item['data']->count() }} Classes</small>
        </div>

        @if (count($item['data']) > 0)
        @foreach ($item['data'] as $data)
        <div class="row align-items-center mt-3">
            <div class="col-md-1 col-12 d-flex d-md-block justify-content-between text-center">
                <div class="fw-bold text-center">Jam {{ date('H:i', strtotime($data->jam_mulai)) }} </div>
                <small class="fw-light text-secondary">{{ $data->kelas->duration }} mins</small>
            </div>
            <div class="col-md-11">
                <div class="shadow bg-white py-md-3 px-md-5 py-2 px-3" style="border-radius: 15px">
                    <div class="row justify-content-center align-items-center">
                        <div class="col-md-3">
                            <span class="fw-light d-block">{{ $data->kelas->nama_kelas }}</span>
                            <span class="fw-light">{{ $data->nama_latihan }}</span>
                        </div>
                        <div class="col-md-3 d-flex d-md-block justify-content-between">
                            <span class="fw-bold d-md-block d-inline text-md-center">{{ $data->locationStudio->location->nama }}</span>
                            <small class="fw-bold d-md-block d-inline text-md-center">{{ $data->locationStudio->nama }}</small>
                        </div>
                        <div class="col-md-3 text-center d-flex d-md-block justify-content-between mt-2 mt-md-0">
                            <span class="text-secondary fw-bold">{{ $data->status_aktivitas_kelas }}</span>
                            <span class="fw-bold d-block">{{ $data->user->name }}</span>
                        </div>
                        <div class="col-md-3 col-12 text-end mt-2 mt-md-0">
                            <form action="{{ route('detail-timetable', ['id' => $data->id]) }}" method="GET">
                                <button type="submit" class="btn custom-btn w-100" @if ($data->status != 1) disabled @endif
                                    style="padding-left: 50px; padding-right: 50px">
                                    @if ($data->status != 1)
                                        Waitlist
                                    @else
                                        Join Class
                                    @endif
                                </button>
                            </form>
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
    @else
    <div class="d-flex align-items-center justify-content-center text-center" style="height: 50vh">
        <div class="text-center">
            <img src="{{ asset('assets/images/illustration-1.png') }}" class="img-fluid" alt=""
                style="height: 500px; object-fit: contain">
            <p class="fw-bold" style="color: #3c6553">Silahkan Pilih Kelas</p>
        </div>
    </div>
    @endif
</div>