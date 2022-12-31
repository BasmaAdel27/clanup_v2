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

<body>
    @impersonating($guard = null)
        <div class="notification-top-bar notification-top-bar bg-dark">
            <p>{{ __('You are impersonating one of the user') }} <small><a href="{{ route('impersonate.leave') }}" class="text-primary">{{ __('Leave impersonation') }}</a></small></p>
        </div>
    @endImpersonating

    <div class="page">
        @include('layouts._header')

        <div class="page-wrapper">
            @yield('content')

            @include('layouts.._footer')
        </div>
    </div>

    @stack('modals')
    @livewire('common.share-modal')
    @include('layouts._js')
    @include('layouts._autocomplete', ['types' => []])
    @include('layouts._flash')
</body>
</html>