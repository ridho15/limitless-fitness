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
                <li class="nav-item" role="presentation">
                    <a class="nav-link active custom-nav-link px-3" role="tab" data-bs-toggle="tab" href="#all_class">All
                        Classes</a>
                </li>
            </ul>


            <div class="row d-sm-none my-3 align-items-center justify-content-center">
                <div class="dropdown col-10">
                    <button class="btn btn-custom-filter btn-block dropdown-toggle shadow-sm border w-100" type="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        All Class Types
                    </button>
                    <ul class="dropdown-menu w-100">
                        <li><a class="dropdown-item" href="#" onclick="changeClassType(0)">All Class
                                Type</a></li>
                        <li><a class="dropdown-item" href="#" onclick="changeClassType(1)">Online</a>
                        </li>
                        <li><a class="dropdown-item" href="#" onclick="changeClassType(2)">Onsite</a>
                        </li>
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
                        <li><a class="dropdown-item" href="#" onclick="changeClassType(0)">All Class Type</a></li>
                        <li><a class="dropdown-item" href="#" onclick="changeClassType(1)">Online</a>
                        </li>
                        <li><a class="dropdown-item" href="#" onclick="changeClassType(2)">Onsite</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="tab-content">
                <div class="tab-pane fade show active" id="all_class">
                    @livewire('class.all-class')
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
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                    </div>
                    <h5 class="fw-bold my-3">All Class Types</h5>
                    <div class="my-4 d-flex align-items-center justify-content-between">
                        <label class="form-check-label" for="all_class_types">
                            All class types
                        </label>
                        <input class="form-check-input" type="radio" name="types" id="all_class_types"
                            onclick="changeClassType(0)" value="0" checked>
                    </div>
                    <div class="my-4 d-flex align-items-center justify-content-between">
                        <label class="form-check-label" for="online">
                            Online
                        </label>
                        <input class="form-check-input" onclick="changeClassType(1)" type="radio" name="types"
                            id="online" value="1">
                    </div>
                    <div class="my-4 d-flex align-items-center justify-content-between">
                        <label class="form-check-label" for="onsite">
                            Onsite
                        </label>
                        <input class="form-check-input" onclick="changeClassType(2)" type="radio" name="types"
                            id="onsite" value="2">
                    </div>
                    <hr>
                    {{-- <h5 class="fw-bold my-3">All Difficulty Levels</h5>
                    <div class="my-4 d-flex align-items-center justify-content-between">
                        <label class="form-check-label" for="all_dificulty_levels">
                            All dificulty levels
                        </label>
                        <input class="form-check-input" type="radio" name="levels" id="all_dificulty_levels"
                            value="0" onclick="changeClassLevel(0)" checked>
                    </div> --}}
                    {{-- <div class="my-4 d-flex align-items-center justify-content-between">
                        <label class="form-check-label" for="intermediate">
                            Intermediate
                        </label>
                        <input class="form-check-input" type="radio" name="levels" id="intermediate" value="1"
                            onclick="changeClassLevel(1)">

                    </div>
                    <div class="my-4 d-flex align-items-center justify-content-between">
                        <label class="form-check-label" for="beginer">
                            Beginer
                        </label>
                        <input class="form-check-input" type="radio" name="levels" id="beginer" value="2"
                            onclick="changeClassLevel(2)">
                    </div>
                    <div class="my-4 d-flex align-items-center justify-content-between">
                        <label class="form-check-label" for="advanced">
                            Advanced
                        </label>
                        <input class="form-check-input" type="radio" name="levels" id="advanced" value="3"
                            onclick="changeClassLevel(3)">
                    </div>
                    <div class="my-4 d-flex align-items-center justify-content-between">
                        <label class="form-check-label" for="open_to_all">
                            Open to all
                        </label>
                        <input class="form-check-input" type="radio" name="levels" id="open_to_all" value="0"
                            onclick="changeClassLevel(0)">
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function() {});

        $('#btn-filter').on('click', function() {
            // $('#modal_filter').modal('show')
        })

        function changeClassType(type) {
            Livewire.emit('changeTypes', type)
        }

        function changeClassLevel(level) {
            Livewire.emit('changeLevel', level);
        }
    </script>
@endpush
