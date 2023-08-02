@extends('base-2.layout')

@section('content')
    <section class="my-4">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    @livewire('profile.left-bar', ['active' => $active])
                </div>
                <div class="col-md py-4">
                    <h4 class="fw-bold">My Booking</h4>
                    <ul class="nav nav-underline d-none d-sm-flex">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active custom-nav-link px-3" role="tab" data-bs-toggle="tab"
                                href="#belum_ada_status">Belum ada status</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link px-3 custom-nav-link" role="tab" data-bs-toggle="tab"
                                href="#hadir">Hadir</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link px-3 custom-nav-link" role="tab" data-bs-toggle="tab"
                                href="#izin">Izin</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link px-3 custom-nav-link" role="tab" data-bs-toggle="tab"
                                href="#tidak_hadir">Tidak Hadir</a>
                        </li>
                    </ul>
                    <hr>
                    @if (session()->has('info'))
                        <div class="alert alert-info" role="alert">
                            {{ session('info') }}
                        </div>
                    @endif
                    <div class="tab-content">
                        <div class="tab-pane fade show active py-4" id="belum_ada_status">
                            @if (count($listBookingKelas->where('status_kehadiran', null)) > 0)
                                @foreach ($listBookingKelas->where('status_kehadiran', null) as $item)
                                    <div class="bg-white shadow-sm border p-4 mb-4" style="border-radius: 15px">
                                        <div class="row justify-content-between">
                                            <div class="col-md-3 text-start">
                                                <small class="fw-light">Kelas</small>
                                                <div class="fw-bold">{{ $item->kelasActivity->kelas->nama_kelas }}</div>
                                                <small>{{ $item->kelasActivity->user->name }}</small>
                                            </div>
                                            <div class="col-md-3 text-center">
                                                <small class="fw-light">Booking</small>
                                                <div class="fw-bold">{{ $item->tanggal_booking }}</div>
                                                <small>Keterangan : {{ $item->keterangan ?? '-' }}</small>
                                            </div>
                                            <div class="col-md-3 text-center">
                                                <small class="fw-light">Waktu Latihan</small>
                                                <div class="fw-bold">{{ $item->kelasActivity->jam_latihan_formatted }}</div>
                                                <small>Kehadiran : {{ $item->status_kehadiran_formatted }}</small>
                                            </div>
                                            <div class="col-md-3 text-end">
                                                <small class="fw-light">Lokasi</small>
                                                <div class="fw-bold">
                                                    {{ $item->kelasActivity->locationStudio->location->nama }}</div>
                                                <small>{{ $item->kelasActivity->locationStudio->nama }}</small>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="d-flex align-items-center flex-column justify-content-center"
                                    style="height: 50vh">
                                    <span class="fs-1 text-center">
                                        <i class="fa-solid fa-file-pen"></i>
                                    </span>
                                    <small class="d-block mb-3">
                                        You don't have any class.
                                    </small>
                                    <a href="{{ route('timetable') }}" class="btn custom-btn-outline">Booking Class</a>
                                </div>
                            @endif
                        </div>
                        <div class="tab-pane fade py-4" id="hadir">
                            @if (count($listBookingKelas->where('status_kehadiran', 1)) > 0)
                                @foreach ($listBookingKelas->where('status_kehadiran', 1) as $item)
                                    <div class="bg-white shadow-sm border p-4 mb-4" style="border-radius: 15px">
                                        <div class="row align-items-center justify-content-between">
                                            <div class="col-md-3 text-start">
                                                <div class="fw-bold">Nama Kelas</div>
                                                <small>Nama Instruktur</small>
                                            </div>
                                            <div class="col-md-3 text-center">
                                                <div class="fw-bold">Tanggal Booking</div>
                                                <small>Keterangan</small>
                                            </div>
                                            <div class="col-md-3 text-center">
                                                <div class="fw-bold">Jam Latihan</div>
                                                <small>status kehadiran</small>
                                            </div>
                                            <div class="col-md-3 text-end">
                                                <div class="fw-bold">Lokasi</div>
                                                <small>Studio</small>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="d-flex align-items-center flex-column justify-content-center"
                                    style="height: 50vh">
                                    <span class="fs-1 text-center">
                                        <i class="fa-solid fa-file-pen"></i>
                                    </span>
                                    <small class="d-block mb-3">
                                        You don't have any class.
                                    </small>
                                    <a href="{{ route('timetable') }}" class="btn custom-btn-outline">Booking Class</a>
                                </div>
                            @endif
                        </div>
                        <div class="tab-pane fade py-4" id="izin">
                            @if (count($listBookingKelas->where('status_kehadiran', 2)) > 0)
                                @foreach ($listBookingKelas->where('status_kehadiran', 2) as $item)
                                    <div class="bg-white shadow-sm border p-4 mb-4" style="border-radius: 15px">
                                        <div class="row align-items-center justify-content-between">
                                            <div class="col-md-3 text-start">
                                                <div class="fw-bold">Nama Kelas</div>
                                                <small>Nama Instruktur</small>
                                            </div>
                                            <div class="col-md-3 text-center">
                                                <div class="fw-bold">Tanggal Booking</div>
                                                <small>Keterangan</small>
                                            </div>
                                            <div class="col-md-3 text-center">
                                                <div class="fw-bold">Jam Latihan</div>
                                                <small>status kehadiran</small>
                                            </div>
                                            <div class="col-md-3 text-end">
                                                <div class="fw-bold">Lokasi</div>
                                                <small>Studio</small>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="d-flex align-items-center flex-column justify-content-center"
                                    style="height: 50vh">
                                    <span class="fs-1 text-center">
                                        <i class="fa-solid fa-file-pen"></i>
                                    </span>
                                    <small class="d-block mb-3">
                                        You don't have any class.
                                    </small>
                                    <a href="{{ route('timetable') }}" class="btn custom-btn-outline">Booking Class</a>
                                </div>
                            @endif
                        </div>
                        <div class="tab-pane fade py-4" id="tidak_hadir">
                            @if (count($listBookingKelas->where('status_kehadiran', '0')) > 0)
                                @foreach ($listBookingKelas->where('status_kehadiran', '0') as $item)
                                    <div class="bg-white shadow-sm border p-4 mb-4" style="border-radius: 15px">
                                        <div class="row align-items-center justify-content-between">
                                            <div class="col-md-3 text-start">
                                                <div class="fw-bold">Nama Kelas</div>
                                                <small>Nama Instruktur</small>
                                            </div>
                                            <div class="col-md-3 text-center">
                                                <div class="fw-bold">Tanggal Booking</div>
                                                <small>Keterangan</small>
                                            </div>
                                            <div class="col-md-3 text-center">
                                                <div class="fw-bold">Jam Latihan</div>
                                                <small>status kehadiran</small>
                                            </div>
                                            <div class="col-md-3 text-end">
                                                <div class="fw-bold">Lokasi</div>
                                                <small>Studio</small>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="d-flex align-items-center flex-column justify-content-center"
                                    style="height: 50vh">
                                    <span class="fs-1 text-center">
                                        <i class="fa-solid fa-file-pen"></i>
                                    </span>
                                    <small class="d-block mb-3">
                                        You don't have any class.
                                    </small>
                                    <a href="{{ route('timetable') }}" class="btn custom-btn-outline">Booking Class</a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
