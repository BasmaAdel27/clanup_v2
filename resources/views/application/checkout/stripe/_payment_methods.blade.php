@php
    $stripe = new \App\Services\Gateways\Stripe($auth_user, null);
    $available_payment_methods = $stripe->getAvailablePaymentMethods();
    $saved_cards = $available_payment_methods ? $available_payment_methods->data : [];
@endphp
<div class="list-group rounded-sm my-4">
    @foreach ($saved_cards as $payment_method)
        <div class="list-group-item list-group-item-action">
            <div class="d-flex flex-row justify-content-between">
                <div class="d-flex align-items-center">
                    <p class="mb-0">
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

                    </p>
                    <p class="mb-0 ps-3">
                        **** **** **** {{ $payment_method['card']['last4'] }}
                    </p>
                </div>
                <a href="{{ route('checkout.stripe.remove_payment_method', ['payment_method_id' => $payment_method['id']]) }}" class="btn btn-link text-danger delete-confirm" data-title="{{ __('Are you sure?') }}" data-text="{{ __('This record will be deleted.') }}">{{ __('Delete') }}</a>
            </div>
        </div>
    @endforeach
</div>
