@extends('layouts.auth', [
    'seo_title' => __('Register'),
])

@section('content')
    @include('layouts._form_errors')

    <form id="auth-form" action="{{ route('register') }}" method="POST" class="card card-md">
        @csrf
        @honeypot
        <input type="hidden" name="timezone" id="timezone">
        @if (request()->get('_redirect'))
            <input type="hidden" name="_redirect" value="{{ request()->get('_redirect') }}">
        @endif

        <div class="card-body">
            <h1 class="card-title mb-4">{{ __('Create an account') }}</h1>

            <div class="row mb-4">
                <div class="col-md-6">
                    <label class="form-label" for="first_name">{{ __('First name') }}</label>
                    <input class="form-control" name="first_name" type="text" placeholder="{{ __('First name') }}" value="{{ old('first_name') }}" required />
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="last_name">{{ __('Last name') }}</label>
                    <input class="form-control" name="last_name" type="text" placeholder="{{ __('Last name') }}" value="{{ old('last_name') }}" required />
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label" for="email">{{ __('Email') }}</label>
                <input class="form-control" name="email" type="email" placeholder="name@address.com" value="{{ old('email') }}" required />
            </div>

            <div class="mb-4">
                <label class="form-label" for="password">{{ __('Password') }}</label>
                <input class="form-control" name="password" placeholder="{{ __('Password') }}" type="password" required />
            </div>

            <div class="mb-4">
                <label class="form-label" for="password_confirmation">{{ __('Confirm Password') }}</label>
                <input class="form-control" name="password_confirmation" placeholder="{{ __('Confirm Password') }}" type="password" required />
            </div>

            <div class="form-footer">
                @if(\App\Models\SystemSetting::isRecaptchaActive())
                    <button class="btn btn-primary w-100 g-recaptcha" data-sitekey="{{ get_system_setting('google_recapthca_key') }}" data-callback="onSubmit" data-action="submit">{{ __('Register') }}</button>
                @else
                    <button class="btn btn-primary w-100" type="submit">{{ __('Register') }}</button>
                @endif

                @if(\App\Models\SystemSetting::isTermsActive())
                    <p class="text-center mt-3">
                        <small class="text-center text-muted">{!! __("By clicking Register, you agree to our <a target='_blank' href='/pages/terms'>Terms</a> and <a target='_blank' href='/pages/privacy'>Privacy Policy</a>") !!}</small>
                    </p>
                @endif
            </div>
        </div>

        @include('auth._login_with_providers')
    </form>

    <div class="text-center text-muted mt-3">
        {{ __('Have an account?') }} <a href="{{ route('login') }}" tabindex="-1">{{ __('Login') }}</a>
    </div>
@endsection

@push('page_body_scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.19.0/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.13/moment-timezone-with-data.js"></script>
    <script>
        $( document ).ready(function() {
            var timezone = moment.tz.guess();
            $('#timezone').val(timezone);
        });
    </script>

    @if (\App\Models\SystemSetting::isRecaptchaActive())
        <script src="https://www.google.com/recaptcha/api.js"></script>
        <script>
            function onSubmit(token) {
                document.getElementById("auth-form").submit();
            }
        </script>
    @endif
@endpush
