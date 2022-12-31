@extends('layouts.admin', ['page' => 'mail_settings'])

@section('title', __('Mail Settings'))

@section('content')
    <div class="container">
        <div class="page-header d-print-none">
            <div class="row align-items-center">
                <div class="col">
                    <div class="page-pretitle">
                        {{ __('Settings') }}
                    </div>
                    <h1 class="page-title">
                        {{ __('Mail Settings') }}
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
                        <h3>{{ __('SMTP Settings') }}</h3>
                        <input name="mail_mailer" type="hidden" class="form-control" value="{{ env('MAIL_MAILER') }}">
    
                        <div class="form-group mb-3">
                            <label for="mail_host">{{ __('Mail Host') }}</label>
                            <input name="mail_host" type="text" class="form-control" placeholder="{{ __('Mail Host') }}" value="{{ env('MAIL_HOST') }}">
                        </div>
    
                        <div class="form-group mb-3">
                            <label for="mail_port">{{ __('Mail Port') }}</label>
                            <input name="mail_port" type="number" class="form-control" placeholder="{{ __('Mail Port') }}" value="{{ env('MAIL_PORT') }}">
                        </div>
    
                        <div class="form-group mb-3">
                            <label for="mail_encryption">{{ __('Mail Encryption') }}</label>
                            <select name="mail_encryption" class="form-control">
                                <option value="" {{ env('MAIL_ENCRYPTION') == '' ? 'selected=""' : '' }}>{{ __('None') }}</option>
                                <option value="tls" {{ env('MAIL_ENCRYPTION') == 'tls' ? 'selected=""' : '' }}>TLS</option>
                                <option value="ssl" {{ env('MAIL_ENCRYPTION') == 'ssl' ? 'selected=""' : '' }}>SSL</option>                            
                            </select>
                        </div>
    
                        <div class="form-group mb-3">
                            <label for="mail_username">{{ __('Username') }}</label>
                            <input name="mail_username" type="text" class="form-control" placeholder="{{ __('Username') }}" value="{{ env('MAIL_USERNAME') }}">
                        </div>
    
                        <div class="form-group mb-3">
                            <label for="mail_password">{{ __('Password') }}</label>
                            <input name="mail_password" type="text" class="form-control" placeholder="{{ __('Password') }}" value="{{ env('MAIL_PASSWORD') }}">
                        </div>
    
                        <div class="form-group mb-3">
                            <label for="mail_from_address">{{ __('From Email Address') }}</label>
                            <input name="mail_from_address" type="text" class="form-control" placeholder="{{ __('From Email Address') }}" value="{{ env('MAIL_FROM_ADDRESS') }}">
                        </div>
    
                        <div class="form-group mb-3">
                            <label for="mail_from_name">{{ __('From Name') }}</label>
                            <input name="mail_from_name" type="text" class="form-control" placeholder="{{ __('From Name') }}" value="{{ env('MAIL_FROM_NAME') }}">
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
