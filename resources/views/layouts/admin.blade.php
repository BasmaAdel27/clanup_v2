<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - {{ $application_name }}</title>

    @include('layouts._favicons')
    @include('layouts._css')
</head>

<body>
    <div class="page">
        @include('layouts._header', ['admin' => true])

        <div class="navbar-expand-md sticky-top">
            <div class="collapse navbar-collapse" id="navbar-menu">
                <div class="navbar navbar-light">
                    <div class="container">
                        <ul class="navbar-nav">
                            <li class="nav-item {{ $page == 'dashboard' ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('admin.dashboard') }}">
                                    <span class="nav-link-title">
                                        {{ __('Dashboard') }}
                                    </span>
                                </a>
                            </li>
                            <li class="nav-item dropdown {{ in_array($page, ['plans', 'orders', 'subscriptions']) ? 'active' : '' }}">
                                <a class="nav-link dropdown-toggle" href="#navbar-base" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
                                    <span class="nav-link-title">
                                        {{ __('Subscriptions') }}
                                    </span>
                                </a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item {{ $page == 'plans' ? 'active' : '' }}" href="{{ route('admin.plans') }}">
                                        {{ __('Plans') }}
                                    </a>
                                    <a class="dropdown-item {{ $page == 'orders' ? 'active' : '' }}" href="{{ route('admin.orders') }}">
                                        {{ __('Orders') }}
                                    </a>
                                    <a class="dropdown-item {{ $page == 'subscriptions' ? 'active' : '' }}" href="{{ route('admin.subscriptions') }}">
                                        {{ __('Subscriptions') }}
                                    </a>
                                </div>
                            </li>
                            <li class="nav-item dropdown {{ in_array($page, ['groups', 'events', 'topic_categories', 'topics', 'users']) ? 'active' : '' }}">
                                <a class="nav-link dropdown-toggle" href="#navbar-base" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
                                    <span class="nav-link-title">
                                        {{ __('Manage') }}
                                    </span>
                                </a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item {{ $page == 'groups' ? 'active' : '' }}" href="{{ route('admin.groups') }}">
                                        {{ __('Groups') }}
                                    </a>
                                    <a class="dropdown-item {{ $page == 'events' ? 'active' : '' }}" href="{{ route('admin.events') }}">
                                        {{ __('Events') }}
                                    </a>
                                    <a class="dropdown-item {{ $page == 'topic_categories' ? 'active' : '' }}" href="{{ route('admin.topic_categories') }}">
                                        {{ __('Topic Categories') }}
                                    </a>
                                    <a class="dropdown-item {{ $page == 'topics' ? 'active' : '' }}" href="{{ route('admin.topics') }}">
                                        {{ __('Topics') }}
                                    </a>
                                    <a class="dropdown-item {{ $page == 'users' ? 'active' : '' }}" href="{{ route('admin.users') }}">
                                        {{ __('Users') }}
                                    </a>
                                </div>
                            </li>
                            <li class="nav-item dropdown {{ in_array($page, ['blog_categories', 'blogs', 'pages']) ? 'active' : '' }}">
                                <a class="nav-link dropdown-toggle" href="#navbar-base" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
                                    <span class="nav-link-title">
                                        {{ __('Pages') }}
                                    </span>
                                </a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item {{ $page == 'blog_categories' ? 'active' : '' }}" href="{{ route('admin.blog_categories') }}">
                                        {{ __('Blog Categories') }}
                                    </a>
                                    <a class="dropdown-item {{ $page == 'blogs' ? 'active' : '' }}" href="{{ route('admin.blogs') }}">
                                        {{ __('Blog') }}
                                    </a>
                                    <a class="dropdown-item {{ $page == 'pages' ? 'active' : '' }}" href="{{ route('admin.pages') }}">
                                        {{ __('Pages') }}
                                    </a>
                                </div>
                            </li>
                            <li class="nav-item dropdown {{ in_array($page, ['application_settings', 'location_settings', 'payment_settings', 'mail_settings', 'social_login_settings', 'company_settings', 'currencies', 'languages']) ? 'active' : '' }}">
                                <a class="nav-link dropdown-toggle" href="#navbar-base" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
                                    <span class="nav-link-title">
                                        {{ __('Settings') }}
                                    </span>
                                </a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item {{ $page == 'application_settings' ? 'active' : '' }}" href="{{ route('admin.settings', ['tab' => 'application']) }}">
                                        {{ __('Application') }}
                                    </a>
                                    <a class="dropdown-item {{ $page == 'location_settings' ? 'active' : '' }}" href="{{ route('admin.settings', ['tab' => 'location']) }}">
                                        {{ __('Location') }}
                                    </a>
                                    <a class="dropdown-item {{ $page == 'payment_settings' ? 'active' : '' }}" href="{{ route('admin.settings', ['tab' => 'payment']) }}">
                                        {{ __('Payment') }}
                                    </a>
                                    <a class="dropdown-item {{ $page == 'mail_settings' ? 'active' : '' }}" href="{{ route('admin.settings', ['tab' => 'mail']) }}">
                                        {{ __('Mail') }}
                                    </a>
                                    <a class="dropdown-item {{ $page == 'social_login_settings' ? 'active' : '' }}" href="{{ route('admin.settings', ['tab' => 'social']) }}">
                                        {{ __('Social Login') }}
                                    </a>
                                    <a class="dropdown-item {{ $page == 'company_settings' ? 'active' : '' }}" href="{{ route('admin.settings', ['tab' => 'company']) }}">
                                        {{ __('Company') }}
                                    </a>
                                    <a class="dropdown-item {{ $page == 'currencies' ? 'active' : '' }}" href="{{ route('admin.settings', ['tab' => 'currencies']) }}">
                                        {{ __('Currencies') }}
                                    </a>
                                    <a class="dropdown-item {{ $page == 'languages' ? 'active' : '' }}" href="{{ route('admin.languages') }}">
                                        {{ __('Languages') }}
                                    </a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="page-wrapper">
            @yield('content')

            <footer class="footer footer-transparent d-print-none">
                <div class="container">
                    <div class="row text-center align-items-center flex-row-reverse">
                        <div class="col-lg-auto ms-lg-auto">
                            <ul class="list-inline list-inline-dots mb-0">
                                <li class="list-inline-item"><a href="https://support.varuscreative.com/" class="link-secondary" target="_blank">{{ __('Documentation') }}</a></li>
                                <li class="list-inline-item"><a href="https://support.varuscreative.com/" class="link-secondary" target="_blank">{{ __('Support') }}</a></li>
                            </ul>
                        </div>
                        <div class="col-12 col-lg-auto mt-3 mt-lg-0">
                            <ul class="list-inline list-inline-dots mb-0">
                                <li class="list-inline-item">
                                    Copyright &copy; {{ date('Y') }}
                                    <a class="link-secondary">{{ get_system_setting('application_name') }}</a>
                                </li>
                                <li class="list-inline-item">
                                    Version {{ get_system_setting('version') }}
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    @stack('modals')
    @include('layouts._js')
    @include('layouts._flash')
</body>
</html>