<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daily Dose Pilates</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
    @yield('css')
    @livewireStyles
    @stack('css')
</head>

<body class="d-flex flex-column vh-100">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4 px-0">
                <div class="bg-white shadow min-vh-100 d-flex align-items-center justify-content-center py-5">
                    <div class="w-100 px-md-5 px-3">
                        <div class="text-center mb-3">
                            <img src="{{ asset('assets/images/logo-sabrina.png') }}" alt="" height="100"
                                width="200">
                        </div>
                        <div class="text-center fw-bold mb-5">
                            Enter Your Data Account
                        </div>
                        <form>
                            <label for="" class="form-label">Nama</label>
                            <div class="input-group mb-3">
                                <input type="text" name="name" class="form-control padding-vertical-12"
                                    placeholder="Masukkan nama" aria-label="Masukkan nama"
                                    aria-describedby="basic-addon2">
                                <span class="input-group-text" id="basic-addon2"><i
                                        class="fa-solid fa-file-signature px-2"></i></span>
                            </div>
                            <label for="" class="form-label">Email</label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control padding-vertical-12" name="email"
                                    placeholder="Masukkan email" aria-label="Masukkan email"
                                    aria-describedby="basic-addon2">
                                <span class="input-group-text" id="basic-addon2"><i
                                        class="fa-regular fa-envelope px-2"></i></span>
                            </div>
                            <label for="" class="form-label">Phone Number</label>
                            <div class="input-group mb-3">
                                <input type="number" class="form-control padding-vertical-12" name="no_hp"
                                    placeholder="Phone number" aria-label="Phone number"
                                    aria-describedby="basic-addon2">
                                <span class="input-group-text" id="basic-addon2"><i
                                        class="fa-solid fa-phone px-2"></i></span>
                            </div>
                            <label for="exampleInputPassword1" class="form-label">Password</label>
                            <div class="mb-3 input-group">
                                <input type="password" class="form-control padding-vertical-12" name="password"
                                    placeholder="Masukkan password" id="exampleInputPassword1">
                                <span class="input-group-text" id="basic-addon2"><i
                                        class="fa-solid fa-key px-2"></i></span>
                            </div>
                            <label for="exampleInputPassword1" class="form-label">Address</label>
                            <div class="mb-3 input-group">
                                <textarea name="alamat" class="form-control padding-vertical-12" placeholder="Masukkan alamat"></textarea>
                                <span class="input-group-text" id="basic-addon2"><i
                                        class="fa-solid fa-map-location-dot px-2"></i></span>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn custom-btn w-100">Submit</button>
                            </div>
                            <div class="row justify-content-center align-items-center my-4">
                                <div class="col-md-5 col-5">
                                    <hr>
                                </div>
                                <div class="col-md-1 col-1 text-center">
                                    Or
                                </div>
                                <div class="col-md-5 col-5">
                                    <hr>
                                </div>
                            </div>
                            <div class="text-center">
                                <a href="{{ route('login') }}" class="btn custom-btn-outline w-100">Login</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-8 px-0">
                <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="true">
                    <div class="carousel-indicators">
                        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0"
                            class="active" aria-current="true" aria-label="Slide 1"></button>
                        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1"
                            aria-label="Slide 2"></button>
                    </div>
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <div class="custom-carousel"
                                style="background-image: url('https://cdnwpedutorenews.gramedia.net/wp-content/uploads/2022/05/30155159/yoga-4-scaled.jpg');">
                                <div class="position-absolute h-100 w-100 bg-dark"
                                    style="--bs-bg-opacity: .5; z-index: 1; "></div>
                                <div class="position-absolute position-absolute d-flex flex-column justify-content-start align-items-start h-100 w-100"
                                    style="z-index: 1; margin-top: 270px">
                                    <div class="container px-5">
                                        <h1 class="text-white my-3">Looking for a space to sweat out your day</h1>
                                        <p class="text-white">Our zen studio is the place for you to rewind, relax, and
                                            let your
                                            hairs loose</p>
                                        <div class="d-flex align-items-center justify-content-right">
                                            <button class="btn me-3 custom-btn custom-btn-white">Book Now</button>
                                            <button class="btn ms-3 my-3 custom-btn custom-btn-outline-white">Buy
                                                Packages</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="custom-carousel"
                                style="background-image: url('https://cdnwpedutorenews.gramedia.net/wp-content/uploads/2022/05/30155159/yoga-4-scaled.jpg');">
                                <div class="position-absolute h-100 w-100 bg-dark"
                                    style="--bs-bg-opacity: .5; z-index: 1; "></div>
                                <div class="position-absolute position-absolute d-flex flex-column justify-content-start align-items-start h-100 w-100"
                                    style="z-index: 1; margin-top: 270px">
                                    <div class="container px-5">
                                        <h1 class="text-white my-3">Looking for a space to sweat out your day long</h1>
                                        <p class="text-white">Our zen studio is the place for you to rewind, relax, and
                                            let your
                                            hairs loose</p>
                                        <div class="d-flex align-items-center justify-content-right">
                                            <button class="btn me-3 custom-btn custom-btn-white">Book Now</button>
                                            <button class="btn ms-3 my-3 custom-btn custom-btn-outline-white">Buy
                                                Packages</button>
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
            </div>
        </div>
    </div>
    {{-- End Footer --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"
        integrity="sha512-fD9DI5bZwQxOi7MhYWnnNPlvXdp/2Pj3XSTRrFs5FQa4mizyGLnJcN6tuvUS6LbmgN1ut+XGSABKvjN0H6Aoow=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="{{ asset('assets/js/custom.js') }}"></script>
    @yield('js')
    @livewireScripts
    @stack('js')
</body>

</html>
