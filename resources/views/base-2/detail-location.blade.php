@extends('base-2.layout')

@section('content')
    <section class="my-4">
        <div class="container">
            <div class="d-flex mt-4 mb-3">
                <small class="fw-light mx-1">Locate Us</small>
                <small class="mx-1"><i class="fa-solid fa-chevron-right"></i></small>
                <small class="fw-bold mx-1">{{ $location->nama }}</small>
            </div>
            <h3 class="fw-bold mb-4">{{ $location->nama }}</h3>
            <div class="row">
                <div class="col-md-8 mb-4">
                    <div class="bg-white w-100 shadow-sm p-5" style="border-radius: 15px">
                        <img src="{{ asset('storage/' . $location->image) }}" class="img-fluid rounded" alt="">
                    </div>
                    <hr class="my-5">
                    <h3 class="fw-bold mb-4">Facilities</h3>
                    @foreach ($location->locationStudio as $item)
                        <div class="row align-items-center mb-3 bg-white rounded shadow-sm">
                            <div class="col-md mb-3">
                                <img src="{{ asset('storage/' . $item->image) }}" class="img-fluid" alt=""
                                    style="height: 150px; width: 100%; border-radius: 15px; object-fit: cover">
                            </div>
                            <div class="col-md-9 mb-3">
                                <div class="fw-semibold mb-2">{{ $item->nama }}</div>
                                <div class="d-flex align-items-center mb-2">
                                    <div class="text-center me-4">
                                        <small class="fw-light"><i class="fa-solid fa-sliders"></i></small>
                                        <small class="fw-light">Level {{ $item->level }}</small>
                                    </div>
                                    <div class="text-center me-2">
                                        <small class="fw-light"><i class="fa-solid fa-people-group"></i></small>
                                        <small class="fw-light">Up to {{ $item->max_peserta }} Pax</small>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <small class="fw-light">Equipment : </small>
                                    <ul class="d-flex flex-wrap">
                                        @php
                                            $equipment = explode(',', $item->equipment);
                                        @endphp
                                        @foreach ($equipment as $item2)
                                            <li class="mx-5"> <small>{{ $item2 }}</small></li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="col-md-4">
                    <div class="p-3 shadow bg-white sticky-top"
                        style="border-radius: 15px; padding: 30px !important; top:80px; z-index: 1;">
                        <div class="fw-bold mb-2">{{ $location->nama }}</div>
                        <small class="d-block fw-light mb-4">{{ $location->deskripsi }}</small>
                        <div class="d-flex mb-3">
                            <small class="me-3"><i class="fa-solid fa-location-dot"></i></small>
                            <small class="fw-light">{{ $location->alamat }}</small>
                        </div>
                        <div class="d-flex mb-3">
                            <small class="me-3"><i class="fa-solid fa-phone"></i></small>
                            <small class="fw-light">{{ $location->no_hp }}</small>
                        </div>
                        <div class="d-flex mb-3">
                            <small class="me-3"><i class="fa-solid fa-envelope-open-text"></i></small>
                            <small class="fw-light">{{ $location->email }}</small>
                        </div>
                        <div class="text-center mb-4">
                            <a href="{{ $location->url_location }}" class="btn custom-btn w-100" target="_blank">Get
                                Directions</a>
                        </div>
                        <div class="fw-bold mb-3">Opening hours for this week</div>
                        @foreach ($location->locationOpening as $item)
                            <div class="row mb-2">
                                <div class="col-md-4 fw-light">{{ $item->hari_formatted }}</div>
                                <div class="col-md-8 fw-light text-end">{{ $item->jam_open }} - {{ $item->jam_close }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('js')
    <script>
        $(document).ready(function() {

        });
    </script>
@endsection
