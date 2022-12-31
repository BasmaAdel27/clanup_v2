@extends('layouts.admin', ['page' => 'payment_settings'])

@section('title', __('Payment Settings'))

@section('content')
    <div class="container">
        <div class="page-header d-print-none">
            <div class="row align-items-center">
                <div class="col">
                    <div class="page-pretitle">
                        {{ __('Settings') }}
                    </div>
                    <h1 class="page-title">
                        {{ __('Payment Settings') }}
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
    
                        <h3>{{ __('Defaults') }}</h3>
                        <div class="form-group mb-3 required">
                            <label for="application_currency">{{ __('Currency') }}</label> 
                            <select name="application_currency" class="form-control" required>
                                <option disabled selected>{{ __('Select Currency') }}</option>
                                @php $appCurrency = get_system_setting('application_currency') @endphp
                                @foreach(get_currencies_select2_array() as $option)
                                    <option value="{{ $option['code'] }}" {{ $appCurrency == $option['code'] ? 'selected=""' : '' }} >{{ $option['text'] }}</option>
                                @endforeach
                            </select>
                        </div>
        
                        <div class="form-group mb-3 required">
                            <label for="order_prefix">{{ __('Order Prefix') }}</label>
                            <input name="order_prefix" type="text" class="form-control" placeholder="{{ __('Order Prefix') }}" value="{{ get_system_setting('order_prefix') }}" required>
                        </div>
    
                        <div class="form-group mb-3 required">
                            <label for="grace_period">{{ __('Grace Period in Days') }}</label>
                            <input name="grace_period" type="number" class="form-control" placeholder="{{ __('Grace Period in Days') }}" value="{{ get_system_setting('grace_period') }}" required>
                            <small>{{ __('Number of days to deactive subscription features after the user subscription expired/ended.') }}</small>
                        </div>
    
                        <hr>
                        <h3>{{ __('Active Payment Gateway') }}</h3>
                        <div class="form-group mb-3 required">
                            <select name="active_payment_gateway" class="form-control" required>
                                <option value="dummy" {{ get_system_setting('active_payment_gateway') == 'dummy' ? 'selected=""' : '' }}>{{ __('Dummy') }}</option>
                                <option value="stripe" {{ get_system_setting('active_payment_gateway') == 'stripe' ? 'selected=""' : '' }}>{{ __('Stripe') }}</option>
                                <option value="paypal" {{ get_system_setting('active_payment_gateway') == 'paypal' ? 'selected=""' : '' }}>{{ __('Paypal') }}</option>
                            </select>
                        </div>
    
                        <hr>
                        <h3>{{ __('Stripe Settings') }}</h3>
                        <div class="form-group mb-3">
                            <label for="stripe_publishable_key">{{ __('Stripe Publishable Key') }}</label>
                            <input name="stripe_publishable_key" type="text" class="form-control" placeholder="{{ __('Stripe Publishable Key') }}" value="{{ get_system_setting('stripe_publishable_key') }}">
                        </div>
                        <div class="form-group mb-3">
                            <label for="stripe_secret_key">{{ __('Stripe Secret Key') }}</label>
                            <input name="stripe_secret_key" type="text" class="form-control" placeholder="{{ __('Stripe Secret Key') }}" value="{{ get_system_setting('stripe_secret_key') }}">
                        </div>
                        <hr>

                        <h3>{{ __('Paypal Settings') }}</h3>
                        <div class="form-group">
                            <div class="alert alert-danger" role="alert">
                                <i class="fas fa-exclamation-triangle"></i>
                                {{ __('Make sure to read the documentation if you are going to use Paypal as a payment provider.') }}
                                <a href="https://support.varuscreative.com/help-center/articles/1/1/getting-started-or-network" target="_blank">Click here</a>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="paypal_client_id">{{ __('Paypal Client ID') }}</label>
                            <input name="paypal_client_id" type="text" class="form-control" placeholder="{{ __('Paypal Client ID') }}" value="{{ get_system_setting('paypal_client_id') }}">
                        </div>
                        <div class="form-group mb-3">
                            <label for="paypal_client_secret">{{ __('Paypal Client Secret') }}</label>
                            <input name="paypal_client_secret" type="text" class="form-control" placeholder="{{ __('Paypal Client Secret') }}" value="{{ get_system_setting('paypal_client_secret') }}">
                        </div>
                        <div class="form-group mb-3">
                            <label for="paypal_app_id">{{ __('Paypal App ID') }}</label>
                            <input name="paypal_app_id" type="text" class="form-control" placeholder="{{ __('Paypal App ID') }}" value="{{ get_system_setting('paypal_app_id') }}">
                        </div>
                        <div class="form-group mb-3">
                            <label for="paypal_webhook_url">{{ __('Paypal Webhook URL') }}</label>
                            <input type="text" class="form-control" placeholder="{{ __('Paypal Webhook URL') }}" value="{{ route('webhooks.paypal', ['token' => get_system_setting('paypal_webhook_token')]) }}" readonly>
                            <small>{{ __('Use this url for Paypal Webhooks') }}</small>
                        </div>
                        <hr>
        
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">{{ __('Update Settings') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
