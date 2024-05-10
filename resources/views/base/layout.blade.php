<!DOCTYPE html>
<html lang="zxx">

<head>
    <title>{{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <meta charset="utf-8">
    <link rel="apple-touch-icon" sizes="57x57" href="assets/images/favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="assets/images/favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="assets/images/favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="assets/images/favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="assets/images/favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="assets/images/favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="assets/images/favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="assets/images/favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="assets/images/favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192" href="assets/images/favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="assets/images/favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicon/favicon-16x16.png">
    <link rel="manifest" href="assets/images/favicon/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="../ms-icon-144x144.html">
    <meta name="theme-color" content="#ffffff">
    <!-- Latest compiled and minified CSS -->
    <link href="{{ asset('/assets/bootstrap/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('/assets/js/bootstrap.min.js') }}">
    <!-- Font Awesome link -->
    <link rel="stylesheet" href="{{ asset('/assets/css/font-awesome/6.1.1/css/all.min.css') }}">
    <!-- StyleSheet link CSS -->
    <link href="{{ asset('/assets/css/style.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('/assets/css/mediaqueries.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('/assets/css/custom-style.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('/assets/css/special-classes.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('/assets/css/aos.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <link href="{{ asset('/assets/css/custom.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('/assets/css/magnific-popup.js/1.1.0/magnific-popup.css') }}">
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
    @yield('css')
    @livewireStyles
    @stack('css')
</head>

<body>
    <div class="banner-section-outer">
        <header>
            <div class="main_header">
                <div class="container-fluid">
                    <nav class="navbar navbar-expand-lg navbar-light p-0">
                        <a class="navbar-brand" href="index.html">
                            <figure class="mb-0"><img src="{{ asset('/assets/images/yogastic_logo.png') }}"
                                    alt=""></figure>
                        </a>
                        <button class="navbar-toggler collapsed" type="button" data-toggle="collapse"
                            data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                            aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                            <span class="navbar-toggler-icon"></span>
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav">
                                <li class="nav-item active">
                                    <a class="nav-link" href="{{ route('base') }}">Home</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#footer_section">Contact Us</a>
                                </li>
                                @if (Auth::check() == false)
                                    <li class="nav-item">
                                        <a class="nav-link contact_us" href="{{ route('login') }}">Login</a>
                                    </li>
                                @else
                                    <li class="nav-item">
                                        <a class="nav-link contact_us" href="{{ route('profile') }}">Profile</a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </nav>
                </div>
            </div>
        </header>

        <!-- SOCIAL ICONS -->
        <div class="left_icons float-left d-table" data-aos="fade-down">
            <div class="icon_content d-table-cell align-middle">
                <ul class="list-unstyled p-0 m-0">
                    <li>
                        <a href="#"><i class="fa-brands fa-facebook-f" aria-hidden="true"></i></a>
                    </li>
                    <li>
                        <a href="#"><i class="fa-brands fa-twitter" aria-hidden="true"></i></a>
                    </li>
                    <li>
                        <a href="#"><i class="fa-brands fa-linkedin-in" aria-hidden="true"></i></a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- BANNER SECTION -->
        @yield('banner')
    </div>

    @yield('content')

    <!-- FOOTER SECTION -->
    <section class="footer-section" id="footer_section">
        <div class="container">
            <div class="middle-portion">
                <div class="row">
                    <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 d-lg-block d-none">
                        <div class="footer_logo">
                            <a href="index.html" class="text-decoration-none">
                                <figure class="mb-0"><img src="assets/images/footer_logo.png" alt=""
                                        class="img-fluid"></figure>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                        <div class="about_col">
                            <h4>About Us</h4>
                            <ul class="list-unstyled">
                                <li>
                                    <p>Quisuam est rui dolorem ipsum quia dolor sit amet, consectetur adipisci velit
                                        sea...</p>
                                </li>
                                <li class="icons"><a href="#"><i class="fa-brands fa-facebook-f"
                                            aria-hidden="true"></i></a></li>
                                <li class="icons"><a href="#"><i class="fa-brands fa-twitter"
                                            aria-hidden="true"></i></a></li>
                                <li class="icons"><a href="#"><i class="fa-brands fa-linkedin-in mr-0"
                                            aria-hidden="true"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 d-md-block d-none">
                        <div class="links_col">
                            <h4>Quick Links</h4>
                            <ul class="list-unstyled">
                                <li>
                                    <a href="index.html">Home</a>
                                </li>
                                <li>
                                    <a href="about.html">About Us</a>
                                </li>
                                <li>
                                    <a href="services.html">Services</a>
                                </li>
                                <li>
                                    <a href="team.html">Team</a>
                                </li>
                                <li>
                                    <a href="contact.html">Contact Us</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 d-sm-block d-none">
                        <div class="contact_col">
                            <h4>Contact Info</h4>
                            <ul class="list-unstyled">
                                <li class="contact_icons">
                                    <i class="fa-solid fa-phone"></i>
                                    <a href="tel:+61383766284" class="text-decoration-none">+61 3 8376 6284</a>
                                </li>
                                <li class="contact_icons">
                                    <i class="fa-sharp fa-solid fa-envelope"></i>
                                    <a href="mailto:Info@yogastic.om"
                                        class="text-decoration-none">Info@yogastic.om</a>
                                </li>
                                <li class="mb-0">
                                    <i class="fa-solid fa-location-dot location"></i>
                                    <span>21 King Street Melbourne, 3000, Australia</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="bottom-portion">
            <div class="copyright col-xl-12">
                <p>Copyright {{ date('Y') }}, R All Rights Reserved.</p>
            </div>
        </div>
        <div class="footer_shape right_shape">
            <figure class="mb-0"><img src="assets/images/footer_shape.png" alt="" class="img-fluid">
            </figure>
        </div>
    </section>
    <!-- Latest compiled JavaScript -->
    <script src="{{ asset('/assets/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('/assets/js/popper.min.js') }}"></script>
    <script src="{{ asset('/assets/js/video-popup.js') }}"></script>
    <script src="{{ asset('/assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('/assets/js/aos.js') }}"></script>
    <script src="{{ asset('/assets/js/video-section.js') }}"></script>
    <script src="{{ asset('/assets/js/animation.js') }}"></script>
    <script src="https://unpkg.com/ityped@0.0.10"></script>
    <script src="{{ asset('/assets/js/type.js') }}"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script src="{{ asset('/assets/js/custom.js') }}"></script>
    @yield('js')
    @livewireScripts
    @stack('js')
    <script>
        $(document).ready(function() {
            if ("{{ session()->has('fail') }}") {
                toast("{{ session()->get('fail') }}", "danger");
            } else if ("{{ session()->has('success') }}") {
                toast("{{ session()->get('success') }}", "success")
            }
        });
    </script>
</body>

<!-- Mirrored from html.designingmedia.com/yogastic/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Sun, 23 Jul 2023 13:27:20 GMT -->

</html>
