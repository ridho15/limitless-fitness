@extends('base-2.layout')

@section('content')
    <section class="my-5 py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    @livewire('profile.left-bar', ['active' => $active])
                </div>
                <div class="col-md ps-5">
                    <h4 class="fw-bold">My Booking</h4>
                    <ul class="nav nav-underline d-none d-sm-flex">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active px-3 custom-nav-link" role="tab" data-bs-toggle="tab"
                                href="#credit">Credit</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link px-3 custom-nav-link" role="tab" data-bs-toggle="tab"
                                href="#confirmed">Confirmed</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link px-3 custom-nav-link" role="tab" data-bs-toggle="tab"
                                href="#history">History</a>
                        </li>
                        {{-- <li class="nav-item" role="presentation">
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
                        </li> --}}

                    </ul>
                    <hr>
                    @if (session()->has('info'))
                        <div class="alert alert-info" role="alert">
                            {{ session('info') }}
                        </div>
                    @endif
                    <div class="tab-content">
                        <div class="tab-pane fade py-4 show active" id="credit">
                            <h4 class="fw-bold">Kredit</h4>
                            <div class="row">
                                {{-- <div class="col-md-3 mb-3">
                                    <div class="card h-100 shadow-sm" style="border-radius: 15px">
                                        <div class="card-body d-flex align-items-center justify-content-center">
                                            <div class="text-center">
                                                <h6 class="fw-light">Total Kredit</h6>
                                                <h2 class="fw-bold">{{ number_format($jumlahKredit, 0, ',', '.') }}</h2>
                                            </div>
                                            <div class="text-center fs-1 text-muted" style="opacity: 0.2">
                                                <i class="fa-solid fa-list-ol"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="card h-100 shadow-sm" style="border-radius: 15px">
                                        <div class="card-body d-flex align-items-center justify-content-center">
                                            <div class="text-center">
                                                <h6 class="fw-light">Digunakan</h6>
                                                <h2 class="fw-bold">{{ number_format($jumlahDigunakan, 0, ',', '.') }}</h2>
                                            </div>
                                            <div class="text-center fs-1 text-muted" style="opacity: 0.2">
                                                <i class="fa-solid fa-list-ol"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="card h-100 shadow-sm" style="border-radius: 15px">
                                        <div class="card-body d-flex align-items-center justify-content-center">
                                            <div class="text-center">
                                                <h6 class="fw-light">Hadir</h6>
                                                <h2 class="fw-bold">{{ number_format($jumlahHadir, 0, ',', '.') }}</h2>
                                            </div>
                                            <div class="text-center fs-1 text-muted" style="opacity: 0.2">
                                                <i class="fa-solid fa-list-ol"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="card h-100 shadow-sm" style="border-radius: 15px">
                                        <div class="card-body d-flex align-items-center justify-content-center">
                                            <div class="text-center">
                                                <h6 class="fw-light">Izin</h6>
                                                <h2 class="fw-bold">{{ number_format($jumlahIzin, 0, ',', '.') }}</h2>
                                            </div>
                                            <div class="text-center fs-1 text-muted" style="opacity: 0.2">
                                                <i class="fa-solid fa-list-ol"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="card h-100 shadow-sm" style="border-radius: 15px">
                                        <div class="card-body d-flex align-items-center justify-content-center">
                                            <div class="text-center">
                                                <h6 class="fw-light">Tidak Hadir</h6>
                                                <h2 class="fw-bold">{{ number_format($jumlahTidakHadir, 0, ',', '.') }}</h2>
                                            </div>
                                            <div class="text-center fs-1 text-muted" style="opacity: 0.2">
                                                <i class="fa-solid fa-list-ol"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="card h-100 shadow-sm" style="border-radius: 15px">
                                        <div class="card-body d-flex align-items-center justify-content-center">
                                            <div class="text-center">
                                                <h6 class="fw-light">Sisa Kredit</h6>
                                                <h2 class="fw-bold">{{ number_format($sisaKredit, 0, ',', '.') }}</h2>
                                            </div>
                                            <div class="text-center fs-1 text-muted" style="opacity: 0.2">
                                                <i class="fa-solid fa-list-ol"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div> --}}
                                @foreach ($listData as $item)
                                    <div class="col-md-3 mb-3">
                                        <div class="card h-100 shadow-sm" style="border-radius: 15px">
                                            <div class="card-body d-flex align-items-start">
                                                <div>
                                                    <h6 class="">{{ $item['nama_kelas'] }}</h6>
                                                    <div>
                                                        <small class="d-block">Total Kredit :
                                                            {{ number_format($item['jumlah_kredit'], 0, ',', '.') }}</small>
                                                    </div>
                                                    <div>
                                                        <small class="text-start">Digunakan :
                                                            {{ number_format($item['digunakan'], 0, ',', '.') }}</small>
                                                    </div>
                                                    <div>
                                                        <small class="">Sisa Kredit :
                                                            {{ number_format($item['sisa'], 0, ',', '.') }}</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                <div class="col-md-3 mb-3">
                                    <div class="card h-100 shadow-sm" style="border-radius: 15px">
                                        <div class="card-body d-flex align-items-center justify-content-center">
                                            <div class="text-center">
                                                <h6 class="fw-light">Tambah Kredit</h6>
                                                <h2 class="fw-bold">
                                                    <a href="#" id="btn-tambah-kredit" class="text-dark">
                                                        <i class="fa-solid fa-plus"></i>
                                                    </a>
                                                </h2>
                                            </div>
                                            <div class="text-center fs-1 text-muted" style="opacity: 0.2">
                                                <i class="fa-solid fa-list-ol"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade py-4" id="hadir">
                            @if (count($listBookingKelas->where('status_kehadiran', 1)) > 0)
                                @foreach ($listBookingKelas->where('status_kehadiran', 1) as $item)
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
                                        {{-- <form
                                            action="{{ route('profile.bookings.update-kehadiran', ['id_booking_kelas' => $item->id]) }}"
                                            method="POST">
                                            @csrf
                                            @php
                                                $isDisable = false;
                                                $kelasActivity = $item->kelasActivity;
                                                $lastTimeClass = \Carbon\Carbon::parse($kelasActivity->jam_mulai)
                                                    ->subDays(1)
                                                    ->locale('id')
                                                    ->isoFormat('YYYY-MM-DD HH:mm:ss');
                                                if ($lastTimeClass <= now()) {
                                                    $isDisable = true;
                                                }
                                            @endphp
                                            <div class="row align-items-center mt-2">
                                                <div class="col-md-8 col-8 text-center">
                                                    <select name="status_kehadiran" class="form-control"
                                                        id="status_kehadiran"
                                                        @if ($isDisable) disabled @endif>
                                                        <option value=""
                                                            @if ($item->status_kehadiran == null) selected @endif>Belum
                                                            Dikonfirmasi
                                                        </option>
                                                        <option value="0"
                                                            @if ($item->status_kehadiran == '0') selected @endif>Tidak Hadir
                                                        </option>
                                                        <option value="2"
                                                            @if ($item->status_kehadiran == '2') selected @endif>Izin
                                                        </option>
                                                        <option value="1"
                                                            @if ($item->status_kehadiran == '1') selected @endif>Hadir
                                                        </option>
                                                    </select>
                                                </div>
                                                <div class="col-md-2 col-2 text-center">
                                                    <button type="submit" class="btn custom-btn w-100"
                                                        @if ($isDisable) disabled @endif>Submit</button>
                                                </div>
                                                <div class="col-md-2 col-2 text-center">
                                                    <button type="button" class="btn btn-danger w-100 btn-batal"
                                                        data-id="{{ $item->id }}" style="border-radius: 20px"
                                                        @if ($isDisable) disabled @endif><i
                                                            class="fa-regular fa-circle-xmark"></i> Batalkan</button>
                                                </div>
                                            </div>
                                        </form>
                                        <form action="{{ route('profile.bookings.batal', ['id' => $item->id]) }}"
                                            method="POST" id="form_batal_{{ $item->id }}">
                                            @csrf
                                        </form> --}}
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
                                        {{-- <form
                                            action="{{ route('profile.bookings.update-kehadiran', ['id_booking_kelas' => $item->id]) }}"
                                            method="POST">
                                            @csrf
                                            @php
                                                $isDisable = false;
                                                $kelasActivity = $item->kelasActivity;
                                                $lastTimeClass = \Carbon\Carbon::parse($kelasActivity->jam_mulai)
                                                    ->subDays(1)
                                                    ->locale('id')
                                                    ->isoFormat('YYYY-MM-DD HH:mm:ss');
                                                if ($lastTimeClass <= now()) {
                                                    $isDisable = true;
                                                }
                                            @endphp
                                            <div class="row align-items-center mt-2">
                                                <div class="col-md-8 col-8 text-center">
                                                    <select name="status_kehadiran" class="form-control"
                                                        id="status_kehadiran"
                                                        @if ($isDisable) disabled @endif>
                                                        <option value=""
                                                            @if ($item->status_kehadiran == null) selected @endif>Belum
                                                            Dikonfirmasi
                                                        </option>
                                                        <option value="0"
                                                            @if ($item->status_kehadiran == '0') selected @endif>Tidak Hadir
                                                        </option>
                                                        <option value="2"
                                                            @if ($item->status_kehadiran == '2') selected @endif>Izin
                                                        </option>
                                                        <option value="1"
                                                            @if ($item->status_kehadiran == '1') selected @endif>Hadir
                                                        </option>
                                                    </select>
                                                </div>
                                                <div class="col-md-2 col-2 text-center">
                                                    <button type="submit" class="btn custom-btn w-100"
                                                        @if ($isDisable) disabled @endif>Submit</button>
                                                </div>
                                                <div class="col-md-2 col-2 text-center">
                                                    <button type="button" class="btn btn-danger w-100 btn-batal"
                                                        data-id="{{ $item->id }}" style="border-radius: 20px"
                                                        @if ($isDisable) disabled @endif><i
                                                            class="fa-regular fa-circle-xmark"></i> Batalkan</button>
                                                </div>
                                            </div>
                                        </form>
                                        <form action="{{ route('profile.bookings.batal', ['id' => $item->id]) }}"
                                            method="POST" id="form_batal_{{ $item->id }}">
                                            @csrf
                                        </form> --}}
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
                        <div class="tab-pane fade py-4" id="history">
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
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            $('select[name="status_kehadiran"]').select2({
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
    </script>
@endpush
