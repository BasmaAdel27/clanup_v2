@extends('layouts.app', [
    'seo_title' => __('Account Settings'),
    'page' => 'account.general'
])

@section('content')
    <section class="py-5">
        <div class="container">
            <div class="row gx-5">
                @include('application.account.settings._sidebar', ['page' => 'general'])

                <div class="col-lg-8">
                    <h1>{{ __('General Settings') }}</h1>
                    <p class="text-muted">{{ __('Manage your profile info and settings here') }}</p>
                    <hr>

                    <div class="text-block mb-4">
                        <div class="row d-flex align-items-center mb-2">
                            <div class="col-sm-9">
                                <h5 class="h2">{{ __('Personal Details') }}</h5>
                            </div>
                            <div class="col-sm-3 text-end">
                                <a class="btn btn-link fs-3" href="{{ route('account.settings.general.details') }}">{{ __('Update') }}</a>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <p class="text-muted mb-0">
                                <i class="fa fa-id-card fa-fw me-2"></i>{{ $auth_user->full_name }}<br>
                                <i class="fa fa-envelope-open fa-fw me-2"></i>{{ $auth_user->email }}<br>
                                <i class="fa fa-phone fa-fw me-2"></i>{{ $auth_user->phone ?? '-' }}<br>
                                <i class="fa fa-birthday-cake fa-fw me-2"></i>{{ $auth_user->birthdate ?? '-' }}<br>
                                <i class="fa fa-venus-mars fa-fw me-2"></i>{{ $auth_user->getSetting('gender') ? __($auth_user->getSetting('gender')) : '-' }}<br>
                            </p>
                            <label for="avatar">
                                <img class="avatar avatar-lg shadow-0 border" src="{{ $auth_user->avatar }}" alt="{{ $auth_user->full_name }}">
                            </label>
                        </div>
                    </div>

                    <hr>

                    <div class="text-block">
                        <div class="row d-flex align-items-center mb-2">
                            <div class="col-sm-9">
                                <h5 class="h2">{{ __('Address') }}</h5>
                            </div>
                            <div class="col-sm-3 text-end">
                                <a class="btn btn-link fs-3" href="{{ route('account.settings.general.address') }}">{{ __('Update') }}</a>
                            </div>
                        </div>
                        <div class="d-flex align-items-center text-muted"> <i class="fa fa-address-book fa-fw flex-shrink-0 me-2"></i>
                            <div>
                                {{ $auth_user->getSetting('city') ? $auth_user->getSetting('city') . ', ' : '-' }} {{ $auth_user->getSetting('country') }}<br>
                                @if ($auth_user->getSetting('hometown'))
                                    <strong>{{ __('Hometown : ') }} {{ $auth_user->getSetting('hometown') }}</strong>
                                @endif
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="text-block">
                        <div class="row d-flex align-items-center mb-2">
                            <div class="col-sm-9">
                                <h5 class="h2">{{ __('Social Media Accounts') }}</h5>
                            </div>
                            <div class="col-sm-3 text-end">
                                <a class="btn btn-link fs-3" href="{{ route('account.settings.general.social') }}">{{ __('Update') }}</a>
                            </div>
                        </div>
                        <p class="text-muted mb-0">
                            <i class="fab fa-facebook fa-fw me-2"></i>{{ $auth_user->getSetting('facebook') ?? '-' }}<br>
                            <i class="fab fa-instagram fa-fw me-2"></i>{{ $auth_user->getSetting('instagram') ?? '-' }}<br>
                            <i class="fab fa-twitter fa-fw me-2"></i>{{ $auth_user->getSetting('twitter') ?? '-' }}<br>
                            <i class="fab fa-linkedin fa-fw me-2"></i>{{ $auth_user->getSetting('linkedin') ?? '-' }}<br>
                        </p>
                    </div>

                    <hr>

                    <div class="text-block">
                        <div class="row d-flex align-items-center mb-2">
                            <div class="col-sm-9">
                                <h5 class="h2">{{ __('Password') }}</h5>
                                <p class="text-muted"><u>{{ __('Last updated') }}</u>: {{ $auth_user->getSetting('last_password_update') ? \Carbon\Carbon::parse($auth_user->getSetting('last_password_update'))->diffForHumans() : __('Never') }}</p>
                            </div>
                            <div class="col-sm-3 text-end">
                                <a class="btn btn-link fs-3" href="{{ route('account.settings.general.password') }}">{{ __('Change') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection