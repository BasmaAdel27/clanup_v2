<div class="card mb-5 mb-lg-0 border-0 shadow">
    <div class="card-body">
        <h2 class="text-base subtitle text-center text-primary py-2">{{ $plan->name }}</h2>
        <p class="text-muted text-center">
            @if (isset($yearly))
                <span class="h1 text-dark">{{ money($plan->yearly->price, $plan->yearly->currency, true) }}</span>
                <span class="ms-2 text-capitalize">/ {{ __($plan->yearly->invoice_interval) }}</span>
            @else
                <span class="h1 text-dark">{{ money($plan->price, $plan->currency, true) }}</span>
                <span class="ms-2 text-capitalize">/ {{ __($plan->invoice_interval) }}</span>
            @endif
        </p>
        <div class="text-center">
            <span class="badge rounded-pill bg-orange">
                {{ __('Save :money on yearly plan', ['money' => money($plan->yearly->price, $plan->yearly->currency, true)->subtract(money($plan->price, $plan->currency, true)->multiply(12))->multiply(-1)]) }}
            </span>
        </div>
        <hr>
        <ul class="fa-ul my-4">
            <li class="mb-3">
                <span class="fa-li text-primary"><i class="fas fa-check"></i></span>
                @if (optional($plan->getFeatureBySlug('groups'))->value == '-1')
                    {{ __('Unlimited Groups') }}
                @else
                    {{ __(':count Groups', ['count' => optional($plan->getFeatureBySlug('groups'))->value]) }}
                @endif
            </li>
            <li class="mb-3">
                @if (optional($plan->getFeatureBySlug('can_access_communication_tools'))->value)
                    <span class="fa-li text-primary"><i class="fas fa-check"></i></span>
                    {{ __('Communication Tools') }}
                @else
                    <span class="fa-li text-muted"><i class="fas fa-times"></i></span>
                    <span class="text-muted">{{ __('Communication Tools') }}</span>
                @endif
            </li>
            <li class="mb-3">
                @if (optional($plan->getFeatureBySlug('can_access_email_addresses'))->value)
                    <span class="fa-li text-primary"><i class="fas fa-check"></i></span>
                    {{ __('Gain access to attendee email addresses to grow your database')}}
                @else
                    <span class="fa-li text-muted"><i class="fas fa-times"></i></span>
                    <span class="text-muted">{{ __('Gain access to attendee email addresses to grow your database') }}</span>
                @endif
            </li>
            <li class="mb-3">
                @if (optional($plan->getFeatureBySlug('can_access_custom_reports'))->value)
                    <span class="fa-li text-primary"><i class="fas fa-check"></i></span>
                    {{ __('Track your community with a performance dashboard') }}
                @else
                    <span class="fa-li text-muted"><i class="fas fa-times"></i></span>
                    <span class="text-muted">{{ __('Track your community with a performance dashboard') }}</span>
                @endif
            </li>
            <li class="mb-3">
                @if (optional($plan->getFeatureBySlug('can_display_sponsors'))->value)
                    <span class="fa-li text-primary"><i class="fas fa-check"></i></span>
                    {{ __('Display sponsors') }}
                @else
                    <span class="fa-li text-muted"><i class="fas fa-times"></i></span>
                    <span class="text-muted">{{ __('Display sponsors') }}</span>
                @endif
            </li>
        </ul>
        <div class="text-center px-3">
            <a class="btn btn-primary w-100" href="{{ isset($yearly) ? route('checkout.payment', ['plan_id' => $plan->yearly->id]) : route('checkout.payment', ['plan_id' => $plan->id]) }}">
                @if ($plan->hasTrial() and !$auth_user->subscription())
                    {{ __('Start Trial') }}
                @else
                    {{ __('Get Started') }}
                @endif
            </a>
        </div>
    </div>
</div>