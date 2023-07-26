@extends('base-2.layout')

@section('content')
    <section class="my-4">
        <div class="container">
            <div class="row mb-3">
                <div class="col-md-8 mb-3">
                    <div class="d-flex mb-3">
                        <small class="fw-light mx-1">classes</small>
                        <small class="mx-1"><i class="fa-solid fa-chevron-right"></i></small>
                        <small class="fw-bold mx-1">Reformer Flow</small>
                    </div>
                    <h3 class="fw-bold mb-2">Reformer Flow</h3>
                    <div class="d-flex align-items-center mb-3">
                        <div class="me-3">
                            <i class="fa-solid fa-star text-warning"></i>
                            <i class="fa-solid fa-star text-warning"></i>
                            <i class="fa-solid fa-star text-warning"></i>
                            <i class="fa-solid fa-star text-warning"></i>
                            <i class="fa-solid fa-star text-warning"></i>
                        </div>
                        <span class="px-3 py-1" style="border: 1px solid grey; border-radius: 15px">
                            <small class="fw-light">10 Review</small>
                        </span>
                    </div>
                    <div class="row mb-4 justify-content-start align-items-center">
                        <div class="col-md-4 col-6 text-center">
                            <small class="d-block fw-semibold"><i class="fa-solid fa-clock-rotate-left"></i>
                                Duration</small>
                            <small class="d-block fw-light">55 mins</small>
                        </div>
                        <div class="col-md-4 col-6 text-center">
                            <small class="d-block fw-semibold"><i class="fa-solid fa-timeline"></i> Difficulty Level</small>
                            <small class="d-block fw-light">Open To All</small>
                        </div>
                    </div>
                    <div class="shadow bg-white p-4" style="border-radius: 15px">
                        <div class="row">
                            <div class="col-md mb-3">
                                <div class="dropdown w-100">
                                    <button class="btn btn-custom-filter w-100 dropdown-toggle shadow-sm border"
                                        type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        All Class Types
                                    </button>
                                    <ul class="dropdown-menu w-100">
                                        <li><a class="dropdown-item" href="#">All Class Type</a></li>
                                        <li><a class="dropdown-item" href="#">Online</a></li>
                                        <li><a class="dropdown-item" href="#">Onsite</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-2 mb-3 text-center">
                                <button class="btn custom-btn w-100">
                                    <small class="fw-light">Book Now</small>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <img src="https://ik.imagekit.io/rezeve/business/e046f57f-3236-4290-aea7-7925cc309467/image/class/9fd2774f-8229-4680-b92b-5c96d53765ff~rsz_compressjpgcompressjpgdb_00420-2-2.jpg"
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
                        New to Pilates? Let's get down to the nitty-gritty. Learn the basics through controlled and precise
                        movements. Contact our admin to learn more.
                    </small>
                </div>
            </div>
            <hr class="my-5">
            <div class="row mb-5">
                <div class="col-md-6 fw-bold fs-4">
                    Reviews
                </div>
                <div class="col-md-6">
                    <div class="d-flex align-items-center mb-2">
                        <span class="fw-bold fs-4 me-2">5</span>
                        <small class="fw-light">10 reviews</small>
                    </div>
                    <div class="d-flex align-items-center mb-3 me-3">
                        <i class="fa-solid fa-star text-warning me-1"></i>
                        <i class="fa-solid fa-star text-warning me-1"></i>
                        <i class="fa-solid fa-star text-warning me-1"></i>
                        <i class="fa-solid fa-star text-warning me-1"></i>
                        <i class="fa-solid fa-star text-warning me-1"></i>
                    </div>
                    <hr class="my-4">
                    <div class="mb-3">
                        <strong>Reformer Flow</strong> <small class="fw-light">with Nabila Sofyan</small>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <small class="me-2">
                            <i class="fa-solid fa-star text-warning"></i>
                            <i class="fa-solid fa-star text-warning"></i>
                            <i class="fa-solid fa-star text-warning"></i>
                            <i class="fa-solid fa-star text-warning"></i>
                            <i class="fa-solid fa-star text-warning"></i>
                        </small>
                        <small class="fw-light"><i class="fa-solid fa-house-chimney-user"></i> Bumi Studio Jakarta</small>
                    </div>
                    <div class="mb-2">
                        <small class="fw-light">Very nice for 1st trial</small>
                    </div>
                    <div class="mb-2">
                        <small class="fw-light text-secondary">Posted 8 days ago</small>
                    </div>
                    <hr class="my-4">
                    <div class="mb-3">
                        <strong>Reformer Flow</strong> <small class="fw-light">with Nabila Sofyan</small>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <small class="me-2">
                            <i class="fa-solid fa-star text-warning"></i>
                            <i class="fa-solid fa-star text-warning"></i>
                            <i class="fa-solid fa-star text-warning"></i>
                            <i class="fa-solid fa-star text-warning"></i>
                            <i class="fa-solid fa-star text-warning"></i>
                        </small>
                        <small class="fw-light"><i class="fa-solid fa-house-chimney-user"></i> Bumi Studio Jakarta</small>
                    </div>
                    <div class="mb-2">
                        <small class="fw-light">Very nice for 1st trial</small>
                    </div>
                    <div class="mb-2">
                        <small class="fw-light text-secondary">Posted 8 days ago</small>
                    </div>
                </div>
            </div>
            <div class="text-center mb-5">
                <button class="btn custom-btn w-100">
                    <small>See all review</small>
                </button>
            </div>
        </div>
        <div class="container my-5">
            <hr>
        </div>
        <div class="container">
            <div class="row my-4">
                <div class="col-md-12">
                    <h3 class="fw-bold">Related Classes</h3>
                </div>
            </div>
            <div class="row my-5">
                <div class="col-lg-4 col-md-6 mb-5">
                    <div class="custom-card-classes">
                        <div class="w-100 bg-dark image-custom-card"
                            style="background-image: url(https://www.bcalife.co.id/storage/articles/share/bagus-untuk-kesehatan-simak-5-manfaat-olahraga-bagi-tubuh-ini-pantang-diabaikan-1653392364.jpg);">
                        </div>
                        <div class="px-4 py-4">
                            <h5 class="fw-bold">Reformer Flow</h5>
                            <div class="d-flex justify-content-start align-items-center my-3">
                                <small class="me-2"><i class="fa-solid fa-star text-warning"></i><i
                                        class="fa-solid fa-star text-warning"></i><i
                                        class="fa-solid fa-star text-warning"></i><i
                                        class="fa-solid fa-star text-warning"></i><i
                                        class="fa-solid fa-star text-warning"></i></small>
                                <small class="ms-2 px-2 py-2" style="border: 1px solid grey; border-radius: 20px">9
                                    Review</small>
                            </div>
                            <div class="d-flex align-items-center justify-content-start my-2">
                                <div class="me-2">
                                    <small class="fw-bold">
                                        <i class="fa-solid fa-clock-rotate-left"></i>
                                        55 Mins</small>
                                </div>
                                <div class="ms-2">
                                    <small class="fw-bold"><i class="fa-brands fa-creative-commons-nd"></i>Open to
                                        all</small>
                                </div>
                            </div>
                            <div class="my-2">
                                <p>New to Pilates? Let's get down to the nitty-gritty. Learn the basics through controlled
                                    and precise movements. Contact our admin to learn more.</p>
                            </div>
                            <div>
                                <button class="btn custom-btn">View More</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-5">
                    <div class="custom-card-classes">
                        <div class="w-100 bg-dark image-custom-card"
                            style="background-image: url(https://www.bcalife.co.id/storage/articles/share/bagus-untuk-kesehatan-simak-5-manfaat-olahraga-bagi-tubuh-ini-pantang-diabaikan-1653392364.jpg);">
                        </div>
                        <div class="px-4 py-4">
                            <h5 class="fw-bold">Reformer Flow</h5>
                            <div class="d-flex justify-content-start align-items-center my-3">
                                <small class="me-2"><i class="fa-solid fa-star text-warning"></i><i
                                        class="fa-solid fa-star text-warning"></i><i
                                        class="fa-solid fa-star text-warning"></i><i
                                        class="fa-solid fa-star text-warning"></i><i
                                        class="fa-solid fa-star text-warning"></i></small>
                                <small class="ms-2 px-2 py-2" style="border: 1px solid grey; border-radius: 20px">9
                                    Review</small>
                            </div>
                            <div class="d-flex align-items-center justify-content-start my-2">
                                <div class="me-2">
                                    <small class="fw-bold">
                                        <i class="fa-solid fa-clock-rotate-left"></i>
                                        55 Mins</small>
                                </div>
                                <div class="ms-2">
                                    <small class="fw-bold"><i class="fa-brands fa-creative-commons-nd"></i>Open to
                                        all</small>
                                </div>
                            </div>
                            <div class="my-2">
                                <p>New to Pilates? Let's get down to the nitty-gritty. Learn the basics through controlled
                                    and precise movements. Contact our admin to learn more.</p>
                            </div>
                            <div>
                                <button class="btn custom-btn">View More</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-5">
                    <div class="custom-card-classes">
                        <div class="w-100 bg-dark image-custom-card"
                            style="background-image: url(https://www.bcalife.co.id/storage/articles/share/bagus-untuk-kesehatan-simak-5-manfaat-olahraga-bagi-tubuh-ini-pantang-diabaikan-1653392364.jpg);">
                        </div>
                        <div class="px-4 py-4">
                            <h5 class="fw-bold">Reformer Flow</h5>
                            <div class="d-flex justify-content-start align-items-center my-3">
                                <small class="me-2"><i class="fa-solid fa-star text-warning"></i><i
                                        class="fa-solid fa-star text-warning"></i><i
                                        class="fa-solid fa-star text-warning"></i><i
                                        class="fa-solid fa-star text-warning"></i><i
                                        class="fa-solid fa-star text-warning"></i></small>
                                <small class="ms-2 px-2 py-2" style="border: 1px solid grey; border-radius: 20px">9
                                    Review</small>
                            </div>
                            <div class="d-flex align-items-center justify-content-start my-2">
                                <div class="me-2">
                                    <small class="fw-bold">
                                        <i class="fa-solid fa-clock-rotate-left"></i>
                                        55 Mins</small>
                                </div>
                                <div class="ms-2">
                                    <small class="fw-bold"><i class="fa-brands fa-creative-commons-nd"></i>Open to
                                        all</small>
                                </div>
                            </div>
                            <div class="my-2">
                                <p>New to Pilates? Let's get down to the nitty-gritty. Learn the basics through controlled
                                    and precise movements. Contact our admin to learn more.</p>
                            </div>
                            <div>
                                <button class="btn custom-btn">View More</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-5">
                    <div class="custom-card-classes">
                        <div class="w-100 bg-dark image-custom-card"
                            style="background-image: url(https://www.bcalife.co.id/storage/articles/share/bagus-untuk-kesehatan-simak-5-manfaat-olahraga-bagi-tubuh-ini-pantang-diabaikan-1653392364.jpg);">
                        </div>
                        <div class="px-4 py-4">
                            <h5 class="fw-bold">Reformer Flow</h5>
                            <div class="d-flex justify-content-start align-items-center my-3">
                                <small class="me-2"><i class="fa-solid fa-star text-warning"></i><i
                                        class="fa-solid fa-star text-warning"></i><i
                                        class="fa-solid fa-star text-warning"></i><i
                                        class="fa-solid fa-star text-warning"></i><i
                                        class="fa-solid fa-star text-warning"></i></small>
                                <small class="ms-2 px-2 py-2" style="border: 1px solid grey; border-radius: 20px">9
                                    Review</small>
                            </div>
                            <div class="d-flex align-items-center justify-content-start my-2">
                                <div class="me-2">
                                    <small class="fw-bold">
                                        <i class="fa-solid fa-clock-rotate-left"></i>
                                        55 Mins</small>
                                </div>
                                <div class="ms-2">
                                    <small class="fw-bold"><i class="fa-brands fa-creative-commons-nd"></i>Open to
                                        all</small>
                                </div>
                            </div>
                            <div class="my-2">
                                <p>New to Pilates? Let's get down to the nitty-gritty. Learn the basics through controlled
                                    and precise movements. Contact our admin to learn more.</p>
                            </div>
                            <div>
                                <button class="btn custom-btn">View More</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-5">
                    <div class="custom-card-classes">
                        <div class="w-100 bg-dark image-custom-card"
                            style="background-image: url(https://www.bcalife.co.id/storage/articles/share/bagus-untuk-kesehatan-simak-5-manfaat-olahraga-bagi-tubuh-ini-pantang-diabaikan-1653392364.jpg);">
                        </div>
                        <div class="px-4 py-4">
                            <h5 class="fw-bold">Reformer Flow</h5>
                            <div class="d-flex justify-content-start align-items-center my-3">
                                <small class="me-2"><i class="fa-solid fa-star text-warning"></i><i
                                        class="fa-solid fa-star text-warning"></i><i
                                        class="fa-solid fa-star text-warning"></i><i
                                        class="fa-solid fa-star text-warning"></i><i
                                        class="fa-solid fa-star text-warning"></i></small>
                                <small class="ms-2 px-2 py-2" style="border: 1px solid grey; border-radius: 20px">9
                                    Review</small>
                            </div>
                            <div class="d-flex align-items-center justify-content-start my-2">
                                <div class="me-2">
                                    <small class="fw-bold">
                                        <i class="fa-solid fa-clock-rotate-left"></i>
                                        55 Mins</small>
                                </div>
                                <div class="ms-2">
                                    <small class="fw-bold"><i class="fa-brands fa-creative-commons-nd"></i>Open to
                                        all</small>
                                </div>
                            </div>
                            <div class="my-2">
                                <p>New to Pilates? Let's get down to the nitty-gritty. Learn the basics through controlled
                                    and precise movements. Contact our admin to learn more.</p>
                            </div>
                            <div>
                                <button class="btn custom-btn">View More</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-5">
                    <div class="custom-card-classes">
                        <div class="w-100 bg-dark image-custom-card"
                            style="background-image: url(https://www.bcalife.co.id/storage/articles/share/bagus-untuk-kesehatan-simak-5-manfaat-olahraga-bagi-tubuh-ini-pantang-diabaikan-1653392364.jpg);">
                        </div>
                        <div class="px-4 py-4">
                            <h5 class="fw-bold">Reformer Flow</h5>
                            <div class="d-flex justify-content-start align-items-center my-3">
                                <small class="me-2"><i class="fa-solid fa-star text-warning"></i><i
                                        class="fa-solid fa-star text-warning"></i><i
                                        class="fa-solid fa-star text-warning"></i><i
                                        class="fa-solid fa-star text-warning"></i><i
                                        class="fa-solid fa-star text-warning"></i></small>
                                <small class="ms-2 px-2 py-2" style="border: 1px solid grey; border-radius: 20px">9
                                    Review</small>
                            </div>
                            <div class="d-flex align-items-center justify-content-start my-2">
                                <div class="me-2">
                                    <small class="fw-bold">
                                        <i class="fa-solid fa-clock-rotate-left"></i>
                                        55 Mins</small>
                                </div>
                                <div class="ms-2">
                                    <small class="fw-bold"><i class="fa-brands fa-creative-commons-nd"></i>Open to
                                        all</small>
                                </div>
                            </div>
                            <div class="my-2">
                                <p>New to Pilates? Let's get down to the nitty-gritty. Learn the basics through controlled
                                    and precise movements. Contact our admin to learn more.</p>
                            </div>
                            <div>
                                <button class="btn custom-btn">View More</button>
                            </div>
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
