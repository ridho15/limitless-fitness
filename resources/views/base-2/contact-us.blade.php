@extends('base-2.layout')

@section('content')
    <section class="my-2">
        <div class="container">
            <div class="row my-4">
                <div class="col-md-12">
                    <h3 class="fw-bold">Contact Us</h3>
                    <small class="fw-light">We're here to assist you! You may also reach out to us via the form below for
                        more assistance.</small>
                </div>
            </div>
            <div class="row justify-content-center align-items-center my-5">
                <div class="col-md-9">
                    <div class="w-100 shadow rounded p-3">
                        <div class="row">
                            <div class="col-md-6 p-5">
                                <div class="fw-bold fs-4 mb-2">
                                    Are you new to Bumi Pilates & Movement?
                                </div>
                                <div>
                                    <span class="fw-light">Check out our</span>
                                    <strong>FAQ</strong>
                                </div>
                                <hr class="my-4">
                                <div class="fw-bold fs-5 mb-2">Find a location</div>
                                <div>
                                    <span class="fw-light">To get in touch with our outlets visit our</span>
                                    <strong>location</strong>
                                </div>
                                <hr class="my-4">
                                <div class="fw-bold fs-5 mb-2">
                                    Connect with us on social media
                                </div>
                                <div class="fs-4">
                                    <i class="fa-brands fa-instagram"></i>
                                </div>
                                <hr class="my-4">
                                <div class="fw-bold fs-5 mb-2">Business information</div>
                                <div class="row mb-2">
                                    <div class="col-md-1"><i class="fa-regular fa-envelope"></i></div>
                                    <div class="col-md fw-light">
                                        bumistudiojakarta@gmail.com
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-1"><i class="fa-regular fa-envelope"></i></div>
                                    <div class="col-md fw-light">
                                        Jl. Bumi No. 4, Kebayoran Baru, Jakarta Selatan, 12120, , Jakarta Selatan, DKI
                                        Jakarta 12120
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 p-5">
                                <div class="fw-bold mb-3">
                                    What can we help you with?
                                </div>
                                <form>
                                    <div class="mb-3">
                                        <label for="inputNama" class="form-label fw-light">Nama</label>
                                        <input type="text" name="name" class="form-control" id="inputNama"
                                            aria-describedby="nama" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="inputEmail" class="form-label fw-light">Email Address</label>
                                        <input type="text" name="email" class="form-control" id="inputEmail"
                                            aria-describedby="email" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="inputMobileNumber" class="form-label fw-light">Mobile Number</label>
                                        <input type="number" name="no_hp" class="form-control" id="inputMobileNumber"
                                            required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="subject" class="form-label fw-light">Subject</label>
                                        <select name="subject" class="form-control" id="subject">
                                            <option value="General">General</option>
                                            <option value="Others">Others</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="description" class="form-label fw-light">Mobile Number</label>
                                        <textarea name="description" class="form-control" id="description"></textarea>
                                    </div>
                                    <div class="text-center d-grid gap-2 mt-4">
                                        <button type="submit" class="btn custom-btn w-100">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
