<?php

namespace App\Http\Controllers\Application\Checkout\Dummy;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Services\Gateways\Dummy;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Dummy Payment
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

        try {
            $dummy = new Dummy($user, $plan);
            $dummy->charge();
        } catch (\Throwable $th) {
            // Return to payment page if something went wrong
            session()->flash('alert-danger', __('Something went wrong.'));
            return redirect()->route('checkout.payment', ['plan_id' => $request->plan_id]);
        }

        session()->flash('alert-success', __('Success!'));
        return redirect()->route('home');
    }
}
