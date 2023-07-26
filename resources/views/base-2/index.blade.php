@extends('base-2.layout')

@section('content')
    {{-- Start Slider --}}
    <section>
        <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="true">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active"
                    aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1"
                    aria-label="Slide 2"></button>
            </div>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <div class="custom-carousel"
                        style="background-image: url('https://cdnwpedutorenews.gramedia.net/wp-content/uploads/2022/05/30155159/yoga-4-scaled.jpg');">
                        <div class="position-absolute h-100 w-100 bg-dark" style="--bs-bg-opacity: .5; z-index: 1; "></div>
                        <div class="position-absolute position-absolute d-flex flex-column justify-content-start align-items-start h-100 w-100"
                            style="z-index: 1; margin-top: 270px">
                            <div class="container">
                                <h1 class="text-white my-3">Looking for a space to sweat out your day</h1>
                                <p class="text-white">Our zen studio is the place for you to rewind, relax, and let your
                                    hairs loose</p>
                                <div class="d-flex align-items-center justify-content-right">
                                    <button class="btn me-3 custom-btn custom-btn-white">Book Now</button>
                                    <button class="btn ms-3 my-3 custom-btn custom-btn-outline-white">Buy Packages</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="custom-carousel"
                        style="background-image: url('https://cdnwpedutorenews.gramedia.net/wp-content/uploads/2022/05/30155159/yoga-4-scaled.jpg');">
                        <div class="position-absolute h-100 w-100 bg-dark" style="--bs-bg-opacity: .5; z-index: 1; "></div>
                        <div class="position-absolute position-absolute d-flex flex-column justify-content-start align-items-start h-100 w-100"
                            style="z-index: 1; margin-top: 270px">
                            <div class="container">
                                <h1 class="text-white my-3">Looking for a space to sweat out your day long</h1>
                                <p class="text-white">Our zen studio is the place for you to rewind, relax, and let your
                                    hairs loose</p>
                                <div class="d-flex align-items-center justify-content-right">
                                    <button class="btn me-3 custom-btn custom-btn-white">Book Now</button>
                                    <button class="btn ms-3 my-3 custom-btn custom-btn-outline-white">Buy Packages</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators"
                data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators"
                data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </section>
    {{-- End Slider --}}
    {{-- List Class --}}
    <section class="py-5">
        <div class="container">
            <h3 class="fw-bold text-center">
                Signature Class Type
            </h3>
            <p class="text-center">All our classes are aimed to help you to progress in your Pilates journey</p>
            <div class="d-flex align-items-center my-5 justify-content-center">
                <button class="btn btn-outline-dark custom-btn custom-btn-outline me-5">
                    See All Classes
                </button>
                <button class="btn btn-dark ms-5 custom-btn">
                    Book A Class
                </button>
            </div>
            <div class="row my-5">
                <div class="col-md-4 mb-4">
                    <div class="custom-card position-relative"
                        style="background-image: url('https://ik.imagekit.io/rezeve/business/e046f57f-3236-4290-aea7-7925cc309467/image/class/f9d71570-c90b-4f43-9ee6-32682000fc74~PHOTO-2023-02-21-13-00-17.jpg')">
                        <div class="h-100 position-absolute bg-gradient w-100 opacity-card-black"></div>
                        <div class="position-absolute d-flex align-items-end justify-content-center h-100 w-100 card-class-text"
                            style="z-index: 10;">
                            <div class="px-4 py-4">
                                <h2 class="text-white fw-bold">
                                    Private Duet
                                </h2>
                                <div class="d-flex mb-5">
                                    <small class="text-white fw-light"> <i class="fa-solid fa-star text-warning"></i> No
                                        Rating Yet</small>
                                </div>
                                <p class="fw-light text-white">
                                    Bring a friend - two is better than one! This semi-private class is perfect for those
                                    who don't like working out alone. ...
                                </p>
                                <a href="{{ route('detail-class', ['id' => 1]) }}"
                                    class="btn btn-outline-dark shadow-sm custom-btn-white">View More</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="custom-card position-relative"
                        style="background-image: url('https://ik.imagekit.io/rezeve/business/e046f57f-3236-4290-aea7-7925cc309467/image/class/f9d71570-c90b-4f43-9ee6-32682000fc74~PHOTO-2023-02-21-13-00-17.jpg')">
                        <div class="h-100 position-absolute bg-gradient w-100 opacity-card-black"></div>
                        <div class="position-absolute d-flex align-items-end justify-content-center h-100 w-100 card-class-text"
                            style="z-index: 10;">
                            <div class="px-4 py-4">
                                <h2 class="text-white fw-bold">
                                    Private Duet
                                </h2>
                                <div class="d-flex mb-5">
                                    <small class="text-white fw-light">No Rating Yet</small>
                                </div>
                                <p class="fw-light text-white">
                                    Bring a friend - two is better than one! This semi-private class is perfect for those
                                    who don't like working out alone. ...
                                </p>
                                <button class="btn btn-outline-dark shadow-sm custom-btn-white">View More</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="custom-card position-relative"
                        style="background-image: url('https://ik.imagekit.io/rezeve/business/e046f57f-3236-4290-aea7-7925cc309467/image/class/f9d71570-c90b-4f43-9ee6-32682000fc74~PHOTO-2023-02-21-13-00-17.jpg')">
                        <div class="h-100 position-absolute bg-gradient w-100 opacity-card-black"></div>
                        <div class="position-absolute d-flex align-items-end justify-content-center h-100 w-100 card-class-text"
                            style="z-index: 10;">
                            <div class="px-4 py-4">
                                <h2 class="text-white fw-bold">
                                    Private Duet
                                </h2>
                                <div class="d-flex mb-5">
                                    <small class="text-white fw-light">No Rating Yet</small>
                                </div>
                                <p class="fw-light text-white">
                                    Bring a friend - two is better than one! This semi-private class is perfect for those
                                    who don't like working out alone. ...
                                </p>
                                <button class="btn btn-outline-dark shadow-sm custom-btn-white">View More</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="my-5">
        <div class="container">
            <h3 class="fw-bold mb-4 text-center">Near The Heart of Jakarta</h3>
            <p class="fw-light text-center mb-5">We are located in a strategic part of Jakarta</p>
            <div class="text-center my-5">
                <button class="btn custom-btn fw-light"
                    style="padding-top: 15px; padding-bottom: 15px; border-radius: 30px">Let's go there</button>
            </div>
            <div class="w-100 position-relative d-flex align-items-center justify-content-center custom-card"
                style="background-image: url('https://i.etsystatic.com/11143919/r/il/a74af7/2577032224/il_fullxfull.2577032224_3sk0.jpg');">
                <div class="h-100 position-absolute bg-gradient w-100 opacity-card-black"></div>
                <div class="position-absolute d-flex align-items-end justify-content-center h-100 w-100 card-class-text"
                    style="z-index: 10;">
                    <div class="px-4 py-4">
                        <h2 class="text-white fw-bold mb-4">
                            Bumi Studio Jakarta
                        </h2>
                        <small class="d-block fw-light text-white mb-2">
                            <i class="fa-solid fa-location-dot"></i>
                            <span class="ms-2">
                                Jl. Bumi No. 4, Kebayoran Baru, Jakarta Selatan, 12120,Second Floor,Daerah Khusus Ibukota
                                Jakarta,12120,Kota Jakarta Selatan
                            </span>
                        </small>
                        <small class="d-block fw-light text-white mb-4">
                            <i class="fa-solid fa-phone"></i> <span class="ms-2">
                                +62 819 4450 2222
                            </span>
                        </small>
                        <button class="btn btn-outline-dark shadow-sm custom-btn-white">View More</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
    {{-- End List Class --}}
@endsection
