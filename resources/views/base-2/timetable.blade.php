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
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                    </div>
                    <h5 class="fw-bold my-3">Instruktur</h5>
                    <div class="my-4 d-flex align-items-center justify-content-between">
                        <label class="form-check-label" for="all_class_types">
                            All Instructor
                        </label>
                        <input class="form-check-input" type="radio" name="instruktur" id="all_class_types" value="0"
                            checked>
                    </div>
                    @foreach ($listInstruktur as $item)
                        <div class="my-4 d-flex align-items-center justify-content-between">
                            <label class="form-check-label" for="instruktur_{{ $item->id }}">
                                {{ $item->name }}
                            </label>
                            <input class="form-check-input" type="radio" name="instruktur"
                                wire:click="$emit('onChangeInstruktur', {{ $item->id }})"
                                id="instruktur_{{ $item->id }}" value="{{ $item->id }}">
                        </div>
                    @endforeach
                    <hr>
                    <h5 class="fw-bold my-3">Tanggal</h5>
                    <div>
                        <input type="date" name="tanggal" class="form-control" id="tanggal"
                            data-dropdown-parent="modal_filter">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            console.log(Modernizr);
        });

        $('#btn-filter').on('click', function() {
            $('#modal_filter').modal('show')
        })

        $('input[name="instruktur"]').on('change', function() {
            Livewire.emit('changeInstruktur', $(this).val())
        })

        $(function() {
            if (!Modernizr.inputtypes.date) {
                $('input[type=date]').datepicker({
                    dateFormat: 'yy-mm-dd'
                });
            }
        });
    </script>
@endsection
