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
    @yield('css')
    @livewireStyles
    @stack('css')
    <style type="text/css">
        #results {
            padding: 20px;
            border: 1px solid;
            width: 100%;
            background: #ccc;
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
