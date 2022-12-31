@extends('layouts.auth', [
    'seo_title' => __('Login'),
])

@section('content')
    @include('layouts._form_errors')

    <form id="auth-form" action="{{ route('login') }}" method="POST" class="card card-md">
        @csrf
        @honeypot
        <input type="hidden" name="timezone" id="timezone">
        @if (request()->get('_redirect'))
            <input type="hidden" name="_redirect" value="{{ request()->get('_redirect') }}">
        @endif

        <div class="card-body">
            <h1 class="card-title mb-4">{{ __('Login to your account') }}</h1>
            <div class="mb-3">
                <label class="form-label">{{ __('Email') }}</label>
                <input class="form-control" name="email" type="email" placeholder="name@address.com" value="{{ old('email') }}" required>
            </div>

            <div class="mb-2">
                <label class="form-label">
                    {{ __('Password') }}
                    <span class="form-label-description">
                        <a href="{{ route('password.request') }}" tabindex="-1">{{ __('Forgot your password?') }}</a>
                    </span>
                </label>
                <input class="form-control" name="password" placeholder="{{ __('Password') }}" type="password" required />
            </div>

            <div class="mb-2">
                <label class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" checked />
                    <span class="form-check-label">{{ __('Remember me on this device') }}</span>
                </label>
            </div>

            <div class="form-footer">
                @if(\App\Models\SystemSetting::isRecaptchaActive())
                    <button class="btn btn-primary w-100 g-recaptcha" data-sitekey="{{ get_system_setting('google_recapthca_key') }}" data-callback="onSubmit" data-action="submit">{{ __('Login') }}</button>
                @else
                    <button class="btn btn-primary w-100" type="submit">{{ __('Login') }}</button>
                @endif
            </div>
        </div>

        @include('auth._login_with_providers')
    </form>

    <div class="text-center text-muted mt-3">
        {{ __('Don\'t have an account yet?') }} <a href="{{ route('register') }}" tabindex="-1">{{ __('Register') }}</a>
    </div>
@endsection

@push('page_body_scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.19.0/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.13/moment-timezone-with-data.js"></script>
    <script>
        $(document).ready(function() {
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
