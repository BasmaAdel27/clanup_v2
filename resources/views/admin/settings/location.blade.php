@extends('layouts.admin', ['page' => 'location_settings'])

@section('title', __('Location Settings'))

@section('content')
    <div class="container">
        <div class="page-header d-print-none">
            <div class="row align-items-center">
                <div class="col">
                    <div class="page-pretitle">
                        {{ __('Settings') }}
                    </div>
                    <h1 class="page-title">
                        {{ __('Location Settings') }}
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
    
                        <h3>{{ __('Geocode & Autocomplete') }}</h3>
                        <div class="form-group mb-3">
                            <label for="google_places_api_key">{{ __('Google Places API Key') }}</label> 
                            <input name="google_places_api_key" type="text" class="form-control" placeholder="{{ __('Enter the api key') }}" value="{{ get_system_setting('google_places_api_key') }}">
                        </div>
                        <hr>
    
                        <h3>{{ __('Default Location') }}</h3>
                        <div class="form-group mb-3">
                            <label for="default_location_city_name">{{ __('City') }}</label> 
                            <input name="default_location_city_name" type="text" class="form-control" value="{{ get_system_setting('default_location_city_name') }}">
                        </div>
                        <div class="form-group mb-3">
                            <label for="default_location_country_name">{{ __('Country') }}</label> 
                            <input name="default_location_country_name" type="text" class="form-control" value="{{ get_system_setting('default_location_country_name') }}">
                        </div>
                        <div class="form-group mb-3">
                            <label for="default_location_latitude">{{ __('Latitude') }}</label> 
                            <input name="default_location_latitude" type="text" class="form-control" value="{{ get_system_setting('default_location_latitude') }}">
                        </div>
                        <div class="form-group mb-3">
                            <label for="default_location_longitude">{{ __('Longitude') }}</label> 
                            <input name="default_location_longitude" type="text" class="form-control" value="{{ get_system_setting('default_location_longitude') }}">
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
