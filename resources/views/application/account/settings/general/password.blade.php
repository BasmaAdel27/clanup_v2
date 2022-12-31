@extends('layouts.app', [
    'seo_title' => __('Password & Security Settings'),
    'page' => 'account.general.details'
])

@section('content')
    <section class="py-5">
        <div class="container">
            <div class="row gx-5">
                @include('application.account.settings._sidebar', ['page' => 'general'])

                <div class="col-lg-8">
                    <a href="{{ route('account.settings.general') }}">< {{ __('Back') }}</a>
                    <h1>{{ __('Password & Security') }}</h1>
                    <hr>

                    <form action="{{ route('account.settings.general.password.update') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="mb-4 col-12">
                                <label class="form-label" for="password-current">{{ __('Current Password') }}</label>
                                <input class="form-control" type="password" name="old_password" required>
                                @error('old_password')<small class="form-text text-danger">{{ $message }}</small>@enderror
                            </div>
                            <div class="mb-4 col-md-6">
                                <label class="form-label" for="password-new">{{ __('New Password') }}</label>
                                <input class="form-control" type="password" name="new_password" required>
                                @error('new_password')<small class="form-text text-danger">{{ $message }}</small>@enderror
                            </div>
                            <div class="mb-4 col-md-6">
                                <label class="form-label" for="password-confirm">{{ __('Confirm New Password') }}</label>
                                <input class="form-control" type="password" name="new_password_confirmation" required>
                                @error('new_password_confirmation')<small class="form-text text-danger">{{ $message }}</small>@enderror
                            </div>
                        </div>
                        <div class="text-end">
                            <button class="btn btn-primary" type="submit">{{ __('Update') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection