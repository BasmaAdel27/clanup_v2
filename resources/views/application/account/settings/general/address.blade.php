@extends('layouts.app', [
    'seo_title' => __('Location Settings'),
    'page' => 'account.general.details'
])

@section('content')
    <section class="py-5">
        <div class="container">
            <div class="row gx-5">
                @include('application.account.settings._sidebar', ['page' => 'general'])

                <div class="col-lg-8">
                    <a href="{{ route('account.settings.general') }}">< {{ __('Back') }}</a>
                    <h1>{{ __('Location Settings') }}</h1>
                    <hr>

                    <form action="{{ route('account.settings.general.address.update') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="mb-4 col-md-6">
                                <label class="form-label" for="city">{{ __('City/State') }}</label>
                                <input class="form-control" type="text" name="city" value="{{ $auth_user->getSetting('city') }}">
                                @error('city')<small class="form-text text-danger">{{ $message }}</small>@enderror
                            </div>
                            <div class="mb-4 col-md-6">
                                <label class="form-label" for="country">{{ __('Country') }}</label>
                                <select class="form-control" name="country">
                                    <option value="" selected hidden>{{ __('Country') }}</option>
                                    @foreach(get_countries_select2_array() as $option)
                                        <option value="{{ $option['text'] }}" {{ $auth_user->getSetting('country') == $option['text'] ? 'selected=""' : '' }}>{{ $option['text'] }}</option>
                                    @endforeach
                                </select>
                                @error('country')<small class="form-text text-danger">{{ $message }}</small>@enderror
                            </div>
                            <div class="mb-4 col-md-6">
                                <label class="form-label" for="hometown">{{ __('Hometown') }}</label>
                                <input class="form-control" type="text" name="hometown" value="{{ $auth_user->getSetting('hometown') }}">
                                @error('hometown')<small class="form-text text-danger">{{ $message }}</small>@enderror
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