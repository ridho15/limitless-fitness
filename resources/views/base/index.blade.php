@extends('base.layout')

@section('banner')
    <section class="banner-section">
        <div class="container-fluid">
            <div class="row" data-aos="fade-up">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 text-md-left text-center">
                    <div class="banner-section-content">
                        <h5>Start a Happy Life</h5>
                        <h1 data-aos="fade-up">Start Healing Your Mind, <span class="ityped"></span></h1>
                        <p data-aos="fade-right">Duis aute irure dolor in reprehenderit in volurate velit cillum
                            nulla pariatur nostrud exercitation.</p>
                        <div class="btn_wrapper" data-aos="fade-up">
                            <a class="text-decoration-none getstarted_btn" href="{{ Auth::check() ? '#kelas' : route('login') }}">Get Started</a>
                        </div>
                        <a class="top-btn" href="#footer_section">
                            <i class="fa-solid fa-arrow-down-long"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <div class="banner-section-image">
                        <figure class="mb-0">
                            <img src="{{ asset('/assets/images/banner_right_image.png') }}" alt="">
                        </figure>
                    </div>
                </div>
            </div>
            <figure class="banner_left_top_shape left_shape mb-0">
                <img src="{{ asset('/assets/images/banner_left_top_shape.png') }}" alt="" class="img-fluid">
            </figure>
            <figure class="banner_left_bottom_shape left_shape mb-0">
                <img src="{{ asset('/assets/images/banner_left_bottom_shape.png') }}" alt="" class="img-fluid">
            </figure>
            <figure class="banner_right_top_shape right_shape mb-0">
                <img src="{{ asset('/assets/images/banner_right_top_shape.png') }}" alt="" class="img-fluid">
            </figure>
            <figure class="banner_right_bottom_shape right_shape mb-0">
                <img src="{{ asset('/assets/images/banner_right_bottom_shape.png') }}" alt="" class="img-fluid">
            </figure>
        </div>
    </section>
@endsection

@section('content')
    <!-- ABOUT US SECTION -->
    <section class="aboutus_section">
        <div class="container">
            <div class="row">
                <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
                    <div class="aboutus_image">
                        <figure class="mb-0"><img src="{{ asset('/assets/images/aboutus_image.png') }}" alt=""
                                class="img-fluid"></figure>
                    </div>
                    <figure class="aboutus_top_shape left_shape mb-0">
                        <img src="{{ asset('/assets/images/aboutus_top_shape.png') }}" alt="" class="img-fluid">
                    </figure>
                    <figure class="aboutus_bottom_shape left_shape mb-0">
                        <img src="{{ asset('/assets/images/aboutus_bottom_shape.png') }}" alt="" class="img-fluid">
                    </figure>
                </div>
                <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12" data-aos="fade-right">
                    <div class="aboutus_content">
                        <h5>About us</h5>
                        <h2>Take Your Yoga to the Next Level</h2>
                        <p>Quis autem vel eum iure reprehenderit qui in eao voluptate velit esse quam nihil molestiae
                            consequatur vel illum.</p>
                        <div class="aboutus_line_wrapper">
                            <h6>Modi tempora incidunt ut labore dolore magnam aliquam auerat volutaem.</h6>
                            <figure class="mb-0 purple_line"><img src="{{ asset('/assets/images/aboutus_line.png') }}"
                                    alt="" class="img-fluid"></figure>
                        </div>
                        <div class="btn_wrapper">
                            <a href="{{ Auth::check() ? '#kelas' : route('login') }}" class="text-decoration-none get_started_btn">Get Started</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- OUR SPECIALTIES SECTION -->
    <section class="our_specialties_section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="our_specialties_heading_content">
                        <h5>Our Specialties</h5>
                        <h2>Why Choose Us</h2>
                        <p>Quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores nostrum
                            exercitationem ullam corporis suscipit laboriosam</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12" data-aos="fade-right">
                    <div class="specialties_content_wrapper">
                        <div class="specialties_content s1">
                            <p class="specialties_name">Vinyasa Yoga</p>
                            <p class="specialties_paragraph">Dolor reaellendus temorius maiores alia</p>
                        </div>
                        <div class="specialties_content s2">
                            <p class="specialties_name">Slow Yoga</p>
                            <p class="specialties_paragraph">Rabore et dolore maga eiusmo rute aliua</p>
                        </div>
                        <div class="specialties_content s3">
                            <p class="specialties_name">Intuitive Yoga</p>
                            <p class="specialties_paragraph">Earum rerum hic tene sapiente delectus</p>
                        </div>
                        <div class="specialties_content s4">
                            <p class="specialties_name">Aroma Yoga</p>
                            <p class="specialties_paragraph">Neque orro quisquam est raui dolorem</p>
                        </div>
                        <figure class="specialties_left_line mb-0">
                            <img src="assets/images/specialties_left_line.png" alt="" class="img-fluid">
                        </figure>
                    </div>
                </div>
                <div class="col-lg-6 col-md-4 col-sm-6 col-xs-12 d-md-block d-none">
                    <figure class="specialties_image mb-0">
                        <img src="assets/images/specialties_image.png" alt="" class="img-fluid">
                    </figure>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12" data-aos="fade-right">
                    <div class="specialties_content_wrapper">
                        <div class="specialties_content specialties_content2 s5">
                            <p class="specialties_name">Kundalini Yoga</p>
                            <p class="specialties_paragraph">Dolor reaellendus temorius maiores alia</p>
                        </div>
                        <div class="specialties_content specialties_content2 s6">
                            <p class="specialties_name">Bikram Yoga</p>
                            <p class="specialties_paragraph">Rabore et dolore mae eiusmo rute aliua</p>
                        </div>
                        <div class="specialties_content specialties_content2 s7">
                            <p class="specialties_name">Mindfulness Training</p>
                            <p class="specialties_paragraph">Earum rerum hic tene sapiente delectus</p>
                        </div>
                        <div class="specialties_content specialties_content2 s8">
                            <p class="specialties_name">Workout Routines</p>
                            <p class="specialties_paragraph">Neque orro quisquam est raui dolorem</p>
                        </div>
                        <figure class="specialties_right_line mb-0">
                            <img src="assets/images/specialties_right_line.png" alt="" class="img-fluid">
                        </figure>
                    </div>
                </div>
            </div>
            <figure class="our_specialties_right_shape right_shape mb-0">
                <img src="assets/images/our_specialties_right_shape.png" alt="" class="img-fluid">
            </figure>
        </div>
    </section>

    <!-- PRICING PLANS SECTION -->
    @livewire('base.kelas')

    <!-- OUR TEAM SECTION -->
    @livewire('base.instruktur')
@endsection
