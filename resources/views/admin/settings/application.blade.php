@extends('layouts.admin', ['page' => 'application_settings'])

@section('title', __('Application Settings'))

@section('content')
    <div class="container">
        <div class="page-header d-print-none">
            <div class="row align-items-center">
                <div class="col">
                    <div class="page-pretitle">
                        {{ __('Settings') }}
                    </div>
                    <h1 class="page-title">
                        {{ __('Application Settings') }}
                    </h1>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container">
            <div class="row">
                <div class="col-12 card">
                    <form class="card-body" action="{{ route('admin.settings.update', ['tab' => $tab]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @include('layouts._form_errors')
    
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label" for="logo">{{ __('Logo') }}</label>
                                    @if (get_system_setting('application_logo'))
                                        <p>
                                            <img class="img-thumbnail h-110px" src="{{ asset(get_system_setting('application_logo')) }}">
                                        </p>
                                    @endif
                                    <input class="form-control" name="logo" type="file" accept="image/png, image/jpeg, image/svg+xml">
                                    <small>{{ __('Recommended size: 654x191px') }}</small>
                                </div>
                            </div>
    
                            <div class="col-12 col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label" for="favicon">{{ __('Favicon') }}</label>
                                    @if (get_system_setting('application_favicon'))
                                        <p>
                                            <img class="img-thumbnail h-110px" src="{{ asset(get_system_setting('application_favicon')) }}">
                                        </p>
                                    @endif
                                    <input class="form-control" name="favicon" type="file" accept="image/png, image/jpeg">
                                    <small>{{ __('Recommended size: 192x192px') }}</small>
                                </div>
                            </div>
                        </div>
    
                        <div class="form-group mb-3 required">
                            <label for="application_name">{{ __('Application Name') }}</label>
                            <input name="application_name" type="text" class="form-control" placeholder="{{ __('Application Name') }}" value="{{ $application_name }}" required>
                        </div>
    
                        <div class="form-group mb-3 required">
                            <label for="meta_description">{{ __('Meta Description') }}</label>
                            <input name="meta_description" type="text" class="form-control" placeholder="{{ __('Meta Description') }}" value="{{ get_system_setting('meta_description') }}" required>
                        </div>
    
                        <div class="form-group mb-3 required">
                            <label for="meta_keywords">{{ __('Meta Keywords') }}</label>
                            <input name="meta_keywords" type="text" class="form-control" placeholder="{{ __('Meta Keywords') }}" value="{{ get_system_setting('meta_keywords') }}" required>
                        </div>
    
                        <div class="form-group mb-3">
                            <label for="google_recapthca_key">{{ __('Google ReCaptcha Key') }}</label> 
                            <input name="google_recapthca_key" type="text" class="form-control" placeholder="{{ __('Enter the key') }}" value="{{ get_system_setting('google_recapthca_key') }}">
                        </div>
    
                        <div class="form-group mb-3">
                            <label for="google_recapthca_secret_key">{{ __('Google ReCaptcha Secret Key') }}</label> 
                            <input name="google_recapthca_secret_key" type="text" class="form-control" placeholder="{{ __('Enter the secret key') }}" value="{{ get_system_setting('google_recapthca_secret_key') }}">
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
