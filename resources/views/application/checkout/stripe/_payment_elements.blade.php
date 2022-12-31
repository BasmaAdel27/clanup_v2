@php
    // Setup Stripe Intent
    $stripe = new \App\Services\Gateways\Stripe($auth_user, $plan);
    $has_trial = $plan->hasTrial() && !$auth_user->subscription();
    if ($has_trial) {
        $intent = $stripe->createSetupIntent();
    } else {
        $intent = $stripe->createPaymentIntent();
    }

    // Get saved cards
    $available_payment_methods = $stripe->getAvailablePaymentMethods();
    $saved_cards = $available_payment_methods ? $available_payment_methods->data : [];
@endphp

@push('page_head_scripts')
    <script src="https://js.stripe.com/v3/"></script>
@endpush

<div class="accordion" id="paymentAccordion">
    @if ($saved_cards)
        <div class="accordion-item rounded-0">
            <h2 class="accordion-header mb-0" id="headingOne">
                <button class="accordion-button bg-gray-200 rounded-0 text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#savedCardsCollapse" aria-expanded="true" aria-controls="savedCardsCollapse">
                    {{ __('Saved Payment Methods') }}
                </button>
            </h2>
            <div id="savedCardsCollapse" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#paymentAccordion">
                <div class="accordion-body text-dark">
                    <form action="{{ route('checkout.stripe.payment', ['plan_id' => $plan->id]) }}" method="POST">
                        @csrf
                        
                        @foreach ($saved_cards as $payment_method)
                            <label for="CC-{{ $loop->index }}" class="saved-pm-radio d-flex align-items-center rounded-sm border p-3 mb-4">
                                <div class="d-flex align-items-center">
                                    <input id="CC-{{ $loop->index }}" type="radio" name="selectedPaymentMethod" value="{{ $payment_method['id'] }}" @if($loop->first) checked="" @endif>
                                    @switch($payment_method['card']['brand'])
                                        @case('visa')
                                            <i class="fab fs-3 ms-2 fa-cc-visa"></i>
                                            @break
                                        @case('amex')
                                            <i class="fab fs-3 ms-2 fa-cc-amex"></i>
                                            @break
                                        @case('discover')
                                            <i class="fab fs-3 ms-2 fa-cc-discover"></i>
                                            @break
                                        @case('jcb')
                                            <i class="fab fs-3 ms-2 fa-cc-jcb"></i>
                                            @break
                                        @case('mastercard')
                                            <i class="fab fs-3 ms-2 fa-cc-mastercard"></i>
                                            @break
                                        @case('diners')
                                            <i class="fab fs-3 ms-2 fa-diners-club"></i>
                                            @break
                                        @default
                                            <i class="fab fs-3 ms-2 fa-stripe"></i>
                                    @endswitch
                                </div>

                                <label class="d-flex flex-column ms-2" for="CC-{{ $loop->index }}">
                                    **** **** **** {{ $payment_method['card']['last4'] }}
                                </label>
                            </label>
                        @endforeach
                        
                        <div class="form-group col-sm-12">
                            <button type="submit" class="btn btn-primary w-100">{{ __('Use payment method') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
    
    <div class="accordion-item rounded-0">
        <h2 class="accordion-header mb-0" id="headingTwo">
            <button class="accordion-button bg-gray-200 rounded-0 text-dark @if ($saved_cards) collapsed @endif" type="button" data-bs-toggle="collapse" data-bs-target="#addNewCardCollapse" aria-expanded="false" aria-controls="addNewCardCollapse">
                {{ __('Add new credit card') }}
            </button>
        </h2>
        <div id="addNewCardCollapse" class="accordion-collapse collapse @if (!$saved_cards) show @endif" aria-labelledby="headingTwo" data-bs-parent="#paymentAccordion">
            <div class="accordion-body">
                <div class="d-flex justify-content-center">
                    <div id="spinner" class="spinner-border" role="status">
                        <span class="visually-hidden">{{ __('Loading...') }}</span>
                    </div>
                </div>
                <form id="payment-form" class="d-none" data-secret="{{ optional($intent)->client_secret }}">
                    <div id="payment-element"></div>
                    <div id="error-message" class="text-danger mt-2"></div>
                    @if ($has_trial)
                        <button id="submit" class="btn btn-primary mt-3 w-100">
                            {{ __('Start Trial') }}
                        </button>
                        <p class="text-muted fs-7 mt-2">
                            {{ __('Your full-priced subscription will begin after your trial ends and will renew automatically until canceled. You can cancel this renewal at any point before your trial ends.') }}
                        </p>
                    @else
                        <button id="submit" class="btn btn-primary mt-3 w-100">
                            {{ __('Subscribe') }}
                        </button>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>


@push('page_body_scripts')
    <script>
        var stripe = Stripe('{{ get_system_setting("stripe_publishable_key") }}');
        var options = {
            clientSecret: '{{ optional($intent)->client_secret }}',
        };
        var elements = stripe.elements(options);
        var paymentElement = elements.create('payment');
        paymentElement.mount('#payment-element');

        paymentElement.on('ready', function(event) {
            document.querySelector("#spinner").classList.add('d-none');
            document.querySelector("#payment-form").classList.remove('d-none');
        });

        var form = document.getElementById('payment-form');
        var submit_button = document.getElementById('submit')

        @if ($has_trial)
            form.addEventListener('submit', async (event) => {
                submit_button.disabled = true;
                event.preventDefault();
                var {error} = await stripe.confirmSetup({
                    elements,
                    confirmParams: {
                        return_url: '{{ route("checkout.stripe.callback", ["plan_id" => $plan->id]) }}',
                    },
                });
                if (error) {
                    var messageContainer = document.querySelector('#error-message');
                    messageContainer.textContent = error.message;
                }
                submit_button.disabled = false;
            });
        @else
            form.addEventListener('submit', async (event) => {
                submit_button.disabled = true;
                event.preventDefault();
                var {error} = await stripe.confirmPayment({
                    elements,
                    confirmParams: {
                        return_url: '{{ route("checkout.stripe.callback", ["plan_id" => $plan->id]) }}',
                    },
                });
                if (error) {
                    var messageContainer = document.querySelector('#error-message');
                    messageContainer.textContent = error.message;
                }
                submit_button.disabled = false;
            });
        @endif
    </script>
@endpush