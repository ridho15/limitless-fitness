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
        <div class="shadow-sm bg-white sticky-top py-2" style="top: 70px; z-index: 2 !important;">
            <div class="container my-3">
                @livewire('timetable.tanggal')
            </div>
        </div>
        @livewire('timetable.list-data')
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
