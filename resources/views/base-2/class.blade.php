@extends('base-2.layout')

@section('content')
    <section class="my-4">
        <div class="container">
            <div class="row my-4">
                <div class="col-md-12">
                    <h3 class="fw-bold">Classes</h3>
                </div>
            </div>
            <ul class="nav nav-underline d-none d-sm-flex">
                <li class="nav-item">
                    <a class="nav-link active custom-nav-link px-3" aria-current="page" href="#">All Classes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3" href="#">Group Classes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3" href="#">Private Classes</a>
                </li>
            </ul>

            <div class="row d-sm-none my-3 align-items-center justify-content-center">
                <div class="dropdown col-10">
                    <button class="btn btn-custom-filter btn-block dropdown-toggle shadow-sm border w-100" type="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        All Class Types
                    </button>
                    <ul class="dropdown-menu w-100">
                        <li><a class="dropdown-item" href="#">All Class Type</a></li>
                        <li><a class="dropdown-item" href="#">Online</a></li>
                        <li><a class="dropdown-item" href="#">Onsite</a></li>
                    </ul>
                </div>
                <div class="col-2">
                    <a href="#" id="btn-filter">
                        <i class="fa-solid fa-sliders fs-4 text-dark"></i>
                    </a>
                </div>
            </div>

            <div class="row d-none d-sm-flex justify-content-end my-5">
                <div class="dropdown mx-2 col-md-2 col-lg-2 col-sm-6 ">
                    <button class="btn btn-custom-filter dropdown-toggle shadow-sm border" type="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        All Class Types
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">All Class Type</a></li>
                        <li><a class="dropdown-item" href="#">Online</a></li>
                        <li><a class="dropdown-item" href="#">Onsite</a></li>
                    </ul>
                </div>
                <div class="dropdown mx-2 col-md-2 col-lg-2 col-sm-5">
                    <button class="btn btn-custom-filter dropdown-toggle shadow-sm border" type="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        All Class Types
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">All dificulty levels</a></li>
                        <li><a class="dropdown-item" href="#">Intermediate</a></li>
                        <li><a class="dropdown-item" href="#">Beginer</a></li>
                        <li><a class="dropdown-item" href="#">Advanced</a></li>
                        <li><a class="dropdown-item" href="#">Open to all</a></li>
                    </ul>
                </div>
            </div>
            <div class="row mt-5 mb-5">
                <div class="col-lg-4 col-md-6 mb-5">
                    <div class="custom-card-classes">
                        <div class="w-100 bg-dark image-custom-card"
                            style="background-image: url(https://www.bcalife.co.id/storage/articles/share/bagus-untuk-kesehatan-simak-5-manfaat-olahraga-bagi-tubuh-ini-pantang-diabaikan-1653392364.jpg);">
                        </div>
                        <div class="px-4 py-4">
                            <h5 class="fw-bold">Reformer Flow</h5>
                            <div class="d-flex justify-content-start align-items-center my-3">
                                <small class="me-2"><i class="fa-solid fa-star text-warning"></i><i
                                        class="fa-solid fa-star text-warning"></i><i
                                        class="fa-solid fa-star text-warning"></i><i
                                        class="fa-solid fa-star text-warning"></i><i
                                        class="fa-solid fa-star text-warning"></i></small>
                                <small class="ms-2 px-2 py-2" style="border: 1px solid grey; border-radius: 20px">9
                                    Review</small>
                            </div>
                            <div class="d-flex align-items-center justify-content-start my-2">
                                <div class="me-2">
                                    <small class="fw-bold">
                                        <i class="fa-solid fa-clock-rotate-left"></i>
                                        55 Mins</small>
                                </div>
                                <div class="ms-2">
                                    <small class="fw-bold"><i class="fa-brands fa-creative-commons-nd"></i>Open to
                                        all</small>
                                </div>
                            </div>
                            <div class="my-2">
                                <p>New to Pilates? Let's get down to the nitty-gritty. Learn the basics through controlled
                                    and precise movements. Contact our admin to learn more.</p>
                            </div>
                            <div>
                                <button class="btn custom-btn">View More</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-5">
                    <div class="custom-card-classes">
                        <div class="w-100 bg-dark image-custom-card"
                            style="background-image: url(https://www.bcalife.co.id/storage/articles/share/bagus-untuk-kesehatan-simak-5-manfaat-olahraga-bagi-tubuh-ini-pantang-diabaikan-1653392364.jpg);">
                        </div>
                        <div class="px-4 py-4">
                            <h5 class="fw-bold">Reformer Flow</h5>
                            <div class="d-flex justify-content-start align-items-center my-3">
                                <small class="me-2"><i class="fa-solid fa-star text-warning"></i><i
                                        class="fa-solid fa-star text-warning"></i><i
                                        class="fa-solid fa-star text-warning"></i><i
                                        class="fa-solid fa-star text-warning"></i><i
                                        class="fa-solid fa-star text-warning"></i></small>
                                <small class="ms-2 px-2 py-2" style="border: 1px solid grey; border-radius: 20px">9
                                    Review</small>
                            </div>
                            <div class="d-flex align-items-center justify-content-start my-2">
                                <div class="me-2">
                                    <small class="fw-bold">
                                        <i class="fa-solid fa-clock-rotate-left"></i>
                                        55 Mins</small>
                                </div>
                                <div class="ms-2">
                                    <small class="fw-bold"><i class="fa-brands fa-creative-commons-nd"></i>Open to
                                        all</small>
                                </div>
                            </div>
                            <div class="my-2">
                                <p>New to Pilates? Let's get down to the nitty-gritty. Learn the basics through controlled
                                    and precise movements. Contact our admin to learn more.</p>
                            </div>
                            <div>
                                <button class="btn custom-btn">View More</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-5">
                    <div class="custom-card-classes">
                        <div class="w-100 bg-dark image-custom-card"
                            style="background-image: url(https://www.bcalife.co.id/storage/articles/share/bagus-untuk-kesehatan-simak-5-manfaat-olahraga-bagi-tubuh-ini-pantang-diabaikan-1653392364.jpg);">
                        </div>
                        <div class="px-4 py-4">
                            <h5 class="fw-bold">Reformer Flow</h5>
                            <div class="d-flex justify-content-start align-items-center my-3">
                                <small class="me-2"><i class="fa-solid fa-star text-warning"></i><i
                                        class="fa-solid fa-star text-warning"></i><i
                                        class="fa-solid fa-star text-warning"></i><i
                                        class="fa-solid fa-star text-warning"></i><i
                                        class="fa-solid fa-star text-warning"></i></small>
                                <small class="ms-2 px-2 py-2" style="border: 1px solid grey; border-radius: 20px">9
                                    Review</small>
                            </div>
                            <div class="d-flex align-items-center justify-content-start my-2">
                                <div class="me-2">
                                    <small class="fw-bold">
                                        <i class="fa-solid fa-clock-rotate-left"></i>
                                        55 Mins</small>
                                </div>
                                <div class="ms-2">
                                    <small class="fw-bold"><i class="fa-brands fa-creative-commons-nd"></i>Open to
                                        all</small>
                                </div>
                            </div>
                            <div class="my-2">
                                <p>New to Pilates? Let's get down to the nitty-gritty. Learn the basics through controlled
                                    and precise movements. Contact our admin to learn more.</p>
                            </div>
                            <div>
                                <button class="btn custom-btn">View More</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-5">
                    <div class="custom-card-classes">
                        <div class="w-100 bg-dark image-custom-card"
                            style="background-image: url(https://www.bcalife.co.id/storage/articles/share/bagus-untuk-kesehatan-simak-5-manfaat-olahraga-bagi-tubuh-ini-pantang-diabaikan-1653392364.jpg);">
                        </div>
                        <div class="px-4 py-4">
                            <h5 class="fw-bold">Reformer Flow</h5>
                            <div class="d-flex justify-content-start align-items-center my-3">
                                <small class="me-2"><i class="fa-solid fa-star text-warning"></i><i
                                        class="fa-solid fa-star text-warning"></i><i
                                        class="fa-solid fa-star text-warning"></i><i
                                        class="fa-solid fa-star text-warning"></i><i
                                        class="fa-solid fa-star text-warning"></i></small>
                                <small class="ms-2 px-2 py-2" style="border: 1px solid grey; border-radius: 20px">9
                                    Review</small>
                            </div>
                            <div class="d-flex align-items-center justify-content-start my-2">
                                <div class="me-2">
                                    <small class="fw-bold">
                                        <i class="fa-solid fa-clock-rotate-left"></i>
                                        55 Mins</small>
                                </div>
                                <div class="ms-2">
                                    <small class="fw-bold"><i class="fa-brands fa-creative-commons-nd"></i>Open to
                                        all</small>
                                </div>
                            </div>
                            <div class="my-2">
                                <p>New to Pilates? Let's get down to the nitty-gritty. Learn the basics through controlled
                                    and precise movements. Contact our admin to learn more.</p>
                            </div>
                            <div>
                                <button class="btn custom-btn">View More</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-5">
                    <div class="custom-card-classes">
                        <div class="w-100 bg-dark image-custom-card"
                            style="background-image: url(https://www.bcalife.co.id/storage/articles/share/bagus-untuk-kesehatan-simak-5-manfaat-olahraga-bagi-tubuh-ini-pantang-diabaikan-1653392364.jpg);">
                        </div>
                        <div class="px-4 py-4">
                            <h5 class="fw-bold">Reformer Flow</h5>
                            <div class="d-flex justify-content-start align-items-center my-3">
                                <small class="me-2"><i class="fa-solid fa-star text-warning"></i><i
                                        class="fa-solid fa-star text-warning"></i><i
                                        class="fa-solid fa-star text-warning"></i><i
                                        class="fa-solid fa-star text-warning"></i><i
                                        class="fa-solid fa-star text-warning"></i></small>
                                <small class="ms-2 px-2 py-2" style="border: 1px solid grey; border-radius: 20px">9
                                    Review</small>
                            </div>
                            <div class="d-flex align-items-center justify-content-start my-2">
                                <div class="me-2">
                                    <small class="fw-bold">
                                        <i class="fa-solid fa-clock-rotate-left"></i>
                                        55 Mins</small>
                                </div>
                                <div class="ms-2">
                                    <small class="fw-bold"><i class="fa-brands fa-creative-commons-nd"></i>Open to
                                        all</small>
                                </div>
                            </div>
                            <div class="my-2">
                                <p>New to Pilates? Let's get down to the nitty-gritty. Learn the basics through controlled
                                    and precise movements. Contact our admin to learn more.</p>
                            </div>
                            <div>
                                <button class="btn custom-btn">View More</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-5">
                    <div class="custom-card-classes">
                        <div class="w-100 bg-dark image-custom-card"
                            style="background-image: url(https://www.bcalife.co.id/storage/articles/share/bagus-untuk-kesehatan-simak-5-manfaat-olahraga-bagi-tubuh-ini-pantang-diabaikan-1653392364.jpg);">
                        </div>
                        <div class="px-4 py-4">
                            <h5 class="fw-bold">Reformer Flow</h5>
                            <div class="d-flex justify-content-start align-items-center my-3">
                                <small class="me-2"><i class="fa-solid fa-star text-warning"></i><i
                                        class="fa-solid fa-star text-warning"></i><i
                                        class="fa-solid fa-star text-warning"></i><i
                                        class="fa-solid fa-star text-warning"></i><i
                                        class="fa-solid fa-star text-warning"></i></small>
                                <small class="ms-2 px-2 py-2" style="border: 1px solid grey; border-radius: 20px">9
                                    Review</small>
                            </div>
                            <div class="d-flex align-items-center justify-content-start my-2">
                                <div class="me-2">
                                    <small class="fw-bold">
                                        <i class="fa-solid fa-clock-rotate-left"></i>
                                        55 Mins</small>
                                </div>
                                <div class="ms-2">
                                    <small class="fw-bold"><i class="fa-brands fa-creative-commons-nd"></i>Open to
                                        all</small>
                                </div>
                            </div>
                            <div class="my-2">
                                <p>New to Pilates? Let's get down to the nitty-gritty. Learn the basics through controlled
                                    and precise movements. Contact our admin to learn more.</p>
                            </div>
                            <div>
                                <button class="btn custom-btn">View More</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-5">
                    <div class="custom-card-classes">
                        <div class="w-100 bg-dark image-custom-card"
                            style="background-image: url(https://www.bcalife.co.id/storage/articles/share/bagus-untuk-kesehatan-simak-5-manfaat-olahraga-bagi-tubuh-ini-pantang-diabaikan-1653392364.jpg);">
                        </div>
                        <div class="px-4 py-4">
                            <h5 class="fw-bold">Reformer Flow</h5>
                            <div class="d-flex justify-content-start align-items-center my-3">
                                <small class="me-2"><i class="fa-solid fa-star text-warning"></i><i
                                        class="fa-solid fa-star text-warning"></i><i
                                        class="fa-solid fa-star text-warning"></i><i
                                        class="fa-solid fa-star text-warning"></i><i
                                        class="fa-solid fa-star text-warning"></i></small>
                                <small class="ms-2 px-2 py-2" style="border: 1px solid grey; border-radius: 20px">9
                                    Review</small>
                            </div>
                            <div class="d-flex align-items-center justify-content-start my-2">
                                <div class="me-2">
                                    <small class="fw-bold">
                                        <i class="fa-solid fa-clock-rotate-left"></i>
                                        55 Mins</small>
                                </div>
                                <div class="ms-2">
                                    <small class="fw-bold"><i class="fa-brands fa-creative-commons-nd"></i>Open to
                                        all</small>
                                </div>
                            </div>
                            <div class="my-2">
                                <p>New to Pilates? Let's get down to the nitty-gritty. Learn the basics through controlled
                                    and precise movements. Contact our admin to learn more.</p>
                            </div>
                            <div>
                                <button class="btn custom-btn">View More</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="modal_filter" tabindex="-1" aria-labelledby="modalFilter" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row justify-content-between align-items-center">
                        <div class="col-10">
                            <h2 class="modal-title fw-bold" id="modalFilter">Filters</h2>
                        </div>
                        <div class="col-2">
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                    </div>
                    <h5 class="fw-bold my-3">All Class Types</h5>
                    <div class="my-4 d-flex align-items-center justify-content-between">
                        <label class="form-check-label" for="all_class_types">
                            All class types
                        </label>
                        <input class="form-check-input" type="radio" name="types" id="all_class_types"
                            value="option1" checked>
                    </div>
                    <div class="my-4 d-flex align-items-center justify-content-between">
                        <label class="form-check-label" for="online">
                            Online
                        </label>
                        <input class="form-check-input" type="radio" name="types" id="online" value="option2">

                    </div>
                    <div class="my-4 d-flex align-items-center justify-content-between">
                        <label class="form-check-label" for="onsite">
                            Onsite
                        </label>
                        <input class="form-check-input" type="radio" name="types" id="onsite" value="option3">
                    </div>
                    <hr>
                    <h5 class="fw-bold my-3">All Difficulty Levels</h5>
                    <div class="my-4 d-flex align-items-center justify-content-between">
                        <label class="form-check-label" for="all_dificulty_levels">
                            All dificulty levels
                        </label>
                        <input class="form-check-input" type="radio" name="levels" id="all_dificulty_levels"
                            value="option1" checked>
                    </div>
                    <div class="my-4 d-flex align-items-center justify-content-between">
                        <label class="form-check-label" for="intermediate">
                            Intermediate
                        </label>
                        <input class="form-check-input" type="radio" name="levels" id="intermediate"
                            value="option2">

                    </div>
                    <div class="my-4 d-flex align-items-center justify-content-between">
                        <label class="form-check-label" for="beginer">
                            Beginer
                        </label>
                        <input class="form-check-input" type="radio" name="levels" id="beginer" value="option3">
                    </div>
                    <div class="my-4 d-flex align-items-center justify-content-between">
                        <label class="form-check-label" for="advanced">
                            Advanced
                        </label>
                        <input class="form-check-input" type="radio" name="levels" id="advanced" value="option3">
                    </div>
                    <div class="my-4 d-flex align-items-center justify-content-between">
                        <label class="form-check-label" for="open_to_all">
                            Open to all
                        </label>
                        <input class="form-check-input" type="radio" name="levels" id="open_to_all" value="option3">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function() {});

        $('#btn-filter').on('click', function() {
            $('#modal_filter').modal('show')
        })
    </script>
@endsection
