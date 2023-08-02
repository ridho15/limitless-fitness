@extends('base-2.layout')

@section('content')
    <section class="my-4">
        <div class="container">
            <div class="row my-3">
                <div class="col-md-12">
                    <h3 class="fw-bold">Timetable</h3>
                </div>
            </div>
            <div class="row">
                @foreach ($listLocation as $item)
                <div class="col-lg-4 col-md-6 mb-5">
                    <div class="custom-card-classes">
                        <div class="w-100 bg-dark image-custom-card"
                            style="background-image: url({{ asset('storage/' . $item->image) }});">
                        </div>
                        <div class="px-4 py-4">
                            <h5 class="fw-bold">{{ $item->nama }}</h5>
                            <div class="row mb-3">
                                <div class="col-md-1"><i class="fa-solid fa-location-dot"></i></div>
                                <div class="col-md fw-light">
                                    {{ $item->alamat }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-1"><i class="fa-solid fa-phone"></i></div>
                                <div class="col-md fw-light">
                                    {{ $item->no_hp }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-1"><i class="fa-regular fa-envelope"></i></div>
                                <div class="col-md fw-light">
                                   {{ $item->email }}
                                </div>
                            </div>
                            <div>
                                <a href="{{ route('detail-location', ['id' => $item->id]) }}" class="btn custom-btn">View More</a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach

            </div>
        </div>
    </section>
@endsection
