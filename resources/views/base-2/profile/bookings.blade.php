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
                            <a class="nav-link custom-nav-link px-3" role="tab" data-bs-toggle="tab"
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
                        <div class="tab-pane fade py-4 show active" id="credit">
                            <h4 class="fw-bold">Kredit</h4>
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <div class="card  shadow-sm" style="border-radius: 15px">
                                        <div class="card-body d-flex align-items-end justify-content-center">
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
                                    <div class="card  shadow-sm" style="border-radius: 15px">
                                        <div class="card-body d-flex align-items-end justify-content-center">
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
                                    <div class="card  shadow-sm" style="border-radius: 15px">
                                        <div class="card-body d-flex align-items-end justify-content-center">
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
                                    <div class="card  shadow-sm" style="border-radius: 15px">
                                        <div class="card-body d-flex align-items-end justify-content-center">
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
                                    <div class="card  shadow-sm" style="border-radius: 15px">
                                        <div class="card-body d-flex align-items-end justify-content-center">
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
                                    <div class="card  shadow-sm" style="border-radius: 15px">
                                        <div class="card-body d-flex align-items-end justify-content-center">
                                            <div class="text-center">
                                                <h6 class="fw-light">Sisa Kredit</h6>
                                                <h2 class="fw-bold">{{ number_format($sisaKredit, 0, ',', '.') }}</h2>
                                            </div>
                                            <div class="text-center fs-1 text-muted" style="opacity: 0.2">
                                                <i class="fa-solid fa-list-ol"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="card  shadow-sm" style="border-radius: 15px">
                                        <div class="card-body d-flex align-items-end justify-content-center">
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
                        <div class="tab-pane fade py-4" id="belum_ada_status">
                            @if (count($listBookingKelas->where('status_kehadiran', null)) > 0)
                                @foreach ($listBookingKelas->where('status_kehadiran', null) as $item)
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
                                                <div class="fw-bold">{{ $item->kelasActivity->jam_latihan_formatted }}
                                                </div>
                                                <small>Kehadiran :
                                                    <strong>{{ $item->status_kehadiran_formatted }}</strong></small>
                                            </div>
                                            <div class="col-md-3 text-end">
                                                <small class="fw-light">Lokasi</small>
                                                <div class="fw-bold">
                                                    <a href="{{ route('detail-location', ['id' => $item->kelasActivity->locationStudio->id_location]) }}"
                                                        style="text-decoration: none; color: black">
                                                        {{ $item->kelasActivity->locationStudio->location->nama }}</a>
                                                </div>
                                                <small>{{ $item->kelasActivity->locationStudio->nama }}</small>
                                            </div>
                                        </div>
                                        <form
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
                                                            @if ($item->status_kehadiran == '2') selected @endif>Izin</option>
                                                        <option value="1"
                                                            @if ($item->status_kehadiran == '1') selected @endif>Hadir</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4 col-4 text-center">
                                                    <button type="submit" class="btn custom-btn w-100"
                                                        @if ($isDisable) disabled @endif>Submit</button>
                                                </div>
                                            </div>
                                        </form>
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
                                                <div class="fw-bold">{{ $item->kelasActivity->jam_latihan_formatted }}
                                                </div>
                                                <small>Kehadiran :
                                                    <strong>{{ $item->status_kehadiran_formatted }}</strong></small>
                                            </div>
                                            <div class="col-md-3 text-end">
                                                <small class="fw-light">Lokasi</small>
                                                <div class="fw-bold">
                                                    <a href="{{ route('detail-location', ['id' => $item->kelasActivity->locationStudio->id_location]) }}"
                                                        style="text-decoration: none; color: black">
                                                        {{ $item->kelasActivity->locationStudio->location->nama }}</a>
                                                </div>
                                                <small>{{ $item->kelasActivity->locationStudio->nama }}</small>
                                            </div>
                                        </div>
                                        <form
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
                                                            @if ($item->status_kehadiran == '2') selected @endif>Izin</option>
                                                        <option value="1"
                                                            @if ($item->status_kehadiran == '1') selected @endif>Hadir
                                                        </option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4 col-4 text-center">
                                                    <button type="submit" class="btn custom-btn w-100"
                                                        @if ($isDisable) disabled @endif>Submit</button>
                                                </div>
                                            </div>
                                        </form>
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
                                                <div class="fw-bold">{{ $item->kelasActivity->jam_latihan_formatted }}
                                                </div>
                                                <small>Kehadiran :
                                                    <strong>{{ $item->status_kehadiran_formatted }}</strong></small>
                                            </div>
                                            <div class="col-md-3 text-end">
                                                <small class="fw-light">Lokasi</small>
                                                <div class="fw-bold">
                                                    <a href="{{ route('detail-location', ['id' => $item->kelasActivity->locationStudio->id_location]) }}"
                                                        style="text-decoration: none; color: black">
                                                        {{ $item->kelasActivity->locationStudio->location->nama }}</a>
                                                </div>
                                                <small>{{ $item->kelasActivity->locationStudio->nama }}</small>
                                            </div>
                                        </div>
                                        <form
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
                                                <div class="col-md-4 col-4 text-center">
                                                    <button type="submit" class="btn custom-btn w-100"
                                                        @if ($isDisable) disabled @endif>Submit</button>
                                                </div>
                                            </div>
                                        </form>
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
                                                <div class="fw-bold">{{ $item->kelasActivity->jam_latihan_formatted }}
                                                </div>
                                                <small>Kehadiran :
                                                    <strong>{{ $item->status_kehadiran_formatted }}</strong></small>
                                            </div>
                                            <div class="col-md-3 text-end">
                                                <small class="fw-light">Lokasi</small>
                                                <div class="fw-bold">
                                                    <a href="{{ route('detail-location', ['id' => $item->kelasActivity->locationStudio->id_location]) }}"
                                                        style="text-decoration: none; color: black">
                                                        {{ $item->kelasActivity->locationStudio->location->nama }}</a>
                                                </div>
                                                <small>{{ $item->kelasActivity->locationStudio->nama }}</small>
                                            </div>
                                        </div>
                                        <form
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
                                                <div class="col-md-4 col-4 text-center">
                                                    <button type="submit" class="btn custom-btn w-100"
                                                        @if ($isDisable) disabled @endif>Submit</button>
                                                </div>
                                            </div>
                                        </form>
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

@push('js')
    <script>
        $(document).ready(function() {
            $('select[name="status_kehadiran"]').select2({
                theme: 'bootstrap-5'
            })
        });

        $('#btn-tambah-kredit').on('click', function(){
            $('#modal_tambah_kredit').modal('show')
        })
    </script>
@endpush
