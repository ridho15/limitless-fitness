@extends('base-2.layout')

@section('content')
    <section class="my-4">
        <div class="container">
            <div class="d-flex mt-4 mb-3">
                <small class="fw-light mx-1">Locate Us</small>
                <small class="mx-1"><i class="fa-solid fa-chevron-right"></i></small>
                <small class="fw-bold mx-1">Bumi Studio Jakarta</small>
            </div>
            <h3 class="fw-bold mb-4">Bumi Studio Jakarta</h3>
            <div class="row">
                <div class="col-md-8">

                </div>
                <div class="col-md-4">
                    <div class="p-3 shadow bg-white" style="border-radius: 15px; padding: 30px !important">
                        <div class="fw-bold mb-2">Bumi Studio Jakarta</div>
                        <small class="d-block fw-light mb-4">
                            Looking for a space to sweat out your day? Our zen studio is the place for you to rewind, relax,
                            and let your hairs loose!
                        </small>
                        <div class="d-flex mb-3">
                            <span class="me-3"><i class="fa-solid fa-location-dot"></i></span>
                            <span class="fw-light">Jl. Bumi No. 4, Kebayoran Baru, Jakarta Selatan, 12120, Second Floor,
                                Daerah Khusus Ibukota Jakarta, Kota Jakarta Selatan 12120</span>
                        </div>
                        <div class="d-flex mb-3">
                            <span class="me-3"><i class="fa-solid fa-phone"></i></span>
                            <span class="fw-light">+62 819 4450 2222</span>
                        </div>
                        <div class="d-flex mb-3">
                            <span class="me-3"><i class="fa-solid fa-envelope-open-text"></i></span>
                            <span class="fw-light">bumistudiojakarta@gmail.com</span>
                        </div>
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
