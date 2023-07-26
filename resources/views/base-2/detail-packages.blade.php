@extends('base-2.layout')

@section('content')
    <section class="my-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="d-flex mb-5">
                        <small class="fw-light mx-1">Package</small>
                        <small class="mx-1"><i class="fa-solid fa-chevron-right"></i></small>
                        <small class="fw-bold mx-1">Confirm and Pay</small>
                    </div>
                    <h3 class="fw-bold mb-4">Your Package</h3>
                    <div class="mb-2 fw-light mb-4">
                        <small class="d-block mb-1">Intro Offer (3 classes)</small>
                        <small class="d-block mb-1">Expires in 14 days</small>
                        <small class="d-block mb-1">Please approach our admin to purchase this package.</small>
                    </div>
                    <div class="border bg-white p-3 my-4" style="border-radius: 15px">
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
                    <h4 class="fw-bold mb-3">What you’re eligible</h4>
                    <ul class="row">
                        <li class="col-md-6"><small class="fw-light">Wanda Funda </small></li>
                        <li class="col-md-6"><small class="fw-light">Reformer Flow </small></li>
                        <li class="col-md-6"><small class="fw-light">Tower Power </small></li>
                        <li class="col-md-6"><small class="fw-light">Strengthen, Lengthen, Tone </small></li>
                        <li class="col-md-6"><small class="fw-light">Arms, Booty, Core </small></li>
                    </ul>
                    <hr class="my-4">
                    <h3 class="fw-bold mb-4">Promote Code</h3>
                    <div class="input-group input-group">
                        <span class="input-group-text bg-white" id="inputGroup-sizing-lg"><i
                                class="fa-solid fa-tag"></i></span>
                        <input type="text" class="form-control" aria-label="Sizing example input"
                            aria-describedby="inputGroup-sizing-lg" placeholder="Promote Code"
                            style="padding-top: 15px; padding-bottom: 15px">
                        <span class="input-group-text text-sm bg-white" id="basic-addon2">
                            <small style="font-size: 13px">Apply</small>
                        </span>
                    </div>
                    <hr class="my-5">
                    <small class="d-block fw-light mb-2">
                        By purchasing you agree to the <strong>
                            Terms & Conditions
                        </strong>
                    </small>
                    <div class="text-center">
                        <button class="btn custom-btn w-100">Confirm and Pay</button>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="px-4 py-5 bg-white shadow sticky-top" style="border-radius: 15px; top: 80px">
                        <small class="d-block mb-2 fw-semibold text-uppercase text-secondary">Group Class</small>
                    <h5 class="fw-semibold mb-3">Intro Offer (3 classes)</h5>
                    <div class="d-flex align-items-center justify-content-between">
                        <small class="fw-light">All location access</small>
                        <small class="fw-light">3 credits</small>
                    </div>
                    <hr class="my-3">
                    <div class="fw-semibold mb-3">Price detail</div>
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="fw-light">Standard</small>
                        <small class="fw-light">Rp.500.000</small>
                    </div>
                    <hr class="my-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="fw-light">Subtotal</small>
                        <small class="fw-light">Rp.500.000</small>
                    </div>
                    <hr class="my-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="fw-bold">Grand Total</small>
                        <small class="fw-bold">Rp.500.000</small>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
