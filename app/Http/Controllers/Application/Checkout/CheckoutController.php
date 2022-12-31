<?php

namespace App\Http\Controllers\Application\Checkout;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\SystemSetting;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    /**
     * Display Plans before Checkout
     *
     * @return \Illuminate\Http\Response
     */
    public function plans()
    {
        return view('application.checkout.plans');
    }

    /**
     * Display the Payment Page
     *
     * @param  \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function payment(Request $request)
    {
        // Find plan
        if (!$plan = Plan::find($request->plan_id)) {
            // Plan not found redirect user to choose plan page
            return redirect()->route('checkout.plans');
        }

        // If the plan is free or the dummy payment gateway is active
        if ($plan->isFree() || SystemSetting::isDummyPaymentActive()) {
            return redirect()->route('checkout.dummy.payment', ['plan_id' => $plan->id]);
        }
        
        return view('application.checkout.payment', [
            'plan' => $plan,
        ]);
    }
}
