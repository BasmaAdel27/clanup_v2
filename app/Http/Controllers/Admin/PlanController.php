<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Plan\Store;
use App\Http\Requests\Admin\Plan\Update;
use Illuminate\Http\Request;
use App\Models\Plan;
use Illuminate\Support\Str;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class PlanController extends Controller
{
    /**
     * Display Super Admin Plans Page
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get Plans
        $plans = QueryBuilder::for(Plan::class)
            ->allowedFilters([
                AllowedFilter::partial('name'),
            ])
            ->whereInvoiceInterval('month')
            ->orderBy('id', 'desc')
            ->paginate()
            ->appends(request()->query());

        return view('admin.plans.index', [
            'plans' => $plans
        ]);
    }

    /**
     * Display the Form for Creating New Plan
     *
     * @param \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $plan = new Plan();
 
        // Fill model with old input
        if (!empty($request->old())) {
            $plan->fill($request->old());
        }

        return view('admin.plans.create', [
            'plan' => $plan,
        ]);
    }

    /**
     * Store the plan in Database
     *
     * @param \App\Http\Requests\Admin\Plan\Store $request
     *
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function store(Store $request)
    {
        // Slug
        $slug = str_replace('-yearly', '', Str::slug($request->name, '-'));

        // Check slug
        while (Plan::where('slug', $slug)->exists()) {
            $slug .= random_int(1, 9);
        };
 
        // Create new Monthly Plan
        $monthly_plan = Plan::create([
            'slug' => $slug,
            'name' => $request->name,
            'description' => $request->description,
            'is_active' => true,
            'price' => $request->price,
            'invoice_period' => 1,
            'invoice_interval' => 'month',
            'trial_period' => $request->trial_period, // trial days
            'trial_interval' => 'day',
            'order' => $request->order ? $request->order : 0,
            'paypal_plan_id' => $request->paypal_plan_id,
        ]);
        $monthly_plan->addPlanFeatures($request->features);

        // Create new Yearly Plan
        $yearly_plan = Plan::create([
            'slug' => $slug . '-yearly',
            'name' => $request->name,
            'description' => $request->description,
            'is_active' => true,
            'price' => $request->yearly_price,
            'invoice_period' => 1,
            'invoice_interval' => 'year',
            'trial_period' => $request->trial_period, // trial days
            'trial_interval' => 'day',
            'order' => $request->order ? $request->order : 0,
            'paypal_plan_id' => $request->paypal_yearly_plan_id,
        ]);
        $yearly_plan->addPlanFeatures($request->features);

        session()->flash('alert-success', __('Plan created'));
        return redirect()->route('admin.plans');
    }


    /**
     * Display the Form for Editing Plan
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Plan         $plan
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Plan $plan)
    {
        // Fill model with old input
        if (!empty($request->old())) {
            $plan->fill($request->old());
        }

        return view('admin.plans.edit', [
            'plan' => $plan,
        ]);
    }

    /**
     * Update the Plan in Database
     *
     * @param \App\Http\Requests\Admin\Plan\Update $request
     * @param \App\Models\Plan                     $plan
     *
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function update(Update $request, Plan $plan)
    {
        // Update the Plan
        $plan->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'trial_period' => $request->trial_period,
            'order' => $request->order,
            'paypal_plan_id' => $request->paypal_plan_id,
        ]);
        $plan->updatePlanFeatures($request->features);

        // Update yearly plan
        $plan->yearly->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->yearly_price,
            'trial_period' => $request->trial_period,
            'order' => $request->order,
            'paypal_plan_id' => $request->paypal_yearly_plan_id,
        ]);
        $plan->yearly->updatePlanFeatures($request->features);

        session()->flash('alert-success', __('Plan updated'));
        return redirect()->route('admin.plans');
    }

    /**
     * Delete the Plan
     *
     * @param \App\Models\Plan $plan
     *
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function delete(Plan $plan)
    {
        // Delete plan
        $plan->delete();

        // Delete yearly plan
        $yearly_plan = $plan->yearly;
        $yearly_plan->delete();

        session()->flash('alert-success', __('Plan deleted'));
        return redirect()->route('admin.plans');
    }
}
