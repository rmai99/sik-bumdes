<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="{{url('/')}}/assets/img/shortcut.png">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Masuk') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    {{-- <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet"> --}}
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app" class="bg-green-smooth">
        <nav class="navbar navbar-expand-md navbar-light bg-green-smooth shadow-sm">
            <div class="container mt-4">
                <a class="navbar-brand d-flex" href="{{ url('/login') }}">
                    <div>
                        <img src="{{url('/')}}/assets/img/logo.png" style="width:30px">
                    </div>
                    <div class="ml-3">
                        <h4 class="font-weight-bold">Sistem Informasi Keuangan</h4>
                        <h4 class="font-weight-bold">Badan Usaha Milik Desa</h4>
                    </div>
                </a>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
</html>
