@extends('layouts.admin', ['page' => 'company_settings'])

@section('title', __('Company Settings'))

@section('content')
    <div class="container">
        <div class="page-header d-print-none">
            <div class="row align-items-center">
                <div class="col">
                    <div class="page-pretitle">
                        {{ __('Settings') }}
                    </div>
                    <h1 class="page-title">
                        {{ __('Company Settings') }}
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
    
                        <h3>{{ __('Company Address') }}</h3>
                        <div class="form-group mb-3">
                            <input name="company_address" type="text" class="form-control" placeholder="{{ __('Company Address') }}" value="{{ get_system_setting('company_address') }}">
                        </div>
                        <hr>
    
                        <h3>{{ __('Social Media Accounts') }}</h3>
                        <div class="form-group mb-3">
                            <label for="facebook_link">{{ __('Facebook Link') }}</label>
                            <input name="facebook_link" type="text" class="form-control" placeholder="{{ __('Facebook Link') }}" value="{{ get_system_setting('facebook_link') }}">
                        </div>
    
                        <div class="form-group mb-3">
                            <label for="twitter_link">{{ __('Twitter Link') }}</label>
                            <input name="twitter_link" type="text" class="form-control" placeholder="{{ __('Twitter Link') }}" value="{{ get_system_setting('twitter_link') }}">
                        </div>
    
                        <div class="form-group mb-3">
                            <label for="instagram_link">{{ __('Instagram Link') }}</label>
                            <input name="instagram_link" type="text" class="form-control" placeholder="{{ __('Instagram Link') }}" value="{{ get_system_setting('instagram_link') }}">
                        </div>
    
                        <div class="form-group mb-3">
                            <label for="pinterest_link">{{ __('Pinterest Link') }}</label>
                            <input name="pinterest_link" type="text" class="form-control" placeholder="{{ __('Pinterest Link') }}" value="{{ get_system_setting('pinterest_link') }}">
                        </div>
    
                        <div class="form-group mb-3">
                            <label for="linkedin_link">{{ __('Linkedin Link') }}</label>
                            <input name="linkedin_link" type="text" class="form-control" placeholder="{{ __('Linkedin Link') }}" value="{{ get_system_setting('linkedin_link') }}">
                        </div>
    
                        <div class="form-group mb-3">
                            <label for="youtube_link">{{ __('Youtube Link') }}</label>
                            <input name="youtube_link" type="text" class="form-control" placeholder="{{ __('Youtube Link') }}" value="{{ get_system_setting('youtube_link') }}">
                        </div>
    
                        <div class="form-group mb-3">
                            <label for="vimeo_link">{{ __('Vimeo Link') }}</label>
                            <input name="vimeo_link" type="text" class="form-control" placeholder="{{ __('Vimeo Link') }}" value="{{ get_system_setting('vimeo_link') }}">
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
