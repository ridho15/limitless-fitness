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
    <div class="shadow-sm bg-white sticky-top py-2" style="top: 55px; z-index: 2 !important;">
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
                        wire:click="$emit('onChangeInstruktur', {{ $item->id }})" id="instruktur_{{ $item->id }}"
                        value="{{ $item->id }}">
                </div>
                @endforeach
                <hr>
                <h5 class="fw-bold my-3">Tanggal</h5>
                <div>
                    <input type="date" name="tanggal" class="form-control" id="tanggal"
                        data-dropdown-parent="modal_filter">
                </div>
                <h5 class="fw-bold my-3">Lokasi</h5>
                <div>
                    <select name="id_location" class="form-control" data-control="select2" id="lokasi_timetable">
                        <option value="">Pilih</option>
                        @foreach ($listLocation as $item)
                        <option value="{{ $item->id }}">{{ $item->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <div class="text-end">
                    <button type="button" class="btn btn-sm btn-outline-secondary"
                        style="border-radius: 10px !important" id="btn-close-modal"><i
                            class="fa-regular fa-circle-xmark"></i> Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_repetitif_time" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Set Repetitif Time</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-end">
                    <button class="btn custom-btn my-3" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapse_repetitif_time" aria-expanded="false" aria-controls="collapseExample">
                        Create Data
                    </button>
                </div>
                <div class="collapse" id="collapse_repetitif_time">
                    <div class="card card-body">
                        <form action="{{ route('profile.post-customer-jadwal') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="" class="form-label">Hari</label>
                                    <select class="form-control" data-placeholder="Pilih Hari" name="hari" id=""
                                        required>
                                        <option value="">Pilih Hari</option>
                                        <option value="0">Senin</option>
                                        <option value="1">Selasa</option>
                                        <option value="2">Rabu</option>
                                        <option value="3">Kamis</option>
                                        <option value="4">Jumat</option>
                                        <option value="5">Sabtu</option>
                                        <option value="6">Minggu</option>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="" class="form-label">Jam</label>
                                    <select name="jam" class="form-control" data-placeholder="Pilih Jam" id="jam">
                                        <option value="">Pilih Jam</option>
                                        @for ($i = 1; $i <= 24; $i++) { <option value="{{ sprintf('%02d', $i) }}:00:00">
                                            {{ sprintf('%02d', $i) }}:00:00
                                            </option>
                                            }
                                            @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-md-12 text-end">
                                    <button type="submit" class="btn custom-btn">Simpan</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Hari</th>
                            <th scope="col">Jam</th>
                            <th scope="col" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($listRepetitifTime) > 0)
                        @foreach ($listRepetitifTime as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->hari_formatted }}</td>
                            <td>{{ $item->jam }}</td>
                            <td class="text-center">
                                <div class="btn-group" role="group" aria-label="Basic example">
                                    <button type="button" class="btn btn-danger btn-hapus"
                                        data-id="{{ $item->id }}">Hapus</button>
                                </div>

                                <form action="{{ route('profile.hapus-repetitif-time', ['id' => $item->id]) }}"
                                    method="POST" id="form_hapus_{{ $item->id }}">
                                    @method('DELETE')
                                    @csrf
                                </form>
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="4" class="text-center">Belum set jadwal</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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

        $('.btn-hapus').on('click', async function() {
            const id = $(this).data('id')
            const response = await sweetAlert('warning', 'Peringatan',
                'Apakah kamu ingin menghapus repetitif time ?', 'Ya, Hapus');

            if (response.isConfirmed == true) {
              $('#form_hapus_' + id).submit();
            }
        })

        $('#lokasi_timetable').on('change', function(){
            const id_location = $(this).val();
            Livewire.emit('changeLocation', id_location)
        })

        $('#btn-close-modal').on('click', function(){
            $('#modal_filter').modal('hide')
        })
</script>
@endsection