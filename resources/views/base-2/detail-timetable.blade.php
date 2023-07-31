@extends('base-2.layout')

@section('content')
    <section class="my-4">
        <div class="container">
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
                    <small class="d-block fw-bold mb-2">Select the latest time prior to the class to be booked in
                        automatically if there's an available slot</small>
                    <small class="d-block mb-2">You will receive an email once you are successfully booked in.</small>
                    <div class="row">
                        <div class="form-check col-md-3">
                            <input class="form-check-input" type="radio" name="set_jam" id="set_jam1" value="1"
                                checked>
                            <label class="form-check-label" for="set_jam1">
                                <small>1 hour before class</small>
                            </label>
                        </div>
                        <div class="form-check col-md-3">
                            <input class="form-check-input" type="radio" name="set_jam" id="set_jam1" value="2"
                                checked>
                            <label class="form-check-label" for="set_jam1">
                                <small>2 hours before class</small>
                            </label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-check col-md-3">
                            <input class="form-check-input" type="radio" name="set_jam" id="set_jam1" value="3"
                                checked>
                            <label class="form-check-label" for="set_jam1">
                                <small>3 hours before class</small>
                            </label>
                        </div>
                        <div class="form-check col-md-3">
                            <input class="form-check-input" type="radio" name="set_jam" id="set_jam1" value="6"
                                checked>
                            <label class="form-check-label" for="set_jam1">
                                <small>6 houre before class</small>
                            </label>
                        </div>
                    </div>
                    <hr class="my-4" style="opacity: 0.1">
                    <div class="fw-bold mb-4">Select your pricing plan</div>
                    <div class="custom-bg-danger w-100 py-3 mb-4 text-center mb-3" style="border-radius: 20px">
                        <small class="text-danger text-center">You do not have an eligible package or membership.</small>
                    </div>
                    <div class="p-4 border card-buy-package" style="border-radius: 20px">
                        <a href="{{ route('packages') }}" style="text-decoration: none;">
                            <h6 class="fw-bold">
                                Buy a package
                            </h6>
                            <small class="">With class packages, you can purchase as many or as few classes as you'd
                                like
                                and use them whenever you're ready. Class packs typically have an expiration date.</small>
                        </a>
                    </div>
                    <hr class="my-4" style="opacity: 0.1">
                    <div class="fw-bold">Things to bring</div>
                    <ul>
                        <li><small>Pilates Socks</small></li>
                    </ul>
                    <hr class="my-4" style="opacity: 0.1">
                    <div class="fw-bold">Getting there</div>
                    <div class="d-flex align-items-center justify-content-between">
                        <small class="fw-light">Jl. Bumi No.4, Kebayoran Baru, Jakarta Selatan, 12120, Second Floor Daerah
                            Khusus Ibukota Jakarta 12120</small>
                        <a href="" class="btn custom-btn-outline" style="padding: 15px">Get Directions</a>
                    </div>
                    <hr class="my-4" style="opacity: 0.1">
                    <div class="fw-bold">Cancellation policy</div>
                    <small class="fw-light">Please remove yourself from the waitlist 12 hours before the class start if you
                        no longer wish to attend class.
                        Bumped up waitlisted slots will not be waived for late cancel.</small>
                </div>
                <div class="col-md-4">
                    <div class="w-100 border shadow-sm sticky-top" style="border-radius: 15px; z-index: 1; top: 70px">
                        <img src="https://ik.imagekit.io/rezeve/business/e046f57f-3236-4290-aea7-7925cc309467/image/class/2eef108d-37b5-4bc3-ac51-bf3e98fcdffd~rsz_1rsz_db_00305.jpg"
                            style="width: 100%; height: 300px; object-fit: cover; border-top-left-radius: 15px; border-top-right-radius: 15px"
                            alt="" style="border-top-left-radius: 15px; border-top-right-radius: 15px">
                        <div class="p-4">
                            <h5 class="fw-bold mb-3">Arms, Booty, Core</h5>
                            <div class="mb-2 row align-items-center">
                                <div class="col-md-1">
                                    <span class="me-3 fs-5">
                                        <i class="fa-solid fa-calendar-week"></i>
                                    </span>
                                </div>
                                <div class="col-md">
                                    <small>31 Jul 2023, Monday</small>
                                </div>
                            </div>
                            <div class="mb-2 d-flex align-items-center">
                                <div class="col-md-1">
                                    <span class="me-3 fs-5">
                                        <i class="fa-solid fa-clock-rotate-left"></i>
                                    </span>
                                </div>
                                <div class="col-md">
                                    <small class="d-block">7:00 PM, 55 mins</small>
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
                                    <small class="d-block">Bumi Studio Jakarta</small>
                                    <small class="d-block">Studio 2</small>
                                </div>
                            </div>
                            <div class="mb-2 d-flex align-items-center">
                                <div class="col-md-1">
                                    <span class="me-3 fs-5">
                                        <i class="fa-regular fa-sun"></i>
                                    </span>
                                </div>
                                <div class="col-md">
                                    <small class="d-block">Coco Verico Ronaldo</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
