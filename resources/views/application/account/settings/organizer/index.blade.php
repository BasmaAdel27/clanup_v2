@extends('layouts.app', [
    'seo_title' => __('Organizer Settings'),
    'page' => 'account.organizer'
])

@section('content')
    <section class="py-5">
        <div class="container">
            <div class="row gx-5">
                @include('application.account.settings._sidebar', ['page' => 'organizer'])

                <div class="col-lg-8">
                    <h1 class="mb-4">{{ __('Organizer Subscription') }}</h1>
                    <hr>

                    @if ($current_subscription)
                        @if ($current_subscription->isActive())
                            <h3>{!! __('You are currently on <strong>:plan_name</strong> subscription plan', ['plan_name' => $current_subscribed_plan->name]) !!}</h3>
                            <ul>
                                @if ($last_order)
                                    <li>{!! 
                                        __('Your last payment of :price was received on <strong>:date</strong>', [
                                            'price' => money($last_order->amount, $last_order->currency, true),
                                            'date' => $last_order->created_at->format('M d, Y')
                                        ])
                                    !!}</li>
                                @endif

                                <li>{!! 
                                    __('Your next payment of :price for <strong>:plan_name</strong> plan will be charged on <strong>:date</strong>', [
                                        'price' => money($current_subscribed_plan->price, $current_subscribed_plan->currency, true),
                                        'plan_name' => $current_subscribed_plan->name,
                                        'date' => $current_subscription->ends_at->format('M d, Y')
                                    ]) 
                                !!}</li>
                            </ul>
                            <a class="btn btn-primary" href="{{ route('checkout.plans') }}">{{ __('Change subscription plan') }}</a>
                            <hr>
                            <a href="{{ route('account.settings.organizer.payment_history') }}">{{ __('Payment history') }}</a>
                            @if (\App\Models\SystemSetting::isStripeActive())
                                <a class="ms-1 ps-1 border-start" href="{{ route('account.settings.organizer.payment_methods') }}">{{ __('Payment Methods') }}</a>
                            @endif
                            <a class="ms-1 ps-1 border-start" href="{{ route('account.settings.organizer.cancel') }}">{{ __('Cancel subscription') }}</a>
                        @elseif ($current_subscription->isCancelled())
                            <h3>{{ __('You\'re subscription has cancelled') }}</h3>
                            <p>{{ __(
                                'Your current subscription is paid through :date. After that, you\'ll no longer be an organizer. To continue leading your :app_name as an organizer, or to start a new :app_name group, all you need to do is turn your subscription payments back on.'
                                , [
                                    'app_name' => $application_name,
                                    'date' => $current_subscription->ends_at->format('M d, Y'),
                                ]) }}
                            </p>
                            <a class="btn btn-primary" href="{{ route('account.settings.organizer.activate') }}">{{ __('Turn it back on') }}</a>
                            <hr>
                            <a href="{{ route('account.settings.organizer.payment_history') }}">{{ __('Payment history') }}</a>
                            @if (\App\Models\SystemSetting::isStripeActive())
                                <a class="ms-1 ps-1 border-start" href="{{ route('account.settings.organizer.payment_methods') }}">{{ __('Payment Methods') }}</a>
                            @endif
                        @else
                            <h3>{{ __('You\'re subscription has expired') }}</h3>
                            <p>{{ __('Something\'s gone wrong, and we haven\'t been able receive payments from you. To turn your subscription back on and continue as an organizer, you\'ll need to enter your credit or debit card details again and renew your pricing plan.') }}</p>
                            <a class="btn btn-primary" href="{{ route('checkout.payment', ['plan_id' => $current_subscription->plan->id]) }}">{{ __('Renew your plan') }}</a>
                            <hr>
                            <a href="{{ route('account.settings.organizer.payment_history') }}">{{ __('Payment history') }}</a>
                            @if (\App\Models\SystemSetting::isStripeActive())
                                <a class="ms-1 ps-1 border-start" href="{{ route('account.settings.organizer.payment_methods') }}">{{ __('Payment Methods') }}</a>
                            @endif
                        @endif
                    @else
                        <h3>{{ __('You currently don\'t have a subscription') }}</h3>
                        <p>{{ __('Want to start your own group on :app_name?', ['app_name' => $application_name]) }}</p>
                        <a class="btn btn-primary" href="{{ route('start.index') }}">{{ __('Start a new group') }}</a>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection