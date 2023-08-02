<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daily Dose Pilates</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/images/logo.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
    @yield('css')
    @livewireStyles
    @stack('css')
</head>

<body class="d-flex flex-column vh-100">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4 px-0">
                <div class="bg-white shadow min-vh-100 d-flex align-items-center justify-content-center">
                    <div class="w-100 px-md-5 px-3">
                        <div class="text-center mb-3">
                            <img src="{{ asset('assets/images/logo.png') }}" alt="" class="img-fluid" style="object-fit: contain">
                        </div>
                        <div class="text-center fw-bold mb-5">
                            Login into your account
                        </div>
                        <form method="POST" action="{{ route('postLogin') }}">
                            @csrf
                            @if (session()->has('fail'))
                                <div class="text-center">
                                    <div class="alert alert-danger" role="alert">
                                        {{ session('fail') }}
                                    </div>
                                </div>
                            @elseif(session()->has('success'))
                                <div class="text-center">
                                    <div class="alert alert-success" role="alert">
                                        {{ session('success') }}
                                    </div>
                                </div>
                            @endif
                            <label for="" class="form-label">Email</label>
                            <div class="input-group mb-3">
                                <input type="text" name="email" class="form-control padding-vertical-12"
                                    placeholder="Masukkan email" aria-label="Masukkan email"
                                    aria-describedby="basic-addon2">
                                <span class="input-group-text" id="basic-addon2"><i
                                        class="fa-regular fa-envelope px-2"></i></span>
                            </div>
                            <label for="exampleInputPassword1" class="form-label">Password</label>
                            <div class="mb-3 input-group">
                                <input type="password" name="password" class="form-control padding-vertical-12"
                                    placeholder="Masukkan password" id="exampleInputPassword1">
                                <span class="input-group-text" id="basic-addon2"><i
                                        class="fa-solid fa-key px-2"></i></span>
                            </div>
                            <div class="text-end mb-3">
                                <a href="" class="fw-light text-decoration-none text-dark">
                                    <small>Forgot Password</small>
                                </a>
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
                                <a href="{{ route('registrasi') }}" class="btn custom-btn-outline w-100">Register</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-8 px-0">
                @livewire('caraousel')
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
