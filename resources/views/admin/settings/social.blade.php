@extends('layouts.admin', ['page' => 'social_login_settings'])

@section('title', __('Social Login Settings'))

@section('content')
    <div class="container">
        <div class="page-header d-print-none">
            <div class="row align-items-center">
                <div class="col">
                    <div class="page-pretitle">
                        {{ __('Settings') }}
                    </div>
                    <h1 class="page-title">
                        {{ __('Social Login Settings') }}
                    </h1>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container">
            <div class="row">
                <div class="col-12 card">
                    <form class="card-body" action="{{ route('admin.settings.update', ['tab' => $tab]) }}" method="POST">
                        @csrf
                        @include('layouts._form_errors')
    
                        <h3>{{ __('Facebook Login') }}</h3>
                        <div class="form-group mb-3">
                            <label for="FACEBOOK_CLIENT_ID">{{ __('Facebook Client ID') }}</label>
                            <input name="FACEBOOK_CLIENT_ID" type="text" class="form-control" placeholder="{{ __('Facebook Client ID') }}" value="{{ env('FACEBOOK_CLIENT_ID') }}">
                        </div>
    
                        <div class="form-group mb-3">
                            <label for="FACEBOOK_CLIENT_SECRET">{{ __('Facebook Client Secret') }}</label>
                            <input name="FACEBOOK_CLIENT_SECRET" type="text" class="form-control" placeholder="{{ __('Facebook Client Secret') }}" value="{{ env('FACEBOOK_CLIENT_SECRET') }}">
                        </div>
                        <hr>
    
                        <h3>{{ __('Twitter Login') }}</h3>
                        <div class="form-group mb-3">
                            <label for="TWITTER_CLIENT_ID">{{ __('Twitter Client ID') }}</label>
                            <input name="TWITTER_CLIENT_ID" type="text" class="form-control" placeholder="{{ __('Twitter Client ID') }}" value="{{ env('TWITTER_CLIENT_ID') }}">
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="TWITTER_CLIENT_SECRET">{{ __('Twitter Client Secret') }}</label>
                            <input name="TWITTER_CLIENT_SECRET" type="text" class="form-control" placeholder="{{ __('Twitter Client Secret') }}" value="{{ env('TWITTER_CLIENT_SECRET') }}">
                        </div>
                        <hr>
    
                        <h3>{{ __('Google Login') }}</h3>
                        <div class="form-group mb-3">
                            <label for="GOOGLE_CLIENT_ID">{{ __('Google Client ID') }}</label>
                            <input name="GOOGLE_CLIENT_ID" type="text" class="form-control" placeholder="{{ __('Google Client ID') }}" value="{{ env('GOOGLE_CLIENT_ID') }}">
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="GOOGLE_CLIENT_SECRET">{{ __('Google Client Secret') }}</label>
                            <input name="GOOGLE_CLIENT_SECRET" type="text" class="form-control" placeholder="{{ __('Google Client Secret') }}" value="{{ env('GOOGLE_CLIENT_SECRET') }}">
                        </div>
                        <hr>
    
                        <h3>{{ __('Linkedin Login') }}</h3>
                        <div class="form-group mb-3">
                            <label for="LINKEDIN_CLIENT_ID">{{ __('Linkedin Client ID') }}</label>
                            <input name="LINKEDIN_CLIENT_ID" type="text" class="form-control" placeholder="{{ __('Linkedin Client ID') }}" value="{{ env('LINKEDIN_CLIENT_ID') }}">
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="LINKEDIN_CLIENT_SECRET">{{ __('Linkedin Client Secret') }}</label>
                            <input name="LINKEDIN_CLIENT_SECRET" type="text" class="form-control" placeholder="{{ __('Linkedin Client Secret') }}" value="{{ env('LINKEDIN_CLIENT_SECRET') }}">
                        </div>
    
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">{{ __('Update Settings') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
