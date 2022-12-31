<?php

namespace App\Http\Controllers\Application\Account\Settings;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrganizerSettingsController extends Controller
{
    /**
     * Show the account organizer settings page.
     *
     * @param  \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $current_subscription = $user->subscription();
        $current_subscribed_plan = $user->currentSubscriptionPlan();
        $last_order = Order::where('user_id', $user->id)->latest()->first();

        return view('application.account.settings.organizer.index', [
            'current_subscription' => $current_subscription,
            'current_subscribed_plan' => $current_subscribed_plan,
            'last_order' => $last_order,
        ]);
    }

    /**
     * Show the account payment history page.
     *
     * @param  \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function payment_history(Request $request)
    {
        $user = $request->user();
        $orders = Order::where('user_id', $user->id)->latest()->paginate();

        return view('application.account.settings.organizer.payment_history', [
            'orders' => $orders
        ]);
    }

    /**
     * Show the account payment methods page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function payment_methods()
    {
        return view('application.account.settings.organizer.payment_methods');
    }

    /**
     * Activate cancelled subscription.
     *
     * @param  \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function activate(Request $request)
    {
        $user = $request->user();
        $current_subscription = $user->subscription();

        // Make sure the subscription is cancelled
        if ($current_subscription && $current_subscription->isCancelled()) {
            $current_subscription->activate();

            session()->flash('alert-success', __('Your subscription is activated back.'));
            return redirect()->route('account.settings.organizer');
        }

        session()->flash('alert-danger', __('Your subscription is already active.'));
        return redirect()->route('account.settings.organizer');
    }

    /**
     * Show the cancel organizer subscription page.
     *
     * @param  \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function cancel(Request $request)
    {
        $user = $request->user();
        $current_subscription = $user->subscription();
        $current_subscribed_plan = $user->currentSubscriptionPlan();

        // Redirect user back to organizer settings if the subscription is already cancelled
        if ($current_subscription && $current_subscription->isCancelled()) {
            session()->flash('alert-danger', __('Your subscription is already cancelled.'));
            return redirect()->route('account.settings.organizer');
        }

        return view('application.account.settings.organizer.cancel', [
            'current_subscription' => $current_subscription,
            'current_subscribed_plan' => $current_subscribed_plan,
        ]);
    }

    /**
     * Cancel organizer subscription of user
     *
     * @param  \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function cancel_store(Request $request)
    {
        $user = $request->user();
        $current_subscription = $user->subscription();

        // Redirect user back to organizer settings if the subscription is already cancelled
        if ($current_subscription && $current_subscription->isCancelled()) {
            session()->flash('alert-danger', __('Your subscription is already cancelled.'));
            return redirect()->route('account.settings.organizer');
        }

        // Make sure there is a subscription
        if ($current_subscription) {
            $current_subscription->cancel();
        }

        session()->flash('alert-success', __('You have successfully cancelled your subscription.'));
        return redirect()->route('account.settings.organizer');
    }
}
