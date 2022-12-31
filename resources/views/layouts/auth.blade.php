<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @include('layouts._seo')
    @include('layouts._favicons')
    @include('layouts._css')
</head>

<body class="border-top-wide border-primary d-flex flex-column">
    <div class="page page-center">
        <div class="container-tight py-4">
            <div class="text-center mb-4">
                <a href="/" class="navbar-brand navbar-brand-autodark">
                    <img class="w-100" src="{{ asset(get_system_setting('application_logo')) }}" alt="{{ $application_name }}">
                </a>
            </div>

            @yield('content')
        </div>
    </div>

    @stack('modals')
    @include('layouts._js')
</body>
</html>