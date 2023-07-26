@extends('base-2.layout')

@section('content')
    <section class="my-4">
        <div class="container">
            <div class="row justify-content-center align-items-center">
                <div class="col-md-8 row mb-3">
                    <div class="col-md-8">
                        <div class="d-flex mb-5">
                            <small class="fw-light mx-1">Package</small>
                            <small class="mx-1"><i class="fa-solid fa-chevron-right"></i></small>
                            <small class="fw-bold mx-1">Confirm and Pay</small>
                        </div>
                        <h3 class="fw-bold mb-4">Your Package</h3>
                        <div class="mb-2 fw-light mb-4">
                            <div class="mb-1">Intro Offer (3 classes)</div>
                            <div class="mb-1">Expires in 14 days</div>
                            <div class="mb-1">Please approach our admin to purchase this package.</div>
                        </div>
                        <div class="border bg-white p-3" style="border-radius: 15px">
                            <div class="mb-2">
                                <span class="me-2"><i class="fa-regular fa-sun"></i></span>
                                <small>3 Credits</small>
                            </div>
                            <div class="mb-2">
                                <span class="me-2"><i class="fa-solid fa-house-user"></i></span>
                                <small>All Location</small>
                            </div>
                            <div class="mb-2">
                                <span class="me-2"><i class="fa-solid fa-calendar-days"></i></span>
                                <small>Valid for 14 Days from date of first booking</small>
                            </div>
                            <div class="mb-2">
                                <span class="me-2"><i class="fa-regular fa-circle-check"></i></span>
                                <small>Onsite classes only</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
