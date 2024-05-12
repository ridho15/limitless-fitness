<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>
    <script src="{{ asset('/js/jquery.min.js') }}"></script>
    <script src="{{ asset('/js/webcam.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('/css/bootstrap.min.css') }}" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"
        integrity="sha512-fD9DI5bZwQxOi7MhYWnnNPlvXdp/2Pj3XSTRrFs5FQa4mizyGLnJcN6tuvUS6LbmgN1ut+XGSABKvjN0H6Aoow=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    @yield('css')
    @livewireStyles
    @stack('css')
    <style type="text/css">
        #results {
            padding: 20px;
            border: 1px solid;
            width: 100%;
            border-radius: 10px;
            background: #f0f0f0;
            border-color: #f0f0f0;
        }

        #results img {
            object-fit: contain;
            width: 100%;
            ;
        }
    </style>
</head>

<body>
    @livewire('scan-qr-code')
    <script language="JavaScript" src="{{ asset('assets/js/custom.js') }}"></script>
    @yield('js')
    @livewireScripts
    @stack('js')
</body>

</html>
