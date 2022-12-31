@push('page_head_scripts')
    <script src="https://www.paypal.com/sdk/js?client-id={{ get_system_setting('paypal_client_id') }}&vault=true&intent=subscription"></script>
@endpush

<div class="accordion" id="paymentAccordion">
    <div class="accordion-item rounded-0">
        <h2 class="accordion-header mb-0" id="headingTwo">
            <button class="accordion-button bg-gray-200 rounded-0 text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#paywithPaypal" aria-expanded="false" aria-controls="paywithPaypal">
                {{ __('Pay with Paypal') }}
            </button>
        </h2>
        <div id="addNewCardCollapse" class="accordion-collapse collapse show p-5" aria-labelledby="headingTwo" data-bs-parent="#paymentAccordion">
            <div id="paypal-button-container"></div>
            <div id="paypal_success" class="text-center d-none">
                <div class="avatar avatar-lg avatar-rounded bg-success mb-3">
                    <i class="fas fa-check"></i>
                </div>
                <h2>{{ __('Success! We are processing your payment. It may take 1-2 minutes.') }}</h2> 
                <a class="btn btn-primary mt-2" href="{{ route('home') }}">{{ __('Return to home') }}</a>
            </div>
            <div id="paypal_error" class="text-center d-none">
                <div class="avatar avatar-lg avatar-rounded bg-danger mb-3">
                    <i class="fas fa-exclamation"></i>
                </div>
                <h2>{{ __('Payment failed. Please try another payment method.') }}</h2> 
                <a class="btn btn-primary mt-2" href="{{ url()->full() }}">{{ __('Try again') }}</a>
            </div>
        </div>
    </div>
</div>

@php
    $hasActiveSubscription = '';
    $plansAreDifferent = '';
    $subscriptionId = '';
    $subscription = $auth_user->subscription();
    if ($subscription) {
        $hasActiveSubscription = $subscription->isActive() && array_key_exists('billing_agreement_id', $subscription->data);
        $plansAreDifferent = $subscription->plan_id != $plan->id;
        $subscriptionId = array_key_exists('billing_agreement_id', $subscription->data) ? $subscription->data['billing_agreement_id'] : '';
    }
@endphp

@push('page_body_scripts')
    <script>
        var hasActiveSubscription = "{{ $hasActiveSubscription }}";
        var plansAreDifferent = "{{ $plansAreDifferent }}";
        var subscriptionId = "{{ $subscriptionId }}";

        paypal.Buttons({
            createSubscription: function(data, actions) {
                if (hasActiveSubscription != '' && plansAreDifferent != '' && subscriptionId != '') {
                    console.log('revise');
                    return actions.subscription.revise(subscriptionId, {
                        'plan_id': "{{ $plan->paypal_plan_id }}"
                    });
                } else {
                    console.log('create');
                    return actions.subscription.create({
                        'plan_id': "{{ $plan->paypal_plan_id }}",
                        'custom_id': "{{ $auth_user->uid }}",
                    });
                }
            },
            onApprove: function(data, actions) {
                console.log(data);
                if (data.subscriptionID.includes('I-')){
                    $('#paypal-button-container').hide();
                    $('#paypal_success').removeClass('d-none');
                } else {
                    $('#paypal-button-container').addClass('d-none');
                    $('#paypal_error').removeClass('d-none');
                }
            },
            onCancel: function(data, actions) {
                $('#paypal-button-container').addClass('d-none');
                $('#paypal_error').removeClass('d-none');
            },
            onError: function(err) {
                console.log(err);
                $('#paypal-button-container').addClass('d-none');
                $('#paypal_error').removeClass('d-none');
            }
        }).render('#paypal-button-container');
    </script>
@endpush