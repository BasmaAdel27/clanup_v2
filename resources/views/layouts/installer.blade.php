<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>

    <link rel="stylesheet" href="{{ asset('assets/css/app.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/app-vendors.min.css') }}">
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.8.2/dist/alpine.min.js" defer></script>
    @livewireStyles
    @stack('page_head_scripts')
</head>

<body>
    <div class="container-fluid px-3">
        <div class="row d-flex justify-content-center">
            <div class="col-md-6">
               
                <div class="w-100 py-5 px-md-5 px-xxl-6 position-relative">
                    <div class="mb-5">
                        <img class="img-fluid mb-3" src="{{ url('/assets/images/logo.svg') }}" alt="Network" />
                        <h2>@yield('title')</h2>
                    </div>

                    <div class="card p-5 rounded-sm">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('layouts._js')
    @include('layouts._flash')
</body>
</html>
