@extends('base-2.layout')

@section('content')
<section class="my-4">
    <div class="container">
        <div class="row my-4">
            <div class="col-md-12">
                <h3 class="fw-bold">Classes</h3>
            </div>
        </div>
        <ul class="nav nav-underline d-none d-sm-flex justify-content-between">
            <li class="nav-item" role="presentation">
                <a class="nav-link active custom-nav-link px-3" role="tab" data-bs-toggle="tab" href="#all_class">All
                    Classes</a>
            </li>
            <li>
                <button type="button" id="btn_repetitif_time" wire:click="$emit('onClickGantiLokasi')"
                    class="btn custom-btn w-100">Ganti Lokasi</button>
            </li>
        </ul>
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
                <hr>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    
    $(document).ready(function() {
        
    });

        $('#btn-filter').on('click', function() {
            // $('#modal_filter').modal('show')
        })

        function changeClassType(type) {
            Livewire.emit('changeTypes', type)
        }

        function changeClassLevel(level) {
            Livewire.emit('changeLevel', level);
        }

        $('#btn_repetitif_time').on('click', function(){
            $('#modal_location_studio').modal('show')
        })
</script>
@endpush