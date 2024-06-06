@extends('base-2.layout')

@section('content')
    <section class="my-5 py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    @livewire('profile.left-bar', ['active' => $active])
                </div>
                <div class="col-md ps-md-5">
                    <h4 class="fw-bold">My Booking</h4>
                    <ul class="nav nav-underline">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active px-3 custom-nav-link" role="tab" data-bs-toggle="tab"
                                href="#booking">Booking</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link px-3 custom-nav-link" role="tab" data-bs-toggle="tab"
                                href="#confirmed">Confirmed</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link px-3 custom-nav-link" role="tab" data-bs-toggle="tab"
                                href="#history">History</a>
                        </li>
                    </ul>
                    <hr>
                    @if (session()->has('info'))
                        <div class="alert alert-info" role="alert">
                            {{ session('info') }}
                        </div>
                    @endif
                    <div class="tab-content">
                        <div class="tab-pane fade py-4 show active" id="booking">
                            <h4 class="fw-bold">Booking Kelas</h4>
                            <div class="row">
                                @if (count($listBookingKelas) > 0)
                                    @foreach ($listBookingKelas as $item)
                                        <div class="bg-white shadow-sm border p-4 mb-4" style="border-radius: 15px">
                                            <div class="row justify-content-between">
                                                <div class="col-md-3 text-start">
                                                    <small class="fw-light">Kelas</small>
                                                    <div class="fw-bold">
                                                        <a style="text-decoration: none; color:black"
                                                            href="{{ route('detail-timetable', ['id' => $item->id_kelas_activity]) }}">
                                                            {{ $item->kelasActivity->kelas->nama_kelas }}
                                                        </a>
                                                    </div>
                                                    <small>{{ $item->kelasActivity->user->name }}</small>
                                                </div>
                                                <div class="col-md-3 text-center">
                                                    <small class="fw-light">Booking</small>
                                                    <div class="fw-bold">{{ $item->tanggal_booking }}</div>
                                                    <small>Keterangan : {{ $item->keterangan ?? '-' }}</small>
                                                </div>
                                                <div class="col-md-3 text-center">
                                                    <small class="fw-light">Waktu Latihan</small>
                                                    <div class="fw-bold">{{ $item->kelasActivity->jam_mulai_formatted }}
                                                    </div>
                                                    <small>Kehadiran :
                                                        <strong>{{ $item->status_kehadiran_formatted }}</strong></small>
                                                </div>
                                                <div class="col-md-3 text-end">
                                                    <small class="fw-light">Lokasi</small>
                                                    <div class="fw-bold">
                                                        <a href="{{ route('detail-location', ['id' => isset($item->kelasActivity->locationStudio->id_location) ? $item->kelasActivity->locationStudio->id_location : 0]) }}"
                                                            style="text-decoration: none; color: black">
                                                            {{ $item->kelasActivity->locationStudio->location->nama }}</a>
                                                    </div>
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
                        </div>
                        <div class="tab-pane fade py-4" id="hadir">
                            @if (count($listBookingKelas->where('status_kehadiran', 2)) > 0)
                                @foreach ($listBookingKelas->where('status_kehadiran', 2) as $item)
                                    <div class="bg-white shadow-sm border p-4 mb-4" style="border-radius: 15px">
                                        <div class="row justify-content-between">
                                            <div class="col-md-3 text-start">
                                                <small class="fw-light">Kelas</small>
                                                <div class="fw-bold">
                                                    <a style="text-decoration: none; color:black"
                                                        href="{{ route('detail-timetable', ['id' => $item->id_kelas_activity]) }}">
                                                        {{ $item->kelasActivity->kelas->nama_kelas }}
                                                    </a>
                                                </div>
                                                <small>{{ $item->kelasActivity->user->name }}</small>
                                            </div>
                                            <div class="col-md-3 text-center">
                                                <small class="fw-light">Booking</small>
                                                <div class="fw-bold">{{ $item->tanggal_booking }}</div>
                                                <small>Keterangan : {{ $item->keterangan ?? '-' }}</small>
                                            </div>
                                            <div class="col-md-3 text-center">
                                                <small class="fw-light">Waktu Latihan</small>
                                                <div class="fw-bold">{{ $item->kelasActivity->jam_mulai_formatted }}
                                                </div>
                                                <small>Kehadiran :
                                                    <strong>{{ $item->status_kehadiran_formatted }}</strong></small>
                                            </div>
                                            <div class="col-md-3 text-end">
                                                <small class="fw-light">Lokasi</small>
                                                <div class="fw-bold">
                                                    <a href="{{ route('detail-location', ['id' => isset($item->kelasActivity->locationStudio->id_location) ? $item->kelasActivity->locationStudio->id_location : 0]) }}"
                                                        style="text-decoration: none; color: black">
                                                        {{ $item->kelasActivity->locationStudio->location->nama }}</a>
                                                </div>
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
                        <div class="tab-pane fade py-4" id="izin">
                            @if (count($listBookingKelas->where('status_kehadiran', 2)) > 0)
                                @foreach ($listBookingKelas->where('status_kehadiran', 2) as $item)
                                    <div class="bg-white shadow-sm border p-4 mb-4" style="border-radius: 15px">
                                        <div class="row justify-content-between">
                                            <div class="col-md-3 text-start">
                                                <small class="fw-light">Kelas</small>
                                                <div class="fw-bold">
                                                    <a style="text-decoration: none; color:black"
                                                        href="{{ route('detail-timetable', ['id' => $item->id_kelas_activity]) }}">
                                                        {{ $item->kelasActivity->kelas->nama_kelas }}
                                                    </a>
                                                </div>
                                                <small>{{ $item->kelasActivity->user->name }}</small>
                                            </div>
                                            <div class="col-md-3 text-center">
                                                <small class="fw-light">Booking</small>
                                                <div class="fw-bold">{{ $item->tanggal_booking }}</div>
                                                <small>Keterangan : {{ $item->keterangan ?? '-' }}</small>
                                            </div>
                                            <div class="col-md-3 text-center">
                                                <small class="fw-light">Waktu Latihan</small>
                                                <div class="fw-bold">{{ $item->kelasActivity->jam_mulai_formatted }}
                                                </div>
                                                <small>Kehadiran :
                                                    <strong>{{ $item->status_kehadiran_formatted }}</strong></small>
                                            </div>
                                            <div class="col-md-3 text-end">
                                                <small class="fw-light">Lokasi</small>
                                                <div class="fw-bold">
                                                    <a href="{{ route('detail-location', ['id' => isset($item->kelasActivity->ruangan->locationStudio->id_location) ? $item->kelasActivity->ruangan->locationStudio->id_location : 0]) }}"
                                                        style="text-decoration: none; color: black">
                                                        {{ $item->kelasActivity->ruangan->locationStudio->location->nama }}</a>
                                                </div>
                                                <small>{{ $item->kelasActivity->ruangan->locationStudio->nama }}</small>
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
                                        <div class="row justify-content-between">
                                            <div class="col-md-3 text-start">
                                                <small class="fw-light">Kelas</small>
                                                <div class="fw-bold">
                                                    <a style="text-decoration: none; color:black"
                                                        href="{{ route('detail-timetable', ['id' => $item->id_kelas_activity]) }}">
                                                        {{ $item->kelasActivity->kelas->nama_kelas }}
                                                    </a>
                                                </div>
                                                <small>{{ $item->kelasActivity->user->name }}</small>
                                            </div>
                                            <div class="col-md-3 text-center">
                                                <small class="fw-light">Booking</small>
                                                <div class="fw-bold">{{ $item->tanggal_booking }}</div>
                                                <small>Keterangan : {{ $item->keterangan ?? '-' }}</small>
                                            </div>
                                            <div class="col-md-3 text-center">
                                                <small class="fw-light">Waktu Latihan</small>
                                                <div class="fw-bold">{{ $item->kelasActivity->jam_mulai_formatted }}
                                                </div>
                                                <small>Kehadiran :
                                                    <strong>{{ $item->status_kehadiran_formatted }}</strong></small>
                                            </div>
                                            <div class="col-md-3 text-end">
                                                <small class="fw-light">Lokasi</small>
                                                <div class="fw-bold">
                                                    <a href="{{ route('detail-location', ['id' => isset($item->kelasActivity->ruangan->locationStudio->id_location) ? $item->kelasActivity->ruangan->locationStudio->id_location : 0]) }}"
                                                        style="text-decoration: none; color: black">
                                                        {{ $item->kelasActivity->locationStudio->location->nama }}</a>
                                                </div>
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
                        <div class="tab-pane fade py-4" id="confirmed">
                            @if (count($listKelasConfirmed) > 0)
                                @foreach ($listKelasConfirmed as $item)
                                    <div class="bg-white shadow-sm border p-4 mb-4" style="border-radius: 15px">
                                        <div class="row d-none d-md-flex justify-content-between">
                                            <div class="col-md-3 text-start">
                                                <small class="fw-light">Kelas</small>
                                                <div class="fw-bold">
                                                    <a style="text-decoration: none; color:black"
                                                        href="{{ route('detail-timetable', ['id' => $item->id_kelas_activity]) }}">
                                                        {{ $item->kelasActivity->kelas->nama_kelas }}
                                                    </a>
                                                </div>
                                                <small>{{ $item->kelasActivity->user->name }}</small>
                                            </div>
                                            <div class="col-md-3 text-center">
                                                <small class="fw-light">Booking</small>
                                                <div class="fw-bold">{{ $item->tanggal_booking }}</div>
                                            </div>
                                            <div class="col-md-3 text-center">
                                                <small class="fw-light">Waktu Latihan</small>
                                                <div class="fw-bold">{{ $item->kelasActivity->jam_mulai_formatted }}</div>
                                                <small>Kehadiran :
                                                    <strong>{{ $item->status_kehadiran_formatted }}</strong></small>
                                            </div>
                                            <div class="col-md-3 text-end">
                                                <small class="fw-light">Lokasi</small>
                                                <div class="fw-bold">
                                                    <a href="{{ route('detail-location', ['id' => isset($item->kelasActivity->ruangan->locationStudio->id_location) ? $item->kelasActivity->ruangan->locationStudio->id_location : 0]) }}"
                                                        style="text-decoration: none; color: black">
                                                        {{ $item->kelasActivity->locationStudio->location->nama }}</a>
                                                </div>
                                                <small>{{ $item->kelasActivity->locationStudio->nama }}</small>
                                            </div>
                                        </div>
                                        <div class="row d-md-none d-flex mb-2">
                                            <div class="col-4">
                                                Kelas
                                            </div>
                                            <div class="col-8">
                                                : <a href="{{ route('detail-timetable', ['id' => $item->id_kelas_activity]) }}"
                                                    class="text-decoration-none text-dark fw-bold">{{ $item->kelasActivity->kelas->nama_kelas }}</a>
                                            </div>
                                        </div>
                                        <div class="row d-md-none d-flex mb-2">
                                            <div class="col-4">
                                                Instrukctor
                                            </div>
                                            <div class="col-8 fw-bold">
                                                : {{ $item->kelasActivity->user->name }}
                                            </div>
                                        </div>
                                        <div class="row d-md-none d-flex mb-2">
                                            <div class="col-4">
                                                Tanggal Booking
                                            </div>
                                            <div class="col-8 fw-bold">
                                                :
                                                {{ Carbon\Carbon::parse($item->created_at)->locale('id')->isoFormat('dddd, DD MMMM
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    YYYY') }}
                                            </div>
                                        </div>
                                        <div class="row d-md-none d-flex mb-2">
                                            <div class="col-4">
                                                Tanggal Latihan
                                            </div>
                                            <div class="col-8 fw-bold">
                                                :
                                                {{ Carbon\Carbon::parse($item->kelasActivity->tanggal)->locale('id')->isoFormat('dddd,
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    DD MMMM YYYY') }}
                                            </div>
                                        </div>
                                        <div class="row d-md-none d-flex mb-2">
                                            <div class="col-4">
                                                Lokasi
                                            </div>
                                            <div class="col-8 fw-bold">
                                                : <a href="{{ route('detail-location', ['id' => $item->kelasActivity->locationStudio->id_location]) }}"
                                                    class="text-decoration-none text-dark">{{ $item->kelasActivity->locationStudio->location->nama }}</a>
                                            </div>
                                        </div>
                                        <div class="row d-md-none d-flex mb-2">
                                            <div class="col-4">
                                                Studio
                                            </div>
                                            <div class="col-8 fw-bold">
                                                : {{ $item->kelasActivity->locationStudio->nama }}
                                            </div>
                                        </div>
                                        <div class="">
                                            <small>Notes : <span class="fw-bold">
                                                    {{ $item->keterangan ?? '-' }}</span>
                                            </small>
                                        </div>
                                        <div class="">
                                            <small>Rating : <span class="fw-bold">
                                                    @for ($i = 0; $i < 5; $i++)
                                                        @if ($i < $item->rating)
                                                            <span class="mx-1"><i class="fa-solid fa-star"
                                                                    style="color: #ffae00;"></i></span>
                                                        @else
                                                            <span class="mx-1">
                                                                <i class="fa-regular fa-star" style="color: #ffae00;"></i>
                                                            </span>
                                                        @endif
                                                    @endfor
                                                </span>
                                            </small>
                                        </div>
                                        <div>
                                            <small>Review : {{ $item->review ?? '-' }}</small>
                                        </div>
                                        @if ($item->kelasActivity->tanggal < now())
                                            <div class="text-end">
                                                <button type="button" class="btn btn-sm custom-btn btn-review"
                                                    data-id="{{ $item->id }}" id="">
                                                    Review
                                                </button>
                                            </div>
                                        @endif
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
                        <div class="tab-pane fade py-4" id="history">
                            @if (count($listBookingKelas) > 0)
                                @foreach ($listBookingKelas as $item)
                                    <div class="bg-white shadow-sm border p-4 mb-4" style="border-radius: 15px">
                                        <div class="row justify-content-between d-none d-md-flex">
                                            <div class="col-md-3 text-start">
                                                <small class="fw-light">Kelas</small>
                                                <div class="fw-bold">
                                                    <a style="text-decoration: none; color:black"
                                                        href="{{ route('detail-timetable', ['id' => $item->id_kelas_activity]) }}">
                                                        {{ $item->kelasActivity->kelas->nama_kelas }}
                                                    </a>
                                                </div>
                                                <small>{{ $item->kelasActivity->user->name }}</small>
                                            </div>
                                            <div class="col-md-3 text-center">
                                                <small class="fw-light">Booking</small>
                                                <div class="fw-bold">{{ $item->tanggal_booking }}</div>
                                                <small>Keterangan : {{ $item->keterangan ?? '-' }}</small>
                                            </div>
                                            <div class="col-md-3 text-center">
                                                <small class="fw-light">Waktu Latihan</small>
                                                <div class="fw-bold">{{ $item->kelasActivity->jam_mulai_formatted }}
                                                </div>
                                                <small>Kehadiran :
                                                    <strong>{{ $item->status_kehadiran_formatted }}</strong></small>
                                            </div>
                                            <div class="col-md-3 text-end">
                                                <small class="fw-light">Lokasi</small>
                                                <div class="fw-bold">
                                                    <a href="{{ route('detail-location', ['id' => isset($item->kelasActivity->ruangan->locationStudio->id_location) ? $item->kelasActivity->ruangan->locationStudio->id_location : 0]) }}"
                                                        style="text-decoration: none; color: black">
                                                        {{ $item->kelasActivity->locationStudio->location->nama }}</a>
                                                </div>
                                                <small>{{ $item->kelasActivity->locationStudio->nama }}</small>
                                            </div>
                                        </div>
                                        <div class="row d-md-none d-flex mb-2">
                                            <div class="col-4">
                                                Kelas
                                            </div>
                                            <div class="col-8">
                                                : <a href="{{ route('detail-timetable', ['id' => $item->id_kelas_activity]) }}"
                                                    class="text-decoration-none text-dark fw-bold">{{ $item->kelasActivity->kelas->nama_kelas }}</a>
                                            </div>
                                        </div>
                                        <div class="row d-md-none d-flex mb-2">
                                            <div class="col-4">
                                                Instrukctor
                                            </div>
                                            <div class="col-8 fw-bold">
                                                : {{ $item->kelasActivity->user->name }}
                                            </div>
                                        </div>
                                        <div class="row d-md-none d-flex mb-2">
                                            <div class="col-4">
                                                Tanggal Booking
                                            </div>
                                            <div class="col-8 fw-bold">
                                                :
                                                {{ Carbon\Carbon::parse($item->created_at)->locale('id')->isoFormat('dddd, DD MMMM
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                YYYY') }}
                                            </div>
                                        </div>
                                        <div class="row d-md-none d-flex mb-2">
                                            <div class="col-4">
                                                Tanggal Latihan
                                            </div>
                                            <div class="col-8 fw-bold">
                                                :
                                                {{ Carbon\Carbon::parse($item->kelasActivity->tanggal)->locale('id')->isoFormat('dddd,
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                DD MMMM YYYY') }}
                                            </div>
                                        </div>
                                        <div class="row d-md-none d-flex mb-2">
                                            <div class="col-4">
                                                Lokasi
                                            </div>
                                            <div class="col-8 fw-bold">
                                                : <a href="{{ route('detail-location', ['id' => $item->kelasActivity->locationStudio->id_location]) }}"
                                                    class="text-decoration-none text-dark">{{ $item->kelasActivity->locationStudio->location->nama }}</a>
                                            </div>
                                        </div>
                                        <div class="row d-md-none d-flex mb-2">
                                            <div class="col-4">
                                                Studio
                                            </div>
                                            <div class="col-8 fw-bold">
                                                : {{ $item->kelasActivity->locationStudio->nama }}
                                            </div>
                                        </div>
                                        <div class="">
                                            <small>Notes : <span class="fw-bold">
                                                    {{ $item->keterangan ?? '-' }}</span>
                                            </small>
                                        </div>
                                        @if ($item->kelasActivity->tanggal <= now())
                                            <div class="text-end">
                                                <button type="button" class="btn btn-sm custom-btn btn-review"
                                                    data-id="{{ $item->id }}" id="">
                                                    Review
                                                </button>
                                            </div>
                                        @endif
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

    <!-- Modal -->
    <div class="modal fade" id="modal_tambah_kredit" tabindex="-1" aria-labelledby="modal_tambah_kredit"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="background-color: #faf0e6">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modal_tambah_kredit">Tambah Kredit Paket</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <img src="{{ asset('assets/images/logo.png') }}" class="img-fluid" alt="">
                    </div>
                    <div class="text-center mb-3">
                        <span class="fs-1 fw-bold" style="color: #3c6553">PRICE LIST</span>
                    </div>
                    <div class="text-center mb-5">
                        <div class="py-2" style="background-color: #3c6553; border-radius: 20px">
                            <span class="text-white fw-semibold">PRIVATE CLASS</span>
                        </div>
                    </div>
                    @if (count($listPaket) > 0)
                        @foreach ($listPaket as $item)
                            <div class="row mb-3 px-5 align-items-center">
                                <div class="col-md-8 col-8 fw-bold" style="color: #3c6553">
                                    {{ $item->nama }}
                                </div>
                                <div class="col-md-4 col-4">
                                    <a href="{{ route('detail-packages', ['id' => $item->id]) }}" class="btn custom-btn"
                                        style="background-color: #3c6553 !important; color: white">Submit</a>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="py-5 text-center fw-bold" style="color: #3c6553">
                            Tidak ada paket
                        </div>
                    @endif
                    <small class="d-block text-center" style="color: #3c6553 !important">
                        Certified trainer. Price is per session.
                    </small>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn custom-btn-outline" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    @livewire('profile.booking-kelas.review')
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            $('select[name="hari"]').select2({
                theme: 'bootstrap-5'
            })
            $('select[name="jam"]').select2({
                theme: 'bootstrap-5'
            })
        });

        $('#btn-tambah-kredit').on('click', function() {
            $('#modal_tambah_kredit').modal('show')
        })

        $('.btn-batal').on('click', async function() {
            const id = $(this).data('id')
            const response = await sweetAlert('danger', 'Peringatan !',
                'Apakah kamu yakin ingin membatalkan kelas ini ?', 'Ya, Batalkan')
            if (response.isConfirmed == true) {
                $('#form_batal_' + id).submit();
            }
        })

        $('.btn-review').on('click', function() {
            const id_booking_kelas = $(this).data('id')
            $('#modal_review_kelas').modal('show')
            Livewire.emit('setBookingKelas', id_booking_kelas)
        })
    </script>
@endpush
