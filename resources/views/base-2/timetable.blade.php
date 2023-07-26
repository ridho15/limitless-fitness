@extends('base-2.layout')

@section('content')
    <section class="my-4">
        <div class="container">
            <div class="row my-3">
                <div class="col-md-12">
                    <h3 class="fw-bold">Timetable</h3>
                </div>
            </div>
        </div>
        <div class="shadow-sm bg-white sticky-top py-2" style="top: 80px">
            <div class="container my-3">
                <div class="d-flex" style="overflow-x: scroll">
                    @for ($i = 1; $i <= 31; $i++)
                        <div class="card-date shadow-sm mx-1">
                            <div class="text-center">
                                <small class="fw-light">Sun</small>
                            </div>
                            <div class="fw-bold text-center fs-4">{{ $i }}</div>
                        </div>
                    @endfor
                </div>
                <div class="row d-none d-sm-flex justify-content-center">
                    <div class="dropdown col-md-2">
                        <button class="btn btn-custom-filter dropdown-toggle shadow-sm border w-100" type="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            All Categories
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">All Categories</a></li>
                            <li><a class="dropdown-item" href="#">Group Classes</a></li>
                            <li><a class="dropdown-item" href="#">Private Classes</a></li>
                        </ul>
                    </div>
                    <div class="dropdown col-md-2">
                        <button class="btn btn-custom-filter dropdown-toggle shadow-sm border w-100" type="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            Location
                        </button>
                        <div class="w-100 dropdown-menu px-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                <label class="form-check-label" for="flexCheckDefault">
                                    Default checkbox
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                <label class="form-check-label" for="flexCheckDefault">
                                    Default checkbox
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="dropdown col-md-2">
                        <button class="btn btn-custom-filter dropdown-toggle shadow-sm border w-100" type="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            Classes
                        </button>
                        <div class="w-100 dropdown-menu px-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                <label class="form-check-label" for="flexCheckDefault">
                                    Default checkbox
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                <label class="form-check-label" for="flexCheckDefault">
                                    Default checkbox
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="dropdown col-md-2">
                        <button class="btn btn-custom-filter dropdown-toggle shadow-sm border w-100" type="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            Instructor
                        </button>
                        <div class="w-100 dropdown-menu px-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                <label class="form-check-label" for="flexCheckDefault">
                                    Default checkbox
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                <label class="form-check-label" for="flexCheckDefault">
                                    Default checkbox
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="dropdown col-md-2">
                        <button class="btn btn-custom-filter dropdown-toggle shadow-sm border w-100" type="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            Level of difficulty
                        </button>
                        <div class="w-100 dropdown-menu px-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                <label class="form-check-label" for="flexCheckDefault">
                                    Default checkbox
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                <label class="form-check-label" for="flexCheckDefault">
                                    Default checkbox
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row d-sm-none my-3 align-items-center justify-content-center">
                    <div class="dropdown col-10">
                        <button class="btn btn-custom-filter btn-block dropdown-toggle shadow-sm border w-100"
                            type="button" data-bs-toggle="dropdown" aria-expanded="false">
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
            </div>
        </div>
        <div class="container mt-3 mb-5">
            <div class="">
                <span class="fw-semibold fs-5 me-2">Today, Wed 26 Jul</span>
                <small class="fw-light text-secondary">3 Classes</small>
            </div>
            <div class="row align-items-center mt-3">
                <div class="col-md-1 text-center">
                    <div class="fw-semibold text-center">8:00 AM</div>
                    <small class="fw-light text-secondary">55 mins</small>
                </div>
                <div class="col-md-11">
                    <div class="shadow bg-white py-3 px-5" style="border-radius: 15px">
                        <div class="row justify-content-center align-items-center">
                            <div class="col-md-3">
                                <span class="fw-bold d-block">Reformer Flow </span>
                                <small class="fw-light d-block">Nabila Sofyan</small>
                            </div>
                            <div class="col-md-3">
                                <span class="fw-bold d-block">Bumi Studio Jakarta</span>
                                <small class="fw-light d-block">Studio 2</small>
                            </div>
                            <div class="col-md-3 text-center">
                                <small class="text-secondary fw-bold">1 in waitlist</small>
                            </div>
                            <div class="col-md-3 text-end">
                                <button class="btn custom-btn" style="padding-left: 50px; padding-right: 50px">Join <br> Waitlist</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row align-items-center mt-3">
                <div class="col-md-1 text-center">
                    <div class="fw-semibold text-center">8:00 AM</div>
                    <small class="fw-light text-secondary">55 mins</small>
                </div>
                <div class="col-md-11">
                    <div class="shadow bg-white py-3 px-5" style="border-radius: 15px">
                        <div class="row justify-content-center align-items-center">
                            <div class="col-md-3">
                                <span class="fw-bold d-block">Reformer Flow </span>
                                <small class="fw-light d-block">Nabila Sofyan</small>
                            </div>
                            <div class="col-md-3">
                                <span class="fw-bold d-block">Bumi Studio Jakarta</span>
                                <small class="fw-light d-block">Studio 2</small>
                            </div>
                            <div class="col-md-3 text-center">
                                <small class="text-secondary fw-bold">1 in waitlist</small>
                            </div>
                            <div class="col-md-3 text-end">
                                <button class="btn custom-btn" style="padding-left: 50px; padding-right: 50px">Join <br> Waitlist</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row align-items-center mt-3">
                <div class="col-md-1 text-center">
                    <div class="fw-semibold text-center">8:00 AM</div>
                    <small class="fw-light text-secondary">55 mins</small>
                </div>
                <div class="col-md-11">
                    <div class="shadow bg-white py-3 px-5" style="border-radius: 15px">
                        <div class="row justify-content-center align-items-center">
                            <div class="col-md-3">
                                <span class="fw-bold d-block">Reformer Flow </span>
                                <small class="fw-light d-block">Nabila Sofyan</small>
                            </div>
                            <div class="col-md-3">
                                <span class="fw-bold d-block">Bumi Studio Jakarta</span>
                                <small class="fw-light d-block">Studio 2</small>
                            </div>
                            <div class="col-md-3 text-center">
                                <small class="text-secondary fw-bold">1 in waitlist</small>
                            </div>
                            <div class="col-md-3 text-end">
                                <button class="btn custom-btn" style="padding-left: 50px; padding-right: 50px">Join <br> Waitlist</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row align-items-center mt-3">
                <div class="col-md-1 text-center">
                    <div class="fw-semibold text-center">8:00 AM</div>
                    <small class="fw-light text-secondary">55 mins</small>
                </div>
                <div class="col-md-11">
                    <div class="shadow bg-white py-3 px-5" style="border-radius: 15px">
                        <div class="row justify-content-center align-items-center">
                            <div class="col-md-3">
                                <span class="fw-bold d-block">Reformer Flow </span>
                                <small class="fw-light d-block">Nabila Sofyan</small>
                            </div>
                            <div class="col-md-3">
                                <span class="fw-bold d-block">Bumi Studio Jakarta</span>
                                <small class="fw-light d-block">Studio 2</small>
                            </div>
                            <div class="col-md-3 text-center">
                                <small class="text-secondary fw-bold">1 in waitlist</small>
                            </div>
                            <div class="col-md-3 text-end">
                                <button class="btn custom-btn" style="padding-left: 50px; padding-right: 50px">Join <br> Waitlist</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row align-items-center mt-3">
                <div class="col-md-1 text-center">
                    <div class="fw-semibold text-center">8:00 AM</div>
                    <small class="fw-light text-secondary">55 mins</small>
                </div>
                <div class="col-md-11">
                    <div class="shadow bg-white py-3 px-5" style="border-radius: 15px">
                        <div class="row justify-content-center align-items-center">
                            <div class="col-md-3">
                                <span class="fw-bold d-block">Reformer Flow </span>
                                <small class="fw-light d-block">Nabila Sofyan</small>
                            </div>
                            <div class="col-md-3">
                                <span class="fw-bold d-block">Bumi Studio Jakarta</span>
                                <small class="fw-light d-block">Studio 2</small>
                            </div>
                            <div class="col-md-3 text-center">
                                <small class="text-secondary fw-bold">1 in waitlist</small>
                            </div>
                            <div class="col-md-3 text-end">
                                <button class="btn custom-btn" style="padding-left: 50px; padding-right: 50px">Join <br> Waitlist</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row align-items-center mt-3">
                <div class="col-md-1 text-center">
                    <div class="fw-semibold text-center">8:00 AM</div>
                    <small class="fw-light text-secondary">55 mins</small>
                </div>
                <div class="col-md-11">
                    <div class="shadow bg-white py-3 px-5" style="border-radius: 15px">
                        <div class="row justify-content-center align-items-center">
                            <div class="col-md-3">
                                <span class="fw-bold d-block">Reformer Flow </span>
                                <small class="fw-light d-block">Nabila Sofyan</small>
                            </div>
                            <div class="col-md-3">
                                <span class="fw-bold d-block">Bumi Studio Jakarta</span>
                                <small class="fw-light d-block">Studio 2</small>
                            </div>
                            <div class="col-md-3 text-center">
                                <small class="text-secondary fw-bold">1 in waitlist</small>
                            </div>
                            <div class="col-md-3 text-end">
                                <button class="btn custom-btn" style="padding-left: 50px; padding-right: 50px">Join <br> Waitlist</button>
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
                <div class="modal-footer">
                    <button>Testing</button>
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
