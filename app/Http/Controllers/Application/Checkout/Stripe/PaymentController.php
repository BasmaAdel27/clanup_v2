<?php

namespace App\Http\Controllers\Application\Checkout\Stripe;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Services\Gateways\Stripe;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Stripe Payment for existing payment method
     *
     * @param  \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function payment(Request $request)
    {
        // Get authenticated user
        $user = $request->user();

        // Find plan
        if (!$plan = Plan::find($request->plan_id)) {
            // Plan not found redirect user to choose plan page
            return redirect()->route('checkout.plans');
        }

        $stripe = new Stripe($user, $plan);
        $payment_method_id = $request->selectedPaymentMethod;

        // Charge payment method
        if ($payment_method_id) {
            $payment_intent = $stripe->chargeExistingCard($payment_method_id);
            return redirect()->route('checkout.stripe.callback', [
                'payment_intent' => $payment_intent ? $payment_intent->id : '',
                'plan_id' => $plan->id,
            ]);
        }

        // Return to payment page if something went wrong
        session()->flash('alert-danger', __('Something went wrong.'));
        return redirect()->route('checkout.payment', ['plan_id' => $request->plan_id]);
    }

    /**
     * Stripe Callback
     *
     * @param  \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function callback(Request $request)
    {
        // Get authenticated user
        $user = $request->user();

        // Find plan
        if (!$plan = Plan::find($request->plan_id)) {
            // Plan not found redirect user to choose plan page
            return redirect()->route('checkout.plans');
        } 

        // Stripe Service
        $stripe = new Stripe($user, $plan);

        // Find setup intent
        $setup_intent_id = $request->get('setup_intent', null);
        $setup_intent = $stripe->retrieveSetupIntent($setup_intent_id);
        if ($setup_intent && $setup_intent->status == 'succeeded') {
            session()->flash('alert-success', __('Success! Your trial has started. And your payment method securely saved for future payments.'));
            return redirect()->route('home');
        } else if ($setup_intent && $setup_intent->status == 'processing') {
            session()->flash('alert-success', __('Payment method is processing. We\'ll update you when the payment method setup is completed.'));
            return redirect()->route('home');
        } else if ($setup_intent && $setup_intent->status == 'requires_payment_method') {
            session()->flash('alert-danger', __('Payment method could not added. Please try another payment method.'));
            return redirect()->route('checkout.payment', ['plan_id' => $request->plan_id]);
        }

        // Find payment intent
        $payment_intent_id = $request->get('payment_intent', null);
        $payment_intent = $stripe->retrievePaymentIntent($payment_intent_id);
        if ($payment_intent && $payment_intent->status == 'succeeded') {
            session()->flash('alert-success', __('Success! Your payment is received.'));
            return redirect()->route('home');
        } else if ($payment_intent && $payment_intent->status == 'processing') {
            session()->flash('alert-success', __('Your payment is processing. We\'ll notify you when the payment is completed.'));
            return redirect()->route('home');
        } else if ($payment_intent && $payment_intent->status == 'requires_payment_method') {
            session()->flash('alert-danger', __('Payment failed. Please try another payment method.'));
            return redirect()->route('checkout.payment', ['plan_id' => $request->plan_id]);
        }

        session()->flash('alert-danger', __('Something went wrong.'));
        return redirect()->route('checkout.payment', ['plan_id' => $request->plan_id]);
    }

    /**
     * Stripe Remove Payment Method
     *
     * @param  \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function remove_payment_method(Request $request)
    {
        // Get authenticated user
        $user = $request->user();

        // Detach payment method id
        if ($request->payment_method_id) {
            $stripe = new Stripe($user, null);
            $stripe->detachPaymentMethod($request->payment_method_id);
        }        
        
        session()->flash('alert-success', __('Payment method deleted successfully.'));
        return redirect()->route('account.settings.organizer');
    }
}
