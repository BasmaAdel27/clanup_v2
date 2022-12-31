@extends('layouts.auth', [
    'seo_title' => __('Reset Password'),
])

@section('content')
    @include('layouts._form_errors')

    <form id="auth-form" action="{{ route('password.email') }}" method="POST" class="card card-md">
        @csrf
        @honeypot

        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif

        <div class="card-body">
            <h1 class="card-title mb-4">{{ __('Reset Your Password') }}</h1>

            <div class="mb-4">
                <label class="form-label" for="email">{{ __('Email') }}</label>
                <input class="form-control" name="email" type="email" placeholder="name@address.com" value="{{ old('email') }}" required autofocus />
            </div>
    
            <div class="form-footer">
                <button class="btn btn-primary w-100" type="submit">{{ __('Send reset link') }}</button>
            </div>
        </div>

        @include('auth._login_with_providers')
    </form>

    <div class="text-center text-muted mt-3">
        {{ __('Return to login') }} <a href="{{ route('login') }}" tabindex="-1">{{ __('Login') }}</a>
    </div>
@endsection
