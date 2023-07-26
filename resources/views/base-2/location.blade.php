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
                <div class="col-lg-4 col-md-6 mb-5">
                    <div class="custom-card-classes">
                        <div class="w-100 bg-dark image-custom-card"
                            style="background-image: url(https://www.bcalife.co.id/storage/articles/share/bagus-untuk-kesehatan-simak-5-manfaat-olahraga-bagi-tubuh-ini-pantang-diabaikan-1653392364.jpg);">
                        </div>
                        <div class="px-4 py-4">
                            <h5 class="fw-bold">Bumi Studio Jakarta</h5>
                            <div class="row mb-3">
                                <div class="col-md-1"><i class="fa-solid fa-location-dot"></i></div>
                                <div class="col-md fw-light">
                                    Jl. Bumi No. 4, Kebayoran Baru, Jakarta Selatan, 12120
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-1"><i class="fa-solid fa-phone"></i></div>
                                <div class="col-md fw-light">
                                    +62 819 4450 2222
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-1"><i class="fa-regular fa-envelope"></i></div>
                                <div class="col-md fw-light">
                                    bumistudiojakarta@gmail.com
                                </div>
                            </div>
                            <div>
                                <a href="{{ route('detail-location', ['id' => 1]) }}" class="btn custom-btn">View More</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
