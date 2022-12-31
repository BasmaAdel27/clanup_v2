@extends('layouts.app', [
    'seo_title' => __('Profile Settings'),
    'page' => 'account.general.details'
])

@section('content')
    <section class="py-5">
        <div class="container">
            <div class="row gx-5">
                @include('application.account.settings._sidebar', ['page' => 'general'])

                <div class="col-lg-8">
                    <a href="{{ route('account.settings.general') }}">< {{ __('Back') }}</a>
                    <h1>{{ __('Profile Settings') }}</h1>
                    <hr>
                    
                    <form action="{{ route('account.settings.general.details.update') }}" enctype="multipart/form-data" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-12 d-flex flex-row mb-4">
                                <span class="avatar avatar-lg border" style="background-image: url({{ $auth_user->avatar }})"></span>
                                <div class="ms-3">
                                    <label class="form-label" for="profile_picture">{{ __('Profile Picture') }}</label>
                                    <input class="form-control" name="profile_picture" type="file" accept="image/png, image/jpeg">
                                    @error('profile_picture')<small class="form-text text-danger">{{ $message }}</small>@enderror
                                </div>
                            </div>
                            <div class="mb-4 col-md-6">
                                <label class="form-label" for="first_name">{{ __('First Name') }}</label>
                                <input class="form-control" type="text" name="first_name" value="{{ $auth_user->first_name }}" required>
                                @error('first_name')<small class="form-text text-danger">{{ $message }}</small>@enderror
                            </div>
                            <div class="mb-4 col-md-6">
                                <label class="form-label" for="last_name">{{ __('Last Name') }}</label>
                                <input class="form-control" type="text" name="last_name" value="{{ $auth_user->last_name }}" required>
                                @error('last_name')<small class="form-text text-danger">{{ $message }}</small>@enderror
                            </div>
                            <div class="mb-4 col-md-6">
                                <label class="form-label" for="email">{{ __('Email address') }}</label>
                                <input class="form-control" type="email" name="email" value="{{ $auth_user->email }}" required>
                                @error('email')<small class="form-text text-danger">{{ $message }}</small>@enderror
                            </div>
                            <div class="mb-4 col-md-6">
                                <label class="form-label" for="phone">{{ __('Phone number') }}</label>
                                <input class="form-control" type="text" name="phone" value="{{ $auth_user->phone }}">
                                @error('phone')<small class="form-text text-danger">{{ $message }}</small>@enderror
                            </div>
                            <div class="mb-4 col-md-6">
                                <label class="form-label" for="birthdate">{{ __('Birthdate') }}</label>
                                <input id="birthdate" class="form-control bg-white" name="birthdate" type="text" value="{{ $auth_user->getSetting('birthdate') }}">
                                @error('birthdate')<small class="form-text text-danger">{{ $message }}</small>@enderror
                            </div>
                            <div class="mb-4 col-md-6">
                                <label class="form-label" for="gender">{{ __('Gender') }}</label>
                                <select class="form-select" name="gender">
                                    <option value="">{{ __('Gender') }}</option>
                                    <option value="male" {{ $auth_user->getSetting('gender') == 'male' ? 'selected=""' : '' }}>{{ __('Male') }}</option>
                                    <option value="female" {{ $auth_user->getSetting('gender') == 'female' ? 'selected=""' : '' }}>{{ __('Female') }}</option>
                                    <option value="none" {{ $auth_user->getSetting('gender') == 'none' ? 'selected=""' : '' }}>{{ __('None of these choices') }}</option>
                                </select>
                                @error('gender')<small class="form-text text-danger">{{ $message }}</small>@enderror
                            </div>
                            <div class="mb-4 col-md-6">
                                <label class="form-label" for="username">{{ __('Username') }}</label>
                                <input class="form-control" name="username" type="text" value="{{ $auth_user->username }}" required>
                                @error('username')<small class="form-text text-danger">{{ $message }}</small>@enderror
                            </div>
                            <div class="mb-4 col-md-6">
                                <label class="form-label" for="timezone">{{ __('Timezone') }}</label>
                                <select class="form-select" name="timezone">
                                    @foreach (timezone_identifiers_list() as $tz)
                                        <option value="{{ $tz }}"{{ $tz == $auth_user->timezone ? ' selected' : '' }}>{{ $tz }}</option>
                                    @endforeach
                                </select>
                                @error('timezone')<small class="form-text text-danger">{{ $message }}</small>@enderror
                            </div>
                            <div class="mb-4 col-md-12">
                                <label class="form-label" for="bio">{{ __('Bio') }}</label>
                                <textarea class="form-control" name="bio" rows="3">{{ $auth_user->getSetting('bio') }}</textarea>
                                @error('bio')<small class="form-text text-danger">{{ $message }}</small>@enderror
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

@push('page_body_scripts')
    <script>
        flatpickr("#birthdate", {
            dateFormat: "Y-m-d",
            altInput: true, 
            altFormat: "F j, Y",
        });
    </script>
@endpush