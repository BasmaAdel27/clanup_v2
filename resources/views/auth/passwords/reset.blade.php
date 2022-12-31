@extends('layouts.auth', [
    'seo_title' => __('Reset Your Password'),
])

@section('content')
    @include('layouts._form_errors')

    <form id="auth-form" action="{{ route('password.update') }}" method="POST" class="card card-md">
        @csrf
        @honeypot
        <input type="hidden" name="token" value="{{ $token }}">
        <input type="hidden" name="email" value="{{ $email }}">

        <div class="card-body">
            <h1 class="card-title mb-4">{{ __('Reset Your Password') }}</h1>

            <div class="mb-4">
                <label class="form-label" for="password">{{ __('New password') }}</label>
                <input class="form-control" name="password" placeholder="{{ __('password') }}" type="password" autocomplete="new-password" required data-msg="{{ __('Please enter your password') }}" />
            </div>
    
            <div class="mb-4">
                <label class="form-label" for="password">{{ __('Confirm password') }}</label>
                <input class="form-control" name="password_confirmation" placeholder="{{ __('Confirm password') }}" type="password" autocomplete="password_confirmation" required data-msg="{{ __('Please confirm your password') }}" />
            </div>
            
            <div class="form-footer">
                <button class="btn btn-primary w-100" type="submit">{{ __('Reset password') }}</button>
            </div>
        </div>

        @include('auth._login_with_providers')
    </form>

    <div class="text-center text-muted mt-3">
        {{ __('Don\'t have an account yet?') }} <a href="{{ route('register') }}" tabindex="-1">{{ __('Register') }}</a>
    </div>
@endsection 