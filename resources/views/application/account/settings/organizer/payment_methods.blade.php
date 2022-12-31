@extends('layouts.app', [
    'seo_title' => __('Payment Methods'),
    'page' => 'account.organizer'
])

@section('content')
    <section class="py-5">
        <div class="container">
            <div class="row gx-5">
                @include('application.account.settings._sidebar', ['page' => 'organizer'])

                <div class="col-lg-8">
                    <a href="{{ route('account.settings.organizer') }}">< {{ __('Back') }}</a>
                    <h1>{{ __('Saved Payment Methods') }}</h1>
                    
                    @if (\App\Models\SystemSetting::isStripeActive())
                        @include('application.checkout.stripe._payment_methods')
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection