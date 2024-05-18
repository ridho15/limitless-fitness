@extends('base-2.layout')

@section('content')
    <section class="my-4">
        <div class="container">
            <div class="row mb-3">
                <div class="col-md-8 mb-3">
                    <div class="d-flex mb-3">
                        <small class="fw-light mx-1">Classes</small>
                        <small class="mx-1"><i class="fa-solid fa-chevron-right"></i></small>
                        <small class="fw-bold mx-1">{{ $kelas->nama_kelas }}</small>
                    </div>
                    <h3 class="fw-bold mb-2">{{ $kelas->nama_kelas }}</h3>
                    <div class="row mb-4 justify-content-start align-items-center">
                        <div class="col-md-4 col-6 text-start">
                            <small class="d-block fw-semibold"><i class="fa-solid fa-clock-rotate-left"></i>
                                Duration</small>
                            <small class="d-block fw-light">{{ $kelas->duration }} mins</small>
                        </div>
                    </div>
                    <div class="shadow bg-white p-4" style="border-radius: 15px">
                        <form action="#" method="GET" id="form_book">
                            <div class="row align-items-center">
                                <div class="col-md mb-3">
                                    <select name="location" class="form-control" data-control="select2"
                                        data-placeholder="Pilih" id="location">
                                        <option value="">Pilih</option>
                                        @foreach ($kelas->paket as $item)
                                            <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2 mb-3 text-center">
                                    <button type="submit" class="btn custom-btn w-100">
                                        <small class="fw-light">Book Now</small>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-md-4">
                    <img src="{{ count($kelas->kelasImage) > 0 ? asset('storage/' . $kelas->kelasImage[0]->image) : null }}"
                        class="img-fluid rounded shadow-sm" alt="">
                </div>
            </div>
            <hr class="my-5">
            <div class="row">
                <div class="col-md-6 fw-bold fs-4">
                    About the class
                </div>
                <div class="col-md-6">
                    <small class="fw-light">
                        {{ $kelas->keterangan }}
                    </small>
                </div>
            </div>
            <hr class="my-5">
            <div class="row">
                <div class="col-md-6 fw-bold fs-4">
                    What do you need to bring?
                </div>
                <div class="col-md-6">
                    <small class="fw-light">
                        @php
                            $listBawaan = explode(',', $kelas->bawaan);
                        @endphp

                        <ul>
                            @foreach ($listBawaan as $item)
                                @if ($item != null)
                                    <li>{{ $item }}</li>
                                @endif
                            @endforeach
                        </ul>
                    </small>
                </div>
            </div>
            <hr class="my-5">
        </div>
    </section>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            $('#location').select2({
                theme: 'bootstrap-5'
            });
        });

        $('#location').on('change', function() {
            const id = $(this).val()
            $('#form_book').attr('action', "{{ config('app.url') }}/detail-packages/" + id);
        })
    </script>
@endsection
