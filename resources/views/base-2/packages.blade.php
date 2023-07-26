@extends('base-2.layout')

@section('content')
    <section class="my-4">
        <div class="container">
            <div class="row my-3">
                <div class="col-md-12">
                    <h3 class="fw-bold">Packages</h3>
                </div>
            </div>
            <ul class="nav nav-underline d-none d-sm-flex">
                <li class="nav-item">
                    <a class="nav-link active custom-nav-link px-3" aria-current="page" href="#">All</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3" href="#">Group Class</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3" href="#">Private Class</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3" href="#">Private Duet</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3" href="#">Private Group</a>
                </li>
            </ul>
            <div class="row d-sm-none my-3 align-items-center justify-content-center">
                <div class="dropdown col-12">
                    <button class="btn btn-custom-filter btn-block dropdown-toggle shadow-sm border w-100" type="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        All
                    </button>
                    <ul class="dropdown-menu w-100">
                        <li><a class="dropdown-item" href="#">All</a></li>
                        <li><a class="dropdown-item" href="#">Group Class</a></li>
                        <li><a class="dropdown-item" href="#">Private Class</a></li>
                        <li><a class="dropdown-item" href="#">Private Duet</a></li>
                        <li><a class="dropdown-item" href="#">Private Group</a></li>
                    </ul>
                </div>
            </div>
            <div class="row my-5">
                <div class="col-md-3">
                    <div class="custom-card-package p-3 mb-4">
                        <div class="mb-3">
                            <small class="fw-semibold text-uppercase" style="color: #b4b4b4">Group Class</small>
                        </div>
                        <div class="h5 fw-bold mt-3">Intro Offer (3 Classes)</div>
                        <div class="mb-4">
                            <small class="fw-bold">Rp.500.000</small>
                        </div>
                        <div class="p-3 mb-4 card-child" style="">
                            <small class="d-block mb-2 fw-light">
                                <span class="me-2"><i class="fa-solid fa-clock-rotate-left" style="color: grey"></i>
                                </span>
                                <span>3
                                    Credits</span>
                            </small>
                            <small class="d-block mb-2 fw-light">
                                <span class="me-2"><i class="fa-solid fa-clock-rotate-left" style="color: grey"></i>
                                </span>
                                <span>All
                                    Location Access</span>
                            </small>
                            <small class="d-block mb-2 fw-light">
                                <span class="me-2"><i class="fa-solid fa-clock-rotate-left" style="color: grey"></i>
                                </span>
                                <span>Valid For 14 days from date
                                    of first booking
                                </span>
                            </small>
                        </div>
                        <div class="text-center d-grid gap-2">
                            <a href="{{ route('detail-packages', ['id' => 1]) }}" class="btn custom-btn">Buy Now</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="custom-card-package p-3 mb-4">
                        <div class="mb-3">
                            <small class="fw-semibold text-uppercase" style="color: #b4b4b4">Group Class</small>
                        </div>
                        <div class="h5 fw-bold mt-3">Intro Offer (3 Classes)</div>
                        <div class="mb-4">
                            <small class="fw-bold">Rp.500.000</small>
                        </div>
                        <div class="p-3 mb-4 card-child" style="">
                            <small class="d-block mb-2 fw-light">
                                <span class="me-2"><i class="fa-solid fa-clock-rotate-left" style="color: grey"></i>
                                </span>
                                <span>3
                                    Credits</span>
                            </small>
                            <small class="d-block mb-2 fw-light">
                                <span class="me-2"><i class="fa-solid fa-clock-rotate-left" style="color: grey"></i>
                                </span>
                                <span>All
                                    Location Access</span>
                            </small>
                            <small class="d-block mb-2 fw-light">
                                <span class="me-2"><i class="fa-solid fa-clock-rotate-left" style="color: grey"></i>
                                </span>
                                <span>Valid For 14 days from date
                                    of first booking
                                </span>
                            </small>
                        </div>
                        <div class="text-center d-grid gap-2">
                            <button class="btn custom-btn">Buy Now</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="custom-card-package p-3 mb-4">
                        <div class="mb-3">
                            <small class="fw-semibold text-uppercase" style="color: #b4b4b4">Group Class</small>
                        </div>
                        <div class="h5 fw-bold mt-3">Intro Offer (3 Classes)</div>
                        <div class="mb-4">
                            <small class="fw-bold">Rp.500.000</small>
                        </div>
                        <div class="p-3 mb-4 card-child" style="">
                            <small class="d-block mb-2 fw-light">
                                <span class="me-2"><i class="fa-solid fa-clock-rotate-left" style="color: grey"></i>
                                </span>
                                <span>3
                                    Credits</span>
                            </small>
                            <small class="d-block mb-2 fw-light">
                                <span class="me-2"><i class="fa-solid fa-clock-rotate-left" style="color: grey"></i>
                                </span>
                                <span>All
                                    Location Access</span>
                            </small>
                            <small class="d-block mb-2 fw-light">
                                <span class="me-2"><i class="fa-solid fa-clock-rotate-left" style="color: grey"></i>
                                </span>
                                <span>Valid For 14 days from date
                                    of first booking
                                </span>
                            </small>
                        </div>
                        <div class="text-center d-grid gap-2">
                            <button class="btn custom-btn">Buy Now</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="custom-card-package p-3 mb-4">
                        <div class="mb-3">
                            <small class="fw-semibold text-uppercase" style="color: #b4b4b4">Group Class</small>
                        </div>
                        <div class="h5 fw-bold mt-3">Intro Offer (3 Classes)</div>
                        <div class="mb-4">
                            <small class="fw-bold">Rp.500.000</small>
                        </div>
                        <div class="p-3 mb-4 card-child" style="">
                            <small class="d-block mb-2 fw-light">
                                <span class="me-2"><i class="fa-solid fa-clock-rotate-left" style="color: grey"></i>
                                </span>
                                <span>3
                                    Credits</span>
                            </small>
                            <small class="d-block mb-2 fw-light">
                                <span class="me-2"><i class="fa-solid fa-clock-rotate-left" style="color: grey"></i>
                                </span>
                                <span>All
                                    Location Access</span>
                            </small>
                            <small class="d-block mb-2 fw-light">
                                <span class="me-2"><i class="fa-solid fa-clock-rotate-left" style="color: grey"></i>
                                </span>
                                <span>Valid For 14 days from date
                                    of first booking
                                </span>
                            </small>
                        </div>
                        <div class="text-center d-grid gap-2">
                            <button class="btn custom-btn">Buy Now</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="custom-card-package p-3 mb-4">
                        <div class="mb-3">
                            <small class="fw-semibold text-uppercase" style="color: #b4b4b4">Group Class</small>
                        </div>
                        <div class="h5 fw-bold mt-3">Intro Offer (3 Classes)</div>
                        <div class="mb-4">
                            <small class="fw-bold">Rp.500.000</small>
                        </div>
                        <div class="p-3 mb-4 card-child" style="">
                            <small class="d-block mb-2 fw-light">
                                <span class="me-2"><i class="fa-solid fa-clock-rotate-left" style="color: grey"></i>
                                </span>
                                <span>3
                                    Credits</span>
                            </small>
                            <small class="d-block mb-2 fw-light">
                                <span class="me-2"><i class="fa-solid fa-clock-rotate-left" style="color: grey"></i>
                                </span>
                                <span>All
                                    Location Access</span>
                            </small>
                            <small class="d-block mb-2 fw-light">
                                <span class="me-2"><i class="fa-solid fa-clock-rotate-left" style="color: grey"></i>
                                </span>
                                <span>Valid For 14 days from date
                                    of first booking
                                </span>
                            </small>
                        </div>
                        <div class="text-center d-grid gap-2">
                            <button class="btn custom-btn">Buy Now</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="custom-card-package p-3 mb-4">
                        <div class="mb-3">
                            <small class="fw-semibold text-uppercase" style="color: #b4b4b4">Group Class</small>
                        </div>
                        <div class="h5 fw-bold mt-3">Intro Offer (3 Classes)</div>
                        <div class="mb-4">
                            <small class="fw-bold">Rp.500.000</small>
                        </div>
                        <div class="p-3 mb-4 card-child" style="">
                            <small class="d-block mb-2 fw-light">
                                <span class="me-2"><i class="fa-solid fa-clock-rotate-left" style="color: grey"></i>
                                </span>
                                <span>3
                                    Credits</span>
                            </small>
                            <small class="d-block mb-2 fw-light">
                                <span class="me-2"><i class="fa-solid fa-clock-rotate-left" style="color: grey"></i>
                                </span>
                                <span>All
                                    Location Access</span>
                            </small>
                            <small class="d-block mb-2 fw-light">
                                <span class="me-2"><i class="fa-solid fa-clock-rotate-left" style="color: grey"></i>
                                </span>
                                <span>Valid For 14 days from date
                                    of first booking
                                </span>
                            </small>
                        </div>
                        <div class="text-center d-grid gap-2">
                            <button class="btn custom-btn">Buy Now</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="custom-card-package p-3 mb-4">
                        <div class="mb-3">
                            <small class="fw-semibold text-uppercase" style="color: #b4b4b4">Group Class</small>
                        </div>
                        <div class="h5 fw-bold mt-3">Intro Offer (3 Classes)</div>
                        <div class="mb-4">
                            <small class="fw-bold">Rp.500.000</small>
                        </div>
                        <div class="p-3 mb-4 card-child" style="">
                            <small class="d-block mb-2 fw-light">
                                <span class="me-2"><i class="fa-solid fa-clock-rotate-left" style="color: grey"></i>
                                </span>
                                <span>3
                                    Credits</span>
                            </small>
                            <small class="d-block mb-2 fw-light">
                                <span class="me-2"><i class="fa-solid fa-clock-rotate-left" style="color: grey"></i>
                                </span>
                                <span>All
                                    Location Access</span>
                            </small>
                            <small class="d-block mb-2 fw-light">
                                <span class="me-2"><i class="fa-solid fa-clock-rotate-left" style="color: grey"></i>
                                </span>
                                <span>Valid For 14 days from date
                                    of first booking
                                </span>
                            </small>
                        </div>
                        <div class="text-center d-grid gap-2">
                            <button class="btn custom-btn">Buy Now</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="custom-card-package p-3 mb-4">
                        <div class="mb-3">
                            <small class="fw-semibold text-uppercase" style="color: #b4b4b4">Group Class</small>
                        </div>
                        <div class="h5 fw-bold mt-3">Intro Offer (3 Classes)</div>
                        <div class="mb-4">
                            <small class="fw-bold">Rp.500.000</small>
                        </div>
                        <div class="p-3 mb-4 card-child" style="">
                            <small class="d-block mb-2 fw-light">
                                <span class="me-2"><i class="fa-solid fa-clock-rotate-left" style="color: grey"></i>
                                </span>
                                <span>3
                                    Credits</span>
                            </small>
                            <small class="d-block mb-2 fw-light">
                                <span class="me-2"><i class="fa-solid fa-clock-rotate-left" style="color: grey"></i>
                                </span>
                                <span>All
                                    Location Access</span>
                            </small>
                            <small class="d-block mb-2 fw-light">
                                <span class="me-2"><i class="fa-solid fa-clock-rotate-left" style="color: grey"></i>
                                </span>
                                <span>Valid For 14 days from date
                                    of first booking
                                </span>
                            </small>
                        </div>
                        <div class="text-center d-grid gap-2">
                            <button class="btn custom-btn">Buy Now</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
