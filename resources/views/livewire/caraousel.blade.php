<div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="true">
    <div class="carousel-indicators">
        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active"
            aria-current="true" aria-label="Slide 1"></button>
    </div>
    <div class="carousel-inner">
        <div class="carousel-item active">
            <div class="custom-carousel"
                style="background-size: cover; background-position: center; background-repeat: no-repeat; background-image: url({{ $location ? asset('storage/' . $location->image) : asset('assets/images/home.jpg') }});">
                <div class="position-absolute h-100 w-100 bg-dark" style="--bs-bg-opacity: .5; z-index: 1; "></div>
                <div class="position-absolute position-absolute d-flex flex-column justify-content-start align-items-start h-100 w-100"
                    style="z-index: 1; margin-top: 270px">
                    <div class="container px-5">
                        <h1 class="text-white my-3">Welcome to (enter)
                            Daily Dose Pilates</h1>
                        <p class="text-white"> {{ $location ? 'Location - ' . $location->nama : 'Your Location' }}</p>
                        <div class="d-flex align-items-center justify-content-right">
                            <a href="{{ route('timetable') }}" class="btn me-3 custom-btn custom-btn-white">Book Now</a>
                            <a href="{{ route('packages') }}"
                                class="btn ms-3 my-3 custom-btn custom-btn-outline-white">Buy
                                Packages</a>
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
