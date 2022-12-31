<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\Update;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class UserController extends Controller
{
    /**
     * Display Super Admin Users Page
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get Users
        $users = QueryBuilder::for(User::class)
            ->allowedFilters([
                AllowedFilter::scope('search'),
                AllowedFilter::exact('role'),
            ])
            ->orderBy('id', 'desc')
            ->paginate()
            ->appends(request()->query());

        return view('admin.users.index', [
            'users' => $users
        ]);
    }

    /**
     * Display the Form for Editing User
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User         $user
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, User $user)
    {
        $subscribed_to_plan = optional($user->currentSubscriptionPlan())->id;
        
        // Fill model with old input
        if (!empty($request->old())) {
            $user->fill($request->old());
        }

        return view('admin.users.edit', [
            'user' => $user,
            'subscribed_to_plan' => $subscribed_to_plan,
        ]);
    }

    /**
     * Update the User in Database
     *
     * @param \App\Http\Requests\Admin\User\Update $request
     * @param \App\Models\User                     $user
     *
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function update(Update $request, User $user)
    {
        $validated = $request->validated();

        // Update the Member
        unset($validated['password']);
        unset($validated['password_confirmation']);
        $user->update($validated);

        // If Password fields are filled
        if ($request->password) {
            $user->password = Hash::make($request->password);
            $user->save();
        }

        // Subscribe or Change plan
        if ($request->plan_id) {
            $plan = Plan::findOrFail($request->plan_id);

            // If already in the plan
            if ($user->subscription()) {
                $user->subscription()->changePlan($plan);
            } else {
                $user->newSubscription($plan);
            }
        } else {
            // Remove plan if user subscribed to a plan
            if ($user->subscription()) {
                $user->subscription()->cancel(true);
            } 
        }

        session()->flash('alert-success', __('User updated'));
        return redirect()->route('admin.users');
    }

    /**
     * Delete the User
     *
     * @param \App\Models\User         $user
     *
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function delete(User $user)
    {
        // Delete user
        $user->delete();

        session()->flash('alert-success', __('User deleted'));
        return redirect()->route('admin.users');
    }
}
