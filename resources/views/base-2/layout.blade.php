<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('vertical/design_7_black.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
    @yield('css')
    @livewireStyles
    @stack('css')
</head>

<body class="d-flex flex-column min-vh-100">
    <nav class="navbar navbar-expand-lg bg-light bg-color-custom shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold text-white fs-4" href="#">
                <img src="{{ asset('horizontal/design_4_white.png') }}" alt="" height="50">
            </a>
            <button class="navbar-toggler text-white" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse ms-md-5 ms-0" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link mx-2 text-white @if ($active == 'home') active nav-link-fs @endif"
                            aria-current="page" href="{{ route('base') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link mx-2 text-white @if ($active == 'packages') active nav-link-fs @endif"
                            href="{{ route('packages') }}">Paket</a>
                    </li>
                    @if (Auth::user())
                        <li class="nav-item ">
                            <a class="nav-link mx-2 text-white @if ($active == 'jadwal-kelas') active nav-link-fs @endif"
                                href="{{ route('kelas-jadwal') }}">Kelas</a>
                        </li>
                    @else
                    <li class="nav-item">
                        <a class="nav-link mx-2 text-white @if ($active == 'list-kelas') active nav-link-fs @endif"
                            href="{{ route('kelas-list') }}">Kelas</a>
                    </li>
                    @endif
                    <li class="nav-item">
                        <a class="nav-link mx-2 text-white @if ($active == 'location') active nav-link-fs @endif"
                            href="{{ route('location') }}">Location</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link mx-2 text-white @if ($active == 'contact us') active nav-link-fs @endif"
                            href="{{ route('contactus') }}">Contact
                            us</a>
                    </li>
                </ul>
                @if (\Auth::user())
                    <div class="dropdown">
                        <button class="btn dropdown-toggle d-flex align-items-center justify-content-center"
                            type="button" data-bs-toggle="dropdown" aria-expanded="false"
                            style="border: none !important">
                            <span class="text-white fw-bold mx-1 text-uppercase rounded-circle px-3"
                                style="background-color: purple; padding: 10px;">
                                {{ substr(Auth::user()->name, 0, 2) }}
                            </span>
                            <span class="mx-1 text-white">{{ Auth::user()->name }}</span>
                        </button>
                        <ul class="dropdown-menu bg-color-custom w-100" style="z-index: 100">
                            <li class="my-2"><a class="dropdown-item text-white" href="{{ route('profile') }}"><i
                                        class="fa-regular fa-circle-user"></i> <span class="mx-2">My
                                        Profile</span></a>
                            </li>
                            <li class="my-2"><a class="dropdown-item text-white"
                                    href="{{ route('profile.bookings') }}"><i class="fa-regular fa-credit-card"></i>
                                    <span class="mx-2">My Bookings</span></a></li>
                            <li class="my-2"><a class="dropdown-item text-white"
                                    href="{{ route('profile.voucher') }}"><i class="fa-solid fa-ticket"></i>
                                    <span class="mx-2">My Voucher</span></a></li>
                            <li class="my-2"><a class="dropdown-item text-white"
                                    href="{{ route('profile.packages') }}"><i
                                        class="fa-solid fa-hand-holding-heart"></i>
                                    <span class="mx-2">My Packages</span></a>
                            </li>
                            <li class="my-2" class="px-2">
                                <hr>
                            </li>
                            <li class="my-2"><a class="dropdown-item text-white"
                                    href="{{ route('profile.billing') }}"><i class="fa-solid fa-comments-dollar"></i>
                                    <span class="mx-2">Billing</span></a></li>
                            <li class="my-2">
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button class="dropdown-item text-white" type="submit"><i
                                            class="fa-solid fa-right-from-bracket"></i> <span
                                            class="mx-2">Logout</span></button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <div class="d-flex justify-content-evenly align-items-center">
                        <a href="{{ route('login') }}"
                            class="btn btn-outline-dark shadow-sm custom-btn custom-btn-outline"
                            style="border-color: white; color: white">Login / Registrasi</a>
                        {{-- <button type="button"
                            class="btn btn-outline-dark shadow-sm custom-btn custom-btn-outline ms-3 btn-ganti-lokasi"
                            style="border-color: white; color: white">Ganti Lokasi</button> --}}
                    </div>
                @endif
            </div>
        </div>
    </nav>

    @yield('content')
    {{-- @livewire('card-location-studio') --}}
    @livewire('footer')
    @livewire('floating-whats-app')

    <script src="/js/app.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"
        integrity="sha512-fD9DI5bZwQxOi7MhYWnnNPlvXdp/2Pj3XSTRrFs5FQa4mizyGLnJcN6tuvUS6LbmgN1ut+XGSABKvjN0H6Aoow=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.0/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('/js/alpine.js') }}"></script>
    <script src="{{ asset('assets/js/custom.js') }}"></script>
    @yield('js')
    @livewireScripts
    @stack('js')
    <script>
        $(document).ready(function() {
            if ("{{ session()->get('isLocationChecked') }}" == "") {
                $('#modal_location_studio').modal('show')
            }
        });
        if ("{{ session()->has('fail') }}") {
            Toastify({
                text: "{{ session('fail') }}",
                className: "danger",
                duration: 3000,
                style: {
                    background: "linear-gradient(to right, #FD8D14, #C51605)"
                }
            }).showToast();
        } else if ("{{ session()->has('success') }}") {
            Toastify({
                text: "{{ session('success') }}",
                className: "success",
                duration: 3000,
                style: {
                    background: "linear-gradient(to right, #7A9D54, #557A46)"
                }
            }).showToast();
        }

        $('.btn-ganti-lokasi').on('click', function() {
            $('#modal_location_studio').modal('show')
        })
    </script>
</body>

</html>
