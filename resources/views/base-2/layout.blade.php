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

<body class="d-flex flex-column min-vh-100">
    <nav class="navbar navbar-expand-lg bg-light py-3 bg-color-custom shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold fs-4" href="#">Daily Dose Pilates</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse ms-5" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item nav-link-fs">
                        <a class="nav-link mx-2 @if ($active == 'home') active @endif" aria-current="page"
                            href="{{ route('base') }}">Home</a>
                    </li>
                    <li class="nav-item nav-link-fs">
                        <a class="nav-link mx-2 @if ($active == 'class') active @endif"
                            href="{{ route('class') }}">Class</a>
                    </li>
                    <li class="nav-item nav-link-fs">
                        <a class="nav-link mx-2 @if ($active == 'packages') active @endif"
                            href="{{ route('packages') }}">Packages</a>
                    </li>
                    <li class="nav-item nav-link-fs ">
                        <a class="nav-link mx-2 @if ($active == 'timetable') active @endif"
                            href="{{ route('timetable') }}">Timetable</a>
                    </li>
                    <li class="nav-item nav-link-fs">
                        <a class="nav-link mx-2 @if ($active == 'location') active @endif"
                            href="{{ route('location') }}">Location</a>
                    </li>
                    <li class="nav-item nav-link-fs">
                        <a class="nav-link mx-2 @if ($active == 'contact us') active @endif" href="{{ route('contactus') }}">Contact
                            us</a>
                    </li>
                </ul>
                <a href="{{ route('login') }}" class="btn btn-outline-dark shadow-sm custom-btn custom-btn-outline">Login / Registrasi</a>
            </div>
        </div>
    </nav>

    @yield('content')
    {{-- Start Footer --}}
    <footer class="bg-color-custom pb-3 pt-5 mt-auto" id="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <h3 class="fw-bold text-grey mb-5">Daily Dose Pilates</h3>
                    <img src="{{ asset('assets/images/logo-sabrina.png') }}" width="200" height="100" class="mb-4" alt="logo">
                    <div class="d-flex justify-content-center align-items-center">
                        <small class="mx-2">Contact us</small>
                        <small class="mx-2">FAQ</small>
                        <small class="mx-2">Terms Of Use</small>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-6 fw-light">
                    Copyright © 2023 <strong>Ridho Al Amzah</strong>
                </div>
                <div class="col-md-6 text-end fw-light">
                    <div class="country-timezone">
                        <span class="fi fi-id"></span>
                        <p>Indonesia (GMT +07:00)</p>
                    </div>
                </div>
            </div>
        </div>
    </footer>
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
