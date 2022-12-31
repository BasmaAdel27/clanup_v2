@extends('layouts.app', [
    'seo_title' => __('Checkout'),
])

@section('content')
    <section class="container py-5">
        <div class="row">
            <div class="col-lg-6 offset-md-2 mx-auto">
                <h1 class="mb-4">{{ __('Subscribe to :plan_name Plan', ['plan_name' => $plan->name]) }}</h1>

                @if (\App\Models\SystemSetting::isStripeActive())
                    @include('application.checkout.stripe._payment_elements')
                @endif

                @if (\App\Models\SystemSetting::isPaypalActive())
                    @include('application.checkout.paypal._payment_elements')
                @endif
            </div>

            <div class="col-lg-4 mx-auto">
                <div class="card mb-5 mb-lg-0 border-0 shadow">
                    <div class="card-body">
                        <h2 class="text-base subtitle text-center text-primary py-3">{{ $plan->name }}</h2>
                        <p class="text-muted text-center">
                            <span class="h1 text-dark">{{ money($plan->price, $plan->currency, true) }}</span>
                            <span class="ms-2 text-capitalize">/ {{ __($plan->invoice_interval) }}</span>
                        </p>
                        <hr>
                        <p>{{ __('Your subscription includes:') }}</p>
                        <ul class="fa-ul my-4">
                            <li class="mb-3">
                                <span class="fa-li text-primary"><i class="fas fa-check"></i></span>
                                {{ __('Cancel your subscription anytime you want') }}
                            </li>
                            <li class="mb-3">
                                <span class="fa-li text-primary"><i class="fas fa-check"></i></span>
                                {{ __('Promotion of your new group to potential members') }}
                            </li>
                            <li class="mb-3">
                                <span class="fa-li text-primary"><i class="fas fa-check"></i></span>
                                {{ __('Quick and easy tools for scheduling events and staying in touch with your members') }}
                            </li>
                            <li class="mb-3">
                                <span class="fa-li text-primary"><i class="fas fa-check"></i></span>
                                {{ __('Access to customer support 7 days a week') }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
