@extends('base-2.layout')

@section('content')
    <section class="my-4">
        <div class="container">
            <form action="{{ route('order-kelas.post', ['id_kelas_activity' => $kelasActivity->id]) }}" method="POST"
                id="form_booking_kelas">
                @csrf
                <div class="row">
                    <div class="col-md-8 mb-5">
                        <div class="mb-5">
                            <small class="fw-light mx-1">Timetable</small>
                            <small class="mx-1"><i class="fa-solid fa-chevron-right"></i></small>
                            <small class="fw-bold mx-1">{{ $kelasActivity->kelas->nama_kelas }} wtih
                                {{ $kelasActivity->user->name }}</small>
                        </div>
                        <h3 class="fw-bold mb-3">
                            Book this class
                        </h3>
                        <small class="">Please confirm your presence before <strong>6 pm</strong> if you are unable to
                            make it when practice starts</small>
                        @if (!$orderPaket)
                            <div class="fw-bold mb-4">Select your pricing plan</div>
                            <hr class="my-4" style="opacity: 0.1">
                            <div class="custom-bg-danger w-100 py-3 mb-4 text-center mb-3" style="border-radius: 20px">
                                <small class="text-danger text-center">Anda tidak memiliki paket yang memenuhi syarat atau
                                    keanggotaan.</small>
                            </div>
                        @elseif($orderPaket->status == 0 || $orderPaket->status == 2)
                            <div class="custom-bg-danger w-100 py-3 mb-4 text-center mb-3" style="border-radius: 20px">
                                <small class="text-danger text-center">Kamu sudah memiliki paket tapi belum di konfirmasi
                                    oleh
                                    admin. silahkan cek pembayaran.</small>
                            </div>
                        @endif
                        @if (!$orderPaket || $orderPaket->status != 1)
                            <div class="p-4 border card-buy-package" style="border-radius: 20px">
                                <a href="{{ route('packages') }}" style="text-decoration: none;">
                                    <h6 class="fw-bold">
                                        Buy a package
                                    </h6>
                                    <small class="">With class packages, you can purchase as many or as few classes as
                                        you'd
                                        like
                                        and use them whenever you're ready. Class packs typically have an expiration
                                        date.</small>
                                </a>
                            </div>
                        @endif
                        <hr class="my-4" style="opacity: 0.1">
                        <div class="fw-bold mb-2">Things to bring</div>
                        <ul>
                            <li><small>Pilates Socks</small></li>
                        </ul>
                        <hr class="my-4" style="opacity: 0.1">
                        <div class="fw-bold mb-2">Getting there</div>
                        <div class="">
                            <small
                                class="d-block fw-light mb-3">{{ $kelasActivity->ruangan->locationStudio->location->alamat }}</small>
                            <a href="{{ $kelasActivity->ruangan->locationStudio->location->url_location }}" target="_blank"
                                class="btn custom-btn-outline w-100" style="padding: 5px"><small>Get Directions</small></a>
                        </div>
                        <hr class="my-4" style="opacity: 0.1">
                        <div class="fw-bold">Cancellation policy</div>
                        <small class="fw-light">{{ $kelasActivity->cancellation_policy }}</small>
                    </div>
                    <div class="col-md-4">
                        <div class="w-100 border shadow-sm sticky-top" style="border-radius: 15px; z-index: 1; top: 70px">
                            <img src="{{ count($kelasActivity->kelas->kelasImage) > 0 ? asset('storage/' . $kelasActivity->kelas->kelasImage[0]->image) : null }}"
                                style="width: 100%; height: 300px; object-fit: cover; border-top-left-radius: 15px; border-top-right-radius: 15px"
                                alt="" style="border-top-left-radius: 15px; border-top-right-radius: 15px">
                            <div class="p-4">
                                <h5 class="fw-bold mb-3">{{ $kelasActivity->kelas->nama_kelas }}</h5>
                                <div class="mb-2 row align-items-center">
                                    <div class="col-md-1">
                                        <span class="me-3 fs-5">
                                            <i class="fa-solid fa-calendar-week"></i>
                                        </span>
                                    </div>
                                    <div class="col-md">
                                        <small>{{ \Carbon\Carbon::parse($kelasActivity->jam_latihan)->locale('id')->isoFormat('dddd, DD MMMM YYYY') }}</small>
                                    </div>
                                </div>
                                <div class="mb-2 d-flex align-items-center">
                                    <div class="col-md-1">
                                        <span class="me-3 fs-5">
                                            <i class="fa-solid fa-clock-rotate-left"></i>
                                        </span>
                                    </div>
                                    <div class="col-md">
                                        <small
                                            class="d-block">{{ \Carbon\Carbon::parse($kelasActivity->jam_mulai)->locale('id')->isoFormat('HH:mm') }}
                                            -
                                            {{ \Carbon\Carbon::parse($kelasActivity->jam_selesai)->locale('id')->isoFormat('HH:mm') }}</small>
                                        <small class="d-block text-secondary">Check-in anytime before class begins</small>
                                    </div>
                                </div>
                                <div class="mb-2 d-flex align-items-center">
                                    <div class="col-md-1">
                                        <span class="me-3 fs-5">
                                            <i class="fa-solid fa-location-dot"></i>
                                        </span>
                                    </div>
                                    <div class="col-md">
                                        <small class="d-block">{{ $kelasActivity->ruangan->locationStudio->location->nama }}</small>
                                        <small class="d-block">{{ $kelasActivity->ruangan->locationStudio->nama }} ({{ $kelasActivity->ruangan->nama_ruangan }})</small>
                                    </div>
                                </div>
                                <div class="mb-2 d-flex align-items-center">
                                    <div class="col-md-1">
                                        <span class="me-3 fs-5">
                                            <i class="fa-regular fa-sun"></i>
                                        </span>
                                    </div>
                                    <div class="col-md">
                                        <small class="d-block">{{ $kelasActivity->user->name }}</small>
                                    </div>
                                </div>
                                <div class="text-center">

                                    @if ($isBooking)
                                        <small class="d-block my-4">Anda sudah memesan kelas ini silahkan lihat pada bagian
                                            <a class="text-decoration-none text-dark"
                                                href="{{ route('profile.bookings') }}">
                                                <Strong>Profile -> My Bookings</Strong></a></small>
                                    @endif
                                    <button type="button" class="btn custom-btn w-100 mb-1" id="btn_book_now"
                                        @if ($isActive == false || $isBooking == true) disabled @endif>
                                        Book Now
                                    </button>
                                    <div class="" style="font-size: 8pt">
                                        By booking, you agree to the <a href="{{ route('term-condition') }}" class="text-dark">
                                            Terms & Conditions</a>.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection

@push('js')
    <script>
        $('#btn_book_now').on('click', async function() {
            const response = await sweetAlert('info', 'Peringatan !',
                'Apakah kamu yakin ingin mengambil kelas ini ?', 'Ya, Ambil')
            if (response.isConfirmed == true) {
                $('#form_booking_kelas').submit();
            }
        })
    </script>
@endpush
